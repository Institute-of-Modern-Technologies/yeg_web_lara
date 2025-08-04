<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\School;
use App\Models\ProgramType;
use App\Models\User;
use App\Models\UserType;
use App\Models\Stage;
use Illuminate\Support\Facades\DB;
use App\Imports\StudentsImport;
use App\Exports\StudentsExport;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Notifications\StudentApprovalNotification;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Response;

class StudentController extends Controller
{
    /**
     * Get stage information for a student (for AJAX requests)
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStageInfo($id)
    {
        try {
            $student = Student::with('stage')->findOrFail($id);
            
            return Response::json([
                'success' => true,
                'current_stage_id' => $student->stage_id,
                'current_stage_order' => optional($student->stage)->order ?? 0,
                'current_stage_name' => optional($student->stage)->name ?? 'No Stage'
            ]);
        } catch (\Exception $e) {
            return Response::json([
                'success' => false,
                'message' => 'Error retrieving student stage information'
            ], 500);
        }
    }
    /**
     * Display a listing of students with optional filtering.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Get filter parameters
        $schoolId = $request->input('school_id');
        $programTypeId = $request->input('program_type_id');
        $status = $request->input('status', '');
        $search = $request->input('search', '');
        
        // Start with a base query
        $query = Student::query();
        
        // Apply search if provided
        if ($search) {
            $searchTerm = '%' . $search . '%';
            $query->where(function($q) use ($searchTerm) {
                $q->where('registration_number', 'like', $searchTerm)
                  ->orWhere('full_name', 'like', $searchTerm)
                  ->orWhere('email', 'like', $searchTerm)
                  ->orWhere('phone', 'like', $searchTerm);
            });
        }
        
        // Apply filters if provided
        if ($schoolId) {
            $query->where('school_id', $schoolId);
        }
        
        if ($programTypeId) {
            $query->where('program_type_id', $programTypeId);
        }
        
        // Filter by status if provided
        if ($status) {
            $query->where('status', $status);
        }
        
        // Get the filtered students with pagination and eager load relationships
        $students = $query->with(['school', 'programType'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        // Get all schools and program types for the filter dropdowns
        $schools = School::orderBy('name')->get();
        $programTypes = ProgramType::where('is_active', true)->orderBy('name')->get();
        
        // Get all stages with their level information for modals
        $stages = Stage::select('id', 'name', 'level', 'order', 'slug')
            ->orderBy('order')
            ->get();
        
        return view('admin.students.index', compact('students', 'schools', 'programTypes', 'schoolId', 'programTypeId', 'status', 'stages'));
    }
    
    /**
     * Show details for a specific student.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $student = Student::with(['school', 'programType', 'payments'])->findOrFail($id);
        return view('admin.students.show', compact('student'));
    }

    /**
     * Show the form for editing the specified student.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    /**
     * Show the form for creating a new student.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $schools = School::where('status', 'approved')->orderBy('name')->get();
        $programTypes = ProgramType::where('is_active', true)->orderBy('name')->get();
        
        return view('admin.students.create', compact('schools', 'programTypes'));
    }
    
    /**
     * Store a newly created student in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate all other fields first
        $baseValidation = [
            'first_name' => 'required|string|max:127',
            'last_name' => 'required|string|max:127',
            'email' => 'nullable|email|max:255|unique:students,email',
            'phone' => 'nullable|string|max:20',
            'parent_contact' => 'required|string|max:20',
            'date_of_birth' => 'required|date',
            'gender' => 'required|string|in:male,female,other',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'region' => 'required|string|max:100',
            'class' => 'required|string|max:255',
            'program_type_id' => 'required|exists:program_types,id',
            'school_selection_method' => 'required|in:select,enter',
        ];
        
        $validated = $request->validate($baseValidation);
        
        // Combine first and last name into full_name
        $fullName = $request->first_name . ' ' . $request->last_name;
        
        // Calculate age from date of birth
        $birthDate = new \DateTime($request->date_of_birth);
        $today = new \DateTime('today');
        $age = $birthDate->diff($today)->y;
        
        // Generate a unique registration number
        $registrationNumber = 'YEG' . date('y') . str_pad(Student::count() + 1, 5, '0', STR_PAD_LEFT);
        
        // Handle school selection - returns [school_id, school_name]
        $schoolData = $this->handleSchoolSelection($request);
        
        // Create the student data array with all required fields
        $studentData = [
            'full_name' => $fullName,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'age' => $age,
            'email' => $request->email,
            'phone' => $request->phone,
            'parent_contact' => $request->parent_contact,
            'gender' => $request->gender,
            'address' => $request->address,
            'city' => $request->city,
            'region' => $request->region,
            'date_of_birth' => $request->date_of_birth,
            'class' => $request->class,
            'school_id' => $schoolData['school_id'],
            'school_name' => $schoolData['school_name'],
            'program_type_id' => $request->program_type_id,
            'registration_number' => $registrationNumber,
            'status' => 'active'
        ];
        
        // Create the student
        $student = Student::create($studentData);
        
        return redirect()->route('admin.students.show', $student->id)
            ->with('success', 'Student added successfully with registration number: ' . $registrationNumber);
    }
    
    public function edit($id)
    {
        $student = Student::findOrFail($id);
        $schools = School::orderBy('name')->get();
        $programTypes = ProgramType::where('is_active', true)->orderBy('name')->get();
        
        return view('admin.students.edit', compact('student', 'schools', 'programTypes'));
    }

    /**
     * Helper method to handle school selection from the form
     * This supports the tab-based approach (Select from List/Enter Manually)
     * Manually entered school names are stored directly with the student record
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return array  School data [id, name]
     */
    private function handleSchoolSelection(Request $request)
    {
        // Check which tab was selected using the hidden field
        $selectionMethod = $request->input('school_selection_method', 'select');
        
        if ($selectionMethod === 'select') {
            // Validate only school_id when "Select from List" tab is active
            if (!$request->filled('school_id')) {
                throw ValidationException::withMessages(['school_id' => ['Please select a school from the list.']]);
            }
            
            $request->validate([
                'school_id' => 'required|exists:schools,id',
            ]);
            
            // Return school_id with null school_name
            return [
                'school_id' => $request->school_id,
                'school_name' => null
            ];
        } else { // $selectionMethod === 'enter'
            // Validate only school_name when "Enter Manually" tab is active
            if (!$request->filled('school_name')) {
                throw ValidationException::withMessages(['school_name' => ['Please enter a school name.']]);
            }
            
            $request->validate([
                'school_name' => 'required|string|max:255',
            ]);
            
            // Return null school_id with the manually entered school name
            return [
                'school_id' => null,
                'school_name' => $request->school_name
            ];
        }
    }

    /**
     * Update the specified student in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $student = Student::findOrFail($id);
        $originalStatus = $student->status;
        
        $validated = $request->validate([
            'first_name' => 'required|string|max:127',
            'last_name' => 'required|string|max:127',
            'email' => 'nullable|email|max:255|unique:students,email,' . $student->id,
            'phone' => 'nullable|string|max:20',
            'parent_contact' => 'required|string|max:20',
            'gender' => 'required|string|in:male,female,other',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'region' => 'required|string|max:100',
            'date_of_birth' => 'required|date',
            'class' => 'required|string|max:255',
            'school_selection_method' => 'required|in:select,enter',
            'program_type_id' => 'required|exists:program_types,id',
            'status' => 'required|string|in:active,inactive,completed',
        ]);
        
        // Combine first and last name into full_name
        $fullName = $request->first_name . ' ' . $request->last_name;
        
        // Calculate age from date of birth
        $birthDate = new \DateTime($request->date_of_birth);
        $today = new \DateTime('today');
        $age = $birthDate->diff($today)->y;
        
        // Check if status is being changed to 'active' (approved)
        $isBeingApproved = ($originalStatus != 'active' && $request->status == 'active');
        
        // Handle school selection - returns [school_id, school_name]
        $schoolData = $this->handleSchoolSelection($request);
        
        // Update student with all fields
        $student->update([
            'full_name' => $fullName,
            'email' => $request->email,
            'phone' => $request->phone,
            'parent_contact' => $request->parent_contact,
            'gender' => $request->gender,
            'address' => $request->address,
            'city' => $request->city,
            'region' => $request->region,
            'date_of_birth' => $request->date_of_birth,
            'age' => $age,
            'class' => $request->class,
            'school_id' => $schoolData['school_id'],
            'school_name' => $schoolData['school_name'],
            'program_type_id' => $request->program_type_id,
            'status' => $request->status,
        ]);
        
        // If student is being approved, create a user account if it doesn't exist
        if ($isBeingApproved) {
            $this->createStudentUser($student);
        }
        
        // If student is being approved, send approval notification with credentials
        if ($isBeingApproved && $student->email) {
            $this->sendApprovalNotification($student);
            return redirect()->route('admin.students.show', $student->id)
                ->with('success', 'Student approved successfully. Login credentials have been sent to their email.');
        }
        
        return redirect()->route('admin.students.show', $student->id)
            ->with('success', 'Student information updated successfully.');
    }

    /**
     * Remove the specified student from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $student = Student::with('payments')->findOrFail($id);
        
        // Check if the student has any related payments
        if ($student->payments->count() > 0) {
            return redirect()->route('admin.students.show', $student->id)
                ->with('error', 'Cannot delete student with payment records. Consider marking them as inactive instead.');
        }
        
        // Start transaction
        DB::beginTransaction();
        
        try {
            $userDeleted = false;
            
            // Delete associated user account if exists
            if (!empty($student->email)) {
                $user = User::where('email', $student->email)->first();
                if ($user) {
                    // Force delete the user to ensure it's completely removed
                    $userId = $user->id;
                    
                    // Raw delete to bypass any potential issues
                    DB::table('users')->where('id', $userId)->delete();
                    
                    $userDeleted = true;
                    Log::info("Individual delete: Removed user account for student ID: {$student->id}, email: {$student->email}, user ID: {$userId}");
                }
            }
            
            // Delete the student
            $student->delete();
            
            // Commit the transaction
            DB::commit();
            
            $message = 'Student deleted successfully';
            if ($userDeleted) {
                $message .= ' along with user account';
            }
            
            return redirect()->route('admin.students.index')
                ->with('success', $message . '.');
                
        } catch (\Exception $e) {
            // Rollback the transaction
            DB::rollBack();
            
            Log::error('Student delete error: ' . $e->getMessage());
            return redirect()->route('admin.students.index')
                ->with('error', 'Failed to delete student: ' . $e->getMessage());
        }
    }
    
    /**
     * Remove multiple students from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'selected_students' => 'required|array',
            'selected_students.*' => 'exists:students,id'
        ]);
        
        $studentIds = $request->selected_students;
        $successCount = 0;
        $failCount = 0;
        $deletedUserCount = 0;
        
        // Begin transaction
        DB::beginTransaction();
        
        try {
            foreach ($studentIds as $id) {
                $student = Student::with('payments')->find($id);
                
                if (!$student) {
                    continue;
                }
                
                // Skip students with payment records
                if ($student->payments->count() > 0) {
                    $failCount++;
                    continue;
                }
                
                // Delete associated user account if exists
                if (!empty($student->email)) {
                    try {
                        $user = User::where('email', $student->email)->first();
                        if ($user) {
                            $userId = $user->id;
                            
                            // Use raw query to bypass any potential issues
                            DB::table('users')->where('id', $userId)->delete();
                            
                            $deletedUserCount++;
                            Log::info("Bulk delete: Removed user account for student ID: {$student->id}, email: {$student->email}, user ID: {$userId}");
                        } else {
                            Log::info("No user account found for student ID: {$student->id}, email: {$student->email}");
                        }
                    } catch (\Exception $e) {
                        Log::error("Failed to delete user account for student {$student->id}: {$e->getMessage()}");
                    }                    
                }
                
                $student->delete();
                $successCount++;
            }
            
            // Commit the transaction
            DB::commit();
            
            if ($failCount > 0) {
                $message = $successCount . ' student(s) deleted successfully (' . $deletedUserCount . ' user accounts). ' . $failCount . ' student(s) could not be deleted due to existing payment records.';
                return redirect()->route('admin.students.index')->with('warning', $message);
            } else {
                $message = $successCount . ' student(s) deleted successfully';  
                if ($deletedUserCount > 0) {
                    $message .= ' along with ' . $deletedUserCount . ' user account(s)';
                }
                $message .= '.';
                return redirect()->route('admin.students.index')->with('success', $message);
            }
        } catch (\Exception $e) {
            // Rollback the transaction in case of error
            DB::rollBack();
            Log::error('Bulk delete error: ' . $e->getMessage());
            return redirect()->route('admin.students.index')->with('error', 'An error occurred while deleting students: ' . $e->getMessage());
        }
    }
    
    /**
     * Show the form for importing students.
     *
     * @return \Illuminate\Http\Response
     */
    public function showImportForm()
    {
        $schools = School::orderBy('name')->get();
        $programTypes = ProgramType::where('is_active', true)->orderBy('name')->get();
        
        return view('admin.students.import', compact('schools', 'programTypes'));
    }
    
    /**
     * Validate the uploaded CSV file and show school matching form.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function validateImport(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:2048',
        ]);
        
        // Store the uploaded file
        $path = $request->file('csv_file')->store('temp');
        $fullPath = Storage::path($path);
        
        // Create column mapping if provided
        $columnMap = [];
        $mappingFields = ['full_name', 'age', 'phone', 'email', 'parent_contact', 
                        'city', 'school_id', 'program_type_id', 'status'];
        
        foreach ($mappingFields as $field) {
            if ($request->has('map_' . $field) && !empty($request->input('map_' . $field))) {
                $columnMap[$request->input('map_' . $field)] = $field;
            }
        }
        
        try {
            // Extract unique school names from CSV
            $import = new StudentsImport();
            $schoolsData = $import->extractUniqueSchools($fullPath, $columnMap);
            
            // Store necessary data in session for next step
            session([
                'import_file_path' => $path,
                'import_column_map' => $columnMap,
                'import_schools' => $schoolsData['schools'],
                'import_csv_data' => $schoolsData['preview']
            ]);
            
            // If no schools to match, go directly to final import
            if (empty($schoolsData['schools'])) {
                // Create a view with auto-submitting form to make a proper POST request
                return view('admin.students.auto-process-import', [
                    'route' => route('admin.students.import.process')
                ]);
            }
            
            // Get all available schools for dropdown selection
            $availableSchools = School::orderBy('name')->get();
            
            return view('admin.students.match-schools', [
                'csvSchools' => $schoolsData['schools'],
                'availableSchools' => $availableSchools,
                'csvPreview' => $schoolsData['preview']
            ]);
            
        } catch (\Exception $e) {
            // Clean up the temp file
            Storage::delete($path);
            
            // Error message
            return redirect()->route('admin.students.import')
                ->with('error', 'CSV validation failed: ' . $e->getMessage());
        }
    }
    
    /**
     * Process school matching selections.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function matchSchools(Request $request)
    {
        // Validate
        $request->validate([
            'school_mapping' => 'required|array',
        ]);
        
        // Store school mapping in session
        session(['import_school_mapping' => $request->school_mapping]);
        
        // Use auto-submission view to make a proper POST request
        return view('admin.students.auto-process-import', [
            'route' => route('admin.students.import.process')
        ]);
    }
    
    /**
     * Process the final import after school matching.
     *
     * @return \Illuminate\Http\Response
     */
    public function processImport()
    {
        // Retrieve session data
        $path = session('import_file_path');
        $columnMap = session('import_column_map');
        $schoolMapping = session('import_school_mapping', []);
        
        if (empty($path)) {
            return redirect()->route('admin.students.import')
                ->with('error', 'Import session expired. Please upload the CSV file again.');
        }
        
        $fullPath = Storage::path($path);
        
        try {
            // Process the final import with school mapping
            $import = new StudentsImport();
            $result = $import->import($fullPath, $columnMap, $schoolMapping);
            
            // Clean up the temp file and session data
            Storage::delete($path);
            session()->forget(['import_file_path', 'import_column_map', 'import_schools', 
                            'import_school_mapping', 'import_csv_data']);
            
            // Success message with details
            if ($result['skipped'] > 0) {
                $message = $result['processed'] . ' students imported successfully. ' . $result['skipped'] . ' records were skipped due to errors.';
                
                if (!empty($result['warnings'])) {
                    session(['import_warnings' => $result['warnings']]);
                }
                
                session(['import_errors' => $result['errors']]);
                return redirect()->route('admin.students.index')->with('warning', $message);
            } else {
                return redirect()->route('admin.students.index')
                    ->with('success', $result['processed'] . ' students imported successfully!');
            }
            
        } catch (\Exception $e) {
            // Clean up the temp file
            Storage::delete($path);
            
            // Error message
            return redirect()->route('admin.students.import')
                ->with('error', 'Import failed: ' . $e->getMessage());
        }
    }
    
    /**
     * Export students as CSV.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function export(Request $request)
    {
        // Get filter values
        $filters = [
            'school_id' => $request->input('school_id'),
            'program_type_id' => $request->input('program_type_id'),
            'status' => $request->input('status')
        ];
        
        try {
            $export = new StudentsExport();
            $csv = $export->export($filters);
            
            $filename = 'students_export_' . date('Y-m-d_His') . '.csv';
            
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
                'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
                'Pragma' => 'public',
                'Expires' => '0'
            ];
            
            return response($csv, 200, $headers);
            
        } catch (\Exception $e) {
            Log::error('Student export failed: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->route('admin.students.index')
                ->with('error', 'Export failed: ' . $e->getMessage());
        }
    }
    
    /**
     * Send approval notification with login credentials to student
     *
     * @param  \App\Models\Student  $student
     * @return void
     */
    protected function sendApprovalNotification(Student $student)
    {
        try {
            // Get student user type
            $studentUserType = UserType::where('name', 'student')->first();
            
            if (!$studentUserType) {
                Log::error('Could not find student user type when approving student');
                return;
            }
            
            // Check if user already exists with this email
            $existingUser = User::where('email', $student->email)->first();
            
            if ($existingUser) {
                // User already exists, send notification with their username
                $student->notify(new StudentApprovalNotification($student, $existingUser->username, 'Your existing password'));
                return;
            }
            
            // Create a username from the student's name
            $baseUsername = Str::slug(explode(' ', $student->full_name)[0]);
            $username = $baseUsername;
            $counter = 1;
            
            // Make sure the username is unique
            while (User::where('username', $username)->exists()) {
                $username = $baseUsername . $counter++;
            }
            
            // Use default password for all student accounts
            $password = 'student123';
            
            // Create the user
            $user = User::create([
                'name' => $student->full_name,
                'email' => $student->email,
                'username' => $username,
                'password' => Hash::make($password),
                'user_type_id' => $studentUserType->id
            ]);
            
            // Send notification with login credentials
            $student->notify(new StudentApprovalNotification($student, $username, $password));
            
            Log::info('Approval notification sent to student: ' . $student->id);
        } catch (\Exception $e) {
            Log::error('Failed to send approval notification: ' . $e->getMessage());
        }
    }
    
    /**
     * Approve a pending student
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function approveStudent($id)
    {
        try {
            $student = Student::findOrFail($id);
            
            // Check if student is already active
            if ($student->status === 'active') {
                return redirect()->route('admin.students.show', $student->id)
                    ->with('info', 'Student is already approved and active.');
            }
            
            // Update status to active
            $student->status = 'active';
            $student->save();
            
            // Create user account for student if needed
            $this->createStudentUser($student);
            
            // Send notification if email exists
            if ($student->email) {
                $this->sendApprovalNotification($student);
            }
            
            return redirect()->route('admin.students.index', ['status' => 'pending'])
                ->with('success', 'Student has been approved successfully.');
                
        } catch (\Exception $e) {
            Log::error('Failed to approve student: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to approve student: ' . $e->getMessage());
        }
    }
    
    /**
     * Create a user account for a student
     * 
     * @param  \App\Models\Student  $student
     * @return \App\Models\User|null
     */
    private function createStudentUser($student)
    {
        // Don't create user account if email is missing
        if (empty($student->email)) {
            return null;
        }
        
        // Check if user already exists with this email
        $existingUser = User::where('email', $student->email)->first();
        if ($existingUser) {
            return $existingUser;
        }
        
        try {
            // Get or create student user type
            $studentUserType = UserType::firstOrCreate(
                ['slug' => 'student'],
                ['name' => 'Student', 'description' => 'Regular student account']
            );
            
            // Create username from first name or full name
            $nameParts = explode(' ', $student->full_name);
            $firstName = $nameParts[0] ?? '';
            $baseUsername = strtolower($firstName ?: $student->full_name);
            $username = $baseUsername;
            $counter = 1;
            
            // Check if username exists
            while (User::where('username', $username)->exists()) {
                $username = $baseUsername . $counter++;
            }
            
            // Use default password for all student accounts
            $password = 'student123';
            
            // Create user account
            $user = User::create([
                'name' => $student->full_name,
                'email' => $student->email,
                'username' => $username,
                'password' => Hash::make($password),
                'user_type_id' => $studentUserType->id
            ]);
            
            // Store the plaintext password temporarily to include in notification
            $user->temp_password = $password;
            
            return $user;
            
        } catch (\Exception $e) {
            Log::error('Failed to create user for student: ' . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Promote a student to the next stage
     *
     * @param int $id Student ID
     * @param int $stage_id Stage ID
     * @return \Illuminate\Http\Response
     */
    public function promoteStage($id, Request $request)
    {
        $student = Student::findOrFail($id);
        $currentStage = $student->stage;
        
        if (!$currentStage) {
            // If student doesn't have a stage, assign them to the first stage
            $firstStage = Stage::where('status', 'active')
                ->orderBy('order')
                ->first();
                
            if (!$firstStage) {
                $message = 'No active stages found in the system.';
                
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => $message
                    ]);
                }
                
                Session::flash('error', $message);
                return redirect()->back();
            }
            
            // Set first stage as current stage
            $currentStage = $firstStage;
            $student->stage_id = $firstStage->id;
            $student->save();
        }
        
        // Check if stage_id was provided via form
        if ($request->has('stage_id')) {
            $nextStage = Stage::findOrFail($request->stage_id);
            
            // Update student stage
            $student->stage_id = $nextStage->id;
            $student->save();
            
            $message = 'Student has been promoted from "' . $currentStage->name . '" to "' . $nextStage->name . '"';
        } else {
            // Get next stage by order (fallback to original behavior)
            $nextStage = Stage::where('status', 'active')
                            ->where('order', '>', $currentStage->order)
                            ->orderBy('order')
                            ->first();
            
            if (!$nextStage) {
                $message = 'Student is already at the highest stage.';
                
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => $message
                    ]);
                }
                
                Session::flash('info', $message);
                return redirect()->back();
            }
            
            // Update student stage
            $student->stage_id = $nextStage->id;
            $student->save();
            
            $message = 'Student has been promoted from "' . $currentStage->name . '" to "' . $nextStage->name . '"';
        }
        
        // Handle response
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message
            ]);
        }
        
        // For non-AJAX requests, use the regular redirect flow
        Session::flash('success', $message);
        $activeTab = $request->input('active_tab', 'program');
        return redirect()->route('admin.students.show', $id)->with('active_tab', $activeTab);
    }
    
    /**
     * Mark a student to repeat the current stage or reassign to a specific stage
     *
     * @param int $id Student ID
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function repeatStage($id, Request $request)
    {
        $student = Student::findOrFail($id);
        $currentStage = $student->stage;
        
        // If student doesn't have a stage, assign them to the first stage
        if (!$currentStage) {
            $firstStage = Stage::where('status', 'active')
                ->orderBy('order')
                ->first();
                
            if (!$firstStage) {
                $message = 'No active stages found in the system.';
                
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => $message
                    ]);
                }
                
                Session::flash('error', $message);
                return redirect()->back();
            }
            
            // Set first stage as current stage
            $currentStage = $firstStage;
            $student->stage_id = $firstStage->id;
            $student->save();
        }
        
        // Check if a specific stage was selected for repeating
        if ($request->has('stage_id')) {
            $stage = Stage::findOrFail($request->stage_id);
            $student->stage_id = $stage->id;
            $student->save();
            
            $message = "Student {$student->full_name} has been assigned to repeat {$stage->name}.";
        } else {
            // No stage selected, just mark current stage as repeating
            $message = "Student {$student->full_name} has been marked to repeat the current stage.";
        }
        
        // Handle response based on request type
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message
            ]);
        }
        
        // For non-AJAX requests, use the regular redirect flow
        session()->flash('success', $message);
        $activeTab = $request->input('active_tab', 'program');
        return redirect()->route('admin.students.show', $id)->with('active_tab', $activeTab);
    }
    
    /**
     * Manually assign a student to a specific stage
     *
     * @param int $id Student ID
     * @return \Illuminate\Http\Response
     */
    public function changeStage($id, Request $request)
    {
        $student = Student::findOrFail($id);
        
        // Validate request
        $validated = $request->validate([
            'stage_id' => 'required|exists:stages,id',
        ]);
        
        // Update student stage
        $student->stage_id = $validated['stage_id'];
        $student->save();
        
        // Get stage name for the message
        $stage = Stage::find($validated['stage_id']);
        
        session()->flash('success', "Student {$student->full_name} has been manually assigned to {$stage->name}.");
        
        return redirect()->back();
    }
}
