<?php

namespace App\Http\Controllers\School;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\School;
use App\Models\User;
use App\Models\ProgramType;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SchoolPortalController extends Controller
{
    // Middleware is applied via routes, no constructor needed

    /**
     * Display the school dashboard
     */
    public function dashboard()
    {
        $user = Auth::user();
        
        // Get the school associated with this user
        $school = School::where('email', $user->email)->first();
        
        if (!$school) {
            return redirect()->route('login')->with('error', 'School not found. Please contact administrator.');
        }

        // Get school's students
        $students = Student::where('created_by_school_id', $school->id)
            ->with(['programType', 'user'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Get statistics
        $totalStudents = Student::where('created_by_school_id', $school->id)->count();
        $activeStudents = Student::where('created_by_school_id', $school->id)
            ->where('status', 'active')
            ->count();
        // Count students with user accounts by checking if a user exists with the same email
        $studentsWithAccounts = Student::where('created_by_school_id', $school->id)
            ->whereExists(function($query) {
                $query->select(DB::raw(1))
                      ->from('users')
                      ->whereColumn('users.email', 'students.email');
            })
            ->count();

        // Get program types for student creation
        $programTypes = ProgramType::where('is_active', true)->get();

        return view('school.dashboard', compact(
            'school', 
            'students', 
            'totalStudents', 
            'activeStudents', 
            'studentsWithAccounts',
            'programTypes'
        ));
    }

    /**
     * Display the students management page
     */
    public function studentsIndex()
    {
        $user = Auth::user();
        
        // Get the school associated with this user
        $school = School::where('email', $user->email)->first();
        
        if (!$school) {
            return redirect()->route('login')->with('error', 'School not found. Please contact administrator.');
        }

        // Get school's students with pagination (both created by this school or assigned to this school)
        $students = Student::where(function($query) use ($school) {
                $query->where('created_by_school_id', $school->id)
                      ->orWhere('school_id', $school->id);
            })
            ->with(['programType'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        // Get program types for student creation
        $programTypes = ProgramType::where('is_active', true)->get();

        return view('school.students.index', compact(
            'school', 
            'students', 
            'programTypes'
        ));
    }

    /**
     * Store a new student created by school
     */
    public function storeStudent(Request $request)
    {
        $user = Auth::user();
        $school = School::where('email', $user->email)->first();

        if (!$school) {
            return response()->json([
                'success' => false,
                'message' => 'School not found.'
            ]);
        }

        $validator = \Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|max:20',
            'program_type_id' => 'required|exists:program_types,id',
            'class' => 'nullable|string|max:50',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female',
            'address' => 'nullable|string|max:500',
            'parent_contact' => 'nullable|string|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed: ' . $validator->errors()->first()
            ]);
        }

        try {
            DB::beginTransaction();

            // Create user account for student
            $studentUser = User::create([
                'name' => $request->first_name . ' ' . $request->last_name,
                'username' => strtolower(str_replace(' ', '', $request->first_name . $request->last_name)) . rand(100, 999),
                'email' => $request->email,
                'password' => Hash::make('student123'), // Default password
                'role' => 'student',
                'user_type_id' => 3, // Assuming 3 is for students - adjust based on your user_types table
                'email_verified_at' => now(),
            ]);

            // Calculate age if date of birth is provided
            $age = null;
            if ($request->date_of_birth) {
                $age = \Carbon\Carbon::parse($request->date_of_birth)->age;
            }

            // Create student record (user account linked via email)
            $student = Student::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'full_name' => $request->first_name . ' ' . $request->last_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'program_type_id' => $request->program_type_id,
                'school_id' => $school->id,
                'created_by_school_id' => $school->id,
                'is_school_managed' => true,
                'class' => $request->class,
                'date_of_birth' => $request->date_of_birth,
                'age' => $age,
                'gender' => $request->gender,
                'address' => $request->address,
                'city' => $request->city ?? '',
                'parent_contact' => $request->parent_contact,
                'status' => 'active',
                'payment_status' => 'pending',
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Student created successfully with user account.',
                'student' => $student->load(['programType', 'user'])
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to create student: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Update student information
     */
    public function updateStudent(Request $request, Student $student)
    {
        $user = Auth::user();
        $school = School::where('email', $user->email)->first();

        // Verify school owns this student
        if (!$school || $student->created_by_school_id !== $school->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized to edit this student.'
            ]);
        }

        // Find user account linked to this student via email
        $linkedUser = User::where('email', $student->email)->first();
        $userId = $linkedUser ? $linkedUser->id : null;

        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $userId,
            'phone' => 'required|string|max:20',
            'program_type_id' => 'required|exists:program_types,id',
            'class' => 'nullable|string|max:50',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female',
            'address' => 'nullable|string|max:500',
            'parent_contact' => 'nullable|string|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed: ' . $validator->errors()->first()
            ]);
        }

        try {
            DB::beginTransaction();

            // Calculate age if date of birth is provided
            $age = null;
            if ($request->date_of_birth) {
                $age = \Carbon\Carbon::parse($request->date_of_birth)->age;
            }

            // Update student record
            $student->update([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'full_name' => $request->first_name . ' ' . $request->last_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'program_type_id' => $request->program_type_id,
                'class' => $request->class,
                'date_of_birth' => $request->date_of_birth,
                'age' => $age,
                'gender' => $request->gender,
                'address' => $request->address,
                'city' => $request->city ?? '',
                'parent_contact' => $request->parent_contact,
            ]);

            // Update user account if exists (linked via email)
            $linkedUser = User::where('email', $student->email)->first();
            if ($linkedUser) {
                $linkedUser->update([
                    'name' => $request->first_name . ' ' . $request->last_name,
                    'email' => $request->email,
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Student updated successfully.',
                'student' => $student->load(['programType', 'user'])
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to update student: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Delete student
     */
    public function deleteStudent(Student $student)
    {
        $user = Auth::user();
        $school = School::where('email', $user->email)->first();

        // Verify school owns this student
        if (!$school || $student->created_by_school_id !== $school->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized to delete this student.'
            ]);
        }

        try {
            DB::beginTransaction();

            // Delete user account if exists
            if ($student->user) {
                $student->user->delete();
            }

            // Delete student record
            $student->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Student deleted successfully.'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete student: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Toggle admin management permission for school
     */
    public function toggleAdminPermission(Request $request)
    {
        $user = Auth::user();
        $school = School::where('email', $user->email)->first();

        if (!$school) {
            return response()->json([
                'success' => false,
                'message' => 'School not found.'
            ]);
        }

        try {
            DB::beginTransaction();

            $allowAdmin = $request->boolean('allow_admin');

            // Update school permission
            $school->update([
                'allow_admin_management' => $allowAdmin,
                'admin_permission_granted_at' => $allowAdmin ? now() : null,
                'admin_permission_granted_by' => $allowAdmin ? $user->name : null,
            ]);

            // Update all school students' admin_can_manage flag
            Student::where('created_by_school_id', $school->id)
                ->update(['admin_can_manage' => $allowAdmin]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => $allowAdmin 
                    ? 'Admin management permission granted successfully.' 
                    : 'Admin management permission revoked successfully.',
                'allow_admin' => $allowAdmin
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to update permission: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Get student data for editing
     */
    public function getStudent(Student $student)
    {
        $user = Auth::user();
        $school = School::where('email', $user->email)->first();

        // Verify school owns this student
        if (!$school || $student->created_by_school_id !== $school->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized to view this student.'
            ]);
        }

        return response()->json([
            'success' => true,
            'student' => $student->load(['programType', 'user'])
        ]);
    }
}
