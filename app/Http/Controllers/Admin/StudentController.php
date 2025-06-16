<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\School;
use App\Models\ProgramType;

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
}
