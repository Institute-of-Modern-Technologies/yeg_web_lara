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

class NewSchoolPortalController extends Controller
{
    /**
     * Constructor - Authentication is handled by routes middleware
     */
    public function __construct()
    {
        // Authentication is handled by the 'auth' middleware in routes
        // No need to call middleware here since routes already handle it
    }

    /**
     * Get the current school for the authenticated user
     */
    private function getCurrentSchool()
    {
        $user = Auth::user();
        
        // First check session
        if (session('school_id')) {
            return School::find(session('school_id'));
        }
        
        // Try multiple lookup methods
        $school = null;
        
        // 1. Try user_id lookup first (most reliable)
        $school = School::where('user_id', $user->id)->first();
        
        // 2. Fallback to email lookup
        if (!$school) {
            $school = School::where('email', $user->email)->first();
            
            // If found by email, update the user_id for future lookups
            if ($school && !$school->user_id) {
                $school->user_id = $user->id;
                $school->save();
            }
        }
        
        if ($school) {
            // Store in session for future requests
            session(['school_id' => $school->id, 'school_name' => $school->name]);
            return $school;
        }
        
        return null;
    }

    /**
     * Display the school dashboard
     */
    public function dashboard()
    {
        $school = $this->getCurrentSchool();
        
        if (!$school) {
            Auth::logout();
            return redirect()->route('login')->with('error', 'School account not found.');
        }

        // Get school's students statistics
        $totalStudents = Student::where(function($query) use ($school) {
            $query->where('created_by_school_id', $school->id)
                  ->orWhere('school_id', $school->id);
        })->count();

        $activeStudents = Student::where(function($query) use ($school) {
            $query->where('created_by_school_id', $school->id)
                  ->orWhere('school_id', $school->id);
        })->where('status', 'active')->count();

        $studentsWithAccounts = Student::where(function($query) use ($school) {
            $query->where('created_by_school_id', $school->id)
                  ->orWhere('school_id', $school->id);
        })->whereNotNull('user_id')->count();

        return view('school.new-dashboard', compact(
            'school', 
            'totalStudents', 
            'activeStudents', 
            'studentsWithAccounts'
        ));
    }

    /**
     * Display students listing
     */
    public function studentsIndex()
    {
        $school = $this->getCurrentSchool();
        
        if (!$school) {
            return redirect()->route('login')->with('error', 'School account not found.');
        }

        $students = Student::where(function($query) use ($school) {
            $query->where('created_by_school_id', $school->id)
                  ->orWhere('school_id', $school->id);
        })
        ->with(['programType', 'user'])
        ->orderBy('created_at', 'desc')
        ->paginate(15);

        $programTypes = ProgramType::where('status', 'active')->get();

        return view('school.new-students', compact('school', 'students', 'programTypes'));
    }

    /**
     * Store a new student
     */
    public function storeStudent(Request $request)
    {
        $school = $this->getCurrentSchool();
        
        if (!$school) {
            return response()->json(['error' => 'School account not found.'], 403);
        }

        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:students,email',
            'phone' => 'required|string|max:20',
            'program_type_id' => 'required|exists:program_types,id',
            'date_of_birth' => 'required|date',
            'city' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            DB::beginTransaction();

            // Calculate age
            $age = \Carbon\Carbon::parse($request->date_of_birth)->age;

            // Create user account for the student
            $user = User::create([
                'name' => $request->first_name . ' ' . $request->last_name,
                'email' => $request->email,
                'username' => strtolower($request->first_name . '.' . $request->last_name . rand(100, 999)),
                'password' => Hash::make('student123'),
                'user_type_id' => 3, // Student user type
            ]);

            // Create student record
            $student = Student::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'full_name' => $request->first_name . ' ' . $request->last_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'program_type_id' => $request->program_type_id,
                'date_of_birth' => $request->date_of_birth,
                'age' => $age,
                'city' => $request->city,
                'school_id' => $school->id,
                'created_by_school_id' => $school->id,
                'is_school_managed' => true,
                'admin_can_manage' => $school->admin_can_manage ?? false,
                'user_id' => $user->id,
                'status' => 'active',
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Student created successfully!',
                'student' => $student->load('programType', 'user')
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Failed to create student: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Get student details
     */
    public function getStudent($id)
    {
        $school = $this->getCurrentSchool();
        
        if (!$school) {
            return response()->json(['error' => 'School account not found.'], 403);
        }

        $student = Student::where(function($query) use ($school) {
            $query->where('created_by_school_id', $school->id)
                  ->orWhere('school_id', $school->id);
        })->with(['programType', 'user'])->find($id);

        if (!$student) {
            return response()->json(['error' => 'Student not found.'], 404);
        }

        return response()->json(['student' => $student]);
    }

    /**
     * Update student
     */
    public function updateStudent(Request $request, $id)
    {
        $school = $this->getCurrentSchool();
        
        if (!$school) {
            return response()->json(['error' => 'School account not found.'], 403);
        }

        $student = Student::where(function($query) use ($school) {
            $query->where('created_by_school_id', $school->id)
                  ->orWhere('school_id', $school->id);
        })->find($id);

        if (!$student) {
            return response()->json(['error' => 'Student not found.'], 404);
        }

        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:students,email,' . $id,
            'phone' => 'required|string|max:20',
            'program_type_id' => 'required|exists:program_types,id',
            'date_of_birth' => 'required|date',
            'city' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            DB::beginTransaction();

            // Calculate age
            $age = \Carbon\Carbon::parse($request->date_of_birth)->age;

            // Update student
            $student->update([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'full_name' => $request->first_name . ' ' . $request->last_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'program_type_id' => $request->program_type_id,
                'date_of_birth' => $request->date_of_birth,
                'age' => $age,
                'city' => $request->city,
            ]);

            // Update associated user if exists
            if ($student->user) {
                $student->user->update([
                    'name' => $request->first_name . ' ' . $request->last_name,
                    'email' => $request->email,
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Student updated successfully!',
                'student' => $student->load('programType', 'user')
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Failed to update student: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Delete student
     */
    public function deleteStudent($id)
    {
        $school = $this->getCurrentSchool();
        
        if (!$school) {
            return response()->json(['error' => 'School account not found.'], 403);
        }

        $student = Student::where(function($query) use ($school) {
            $query->where('created_by_school_id', $school->id)
                  ->orWhere('school_id', $school->id);
        })->find($id);

        if (!$student) {
            return response()->json(['error' => 'Student not found.'], 404);
        }

        try {
            DB::beginTransaction();

            // Delete associated user if exists
            if ($student->user) {
                $student->user->delete();
            }

            // Delete student
            $student->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Student deleted successfully!'
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Failed to delete student: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Toggle admin permission for the school
     */
    public function toggleAdminPermission(Request $request)
    {
        $school = $this->getCurrentSchool();
        
        if (!$school) {
            return response()->json(['error' => 'School account not found.'], 403);
        }

        try {
            $school->admin_can_manage = !$school->admin_can_manage;
            $school->save();

            // Update all school's students
            Student::where(function($query) use ($school) {
                $query->where('created_by_school_id', $school->id)
                      ->orWhere('school_id', $school->id);
            })->update(['admin_can_manage' => $school->admin_can_manage]);

            return response()->json([
                'success' => true,
                'admin_can_manage' => $school->admin_can_manage,
                'message' => $school->admin_can_manage 
                    ? 'Admin access granted successfully!' 
                    : 'Admin access revoked successfully!'
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to toggle admin permission: ' . $e->getMessage()], 500);
        }
    }
}
