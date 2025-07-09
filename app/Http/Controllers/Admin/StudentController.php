<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\School;
use App\Models\ProgramType;
use App\Models\User;
use App\Models\UserType;
use Illuminate\Support\Facades\DB;
use App\Imports\StudentsImport;
use App\Exports\StudentsExport;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Notifications\StudentApprovalNotification;

class StudentController extends Controller
{
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
        
        // Start with a base query
        $query = Student::query();
        
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
        
        return view('admin.students.index', compact('students', 'schools', 'programTypes', 'schoolId', 'programTypeId', 'status'));
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
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'age' => 'required|integer|min:1|max:100',
            'email' => 'nullable|email|max:255|unique:students,email',
            'phone' => 'nullable|string|max:20',
            'parent_contact' => 'required|string|max:20',
            'city' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'class' => 'required|string|max:255',
            'school_id' => 'required|exists:schools,id',
            'program_type_id' => 'required|exists:program_types,id',
        ]);
        
        // Generate a unique registration number
        $registrationNumber = 'YEG' . date('y') . str_pad(Student::count() + 1, 5, '0', STR_PAD_LEFT);
        
        // Create the student data array with all required fields
        $studentData = [
            'full_name' => $request->full_name,
            'age' => $request->age,
            'email' => $request->email,
            'phone' => $request->phone,
            'parent_contact' => $request->parent_contact,
            'city' => $request->city,
            'date_of_birth' => $request->date_of_birth,
            'class' => $request->class,
            'school_id' => $request->school_id,
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
            'full_name' => 'required|string|max:255',
            'age' => 'required|integer|min:1|max:100',
            'email' => 'nullable|email|max:255|unique:students,email,' . $student->id,
            'phone' => 'nullable|string|max:20',
            'parent_contact' => 'required|string|max:20',
            'city' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'class' => 'required|string|max:255',
            'school_id' => 'required|exists:schools,id',
            'program_type_id' => 'required|exists:program_types,id',
            'status' => 'required|string|in:active,inactive,completed',
        ]);
        
        // Check if status is being changed to 'active' (approved)
        $isBeingApproved = ($originalStatus != 'active' && $request->status == 'active');
        
        $student->update($validated);
        
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
        $student = Student::findOrFail($id);
        
        // Check if the student has any related payments
        if ($student->payments->count() > 0) {
            return redirect()->route('admin.students.show', $student->id)
                ->with('error', 'Cannot delete student with payment records. Consider marking them as inactive instead.');
        }
        
        $student->delete();
        
        return redirect()->route('admin.students.index')
            ->with('success', 'Student deleted successfully.');
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
            'student_ids' => 'required|array',
            'student_ids.*' => 'exists:students,id'
        ]);
        
        $studentIds = $request->student_ids;
        $successCount = 0;
        $failCount = 0;
        
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
                
                $student->delete();
                $successCount++;
            }
            
            // Commit the transaction
            DB::commit();
            
            if ($failCount > 0) {
                $message = $successCount . ' student(s) deleted successfully. ' . $failCount . ' student(s) could not be deleted due to existing payment records.';
                return redirect()->route('admin.students.index')->with('warning', $message);
            } else {
                return redirect()->route('admin.students.index')->with('success', $successCount . ' student(s) deleted successfully.');
            }
        } catch (\Exception $e) {
            // Rollback the transaction in case of error
            DB::rollBack();
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
            
            // Generate a random password
            $password = 'YEG' . rand(1000, 9999);
            
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
            
            // Generate a random password
            $password = Str::random(8);
            
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
}
