<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\School;
use App\Models\ProgramType;
use Illuminate\Support\Facades\DB;
use App\Imports\StudentsImport;
use App\Exports\StudentsExport;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

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
        
        // Start with a base query
        $query = Student::query();
        
        // Apply filters if provided
        if ($schoolId) {
            $query->where('school_id', $schoolId);
        }
        
        if ($programTypeId) {
            $query->where('program_type_id', $programTypeId);
        }
        
        // Get the filtered students with pagination and eager load relationships
        $students = $query->with(['school', 'programType'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        // Get all schools and program types for the filter dropdowns
        $schools = School::orderBy('name')->get();
        $programTypes = ProgramType::where('is_active', true)->orderBy('name')->get();
        
        return view('admin.students.index', compact('students', 'schools', 'programTypes', 'schoolId', 'programTypeId'));
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
        
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'age' => 'required|integer|min:1|max:100',
            'email' => 'nullable|email|max:255|unique:students,email,' . $student->id,
            'phone' => 'nullable|string|max:20',
            'parent_contact' => 'required|string|max:20',
            'city' => 'required|string|max:255',
            'school_id' => 'required|exists:schools,id',
            'program_type_id' => 'required|exists:program_types,id',
            'status' => 'required|string|in:active,inactive,completed',
        ]);
        
        $student->update($validated);
        
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
                return redirect()->route('admin.students.import.process');
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
        
        return redirect()->route('admin.students.import.process');
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
}
