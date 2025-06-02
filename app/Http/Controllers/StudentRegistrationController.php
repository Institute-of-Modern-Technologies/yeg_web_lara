<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ProgramType;
use App\Models\School;
use App\Models\Student;
use App\Models\Payment;
use App\Models\Fee;
use Illuminate\Support\Str;

class StudentRegistrationController extends Controller
{
    /**
     * Show the first step of student registration (program type selection)
     */
    public function showStep1()
    {
        // Fetch all active program types from the database
        $programTypes = ProgramType::all();
        return view('student.registration.step1', compact('programTypes'));
    }
    
    /**
     * Process step 1 and show step 2
     */
    public function processStep1(Request $request)
    {
        $validated = $request->validate([
            'program_type_id' => 'required|exists:program_types,id',
        ]);
        
        $programType = ProgramType::findOrFail($request->program_type_id);
        
        // Get all schools from the database (approved only)
        $schools = School::all();
        
        // Store data in session
        session(['registration.program_type_id' => $request->program_type_id]);
        session(['registration.program_type_name' => $programType->name]);
        
        // Determine which view to show based on program type name
        $programName = strtolower($programType->name);
        
        if ($programName === 'in school') {
            return view('student.registration.step2_inschool', compact('schools', 'programType'));
        }
        
        // For other program types, go to different school selection
        return view('student.registration.step2_other', compact('schools', 'programType'));
    }
    
    /**
     * Process step 2 for In School programs
     */
    public function processStep2InSchool(Request $request)
    {
        $validated = $request->validate([
            'school_id' => 'required|exists:schools,id',
        ]);
        
        // Store data in session
        session(['registration.school_id' => $request->school_id]);
        
        // Get the school information
        $school = School::find($request->school_id);
        
        return view('student.registration.step3_inschool', compact('school'));
    }
    
    /**
     * Process step 2 for other programs
     */
    public function processStep2Other(Request $request)
    {
        $validated = $request->validate([
            'school_id' => 'nullable|exists:schools,id',
        ]);
        
        // Store data in session
        session(['registration.school_id' => $request->school_id]);
        
        // Calculate fee based on program type and school
        $programTypeId = session('registration.program_type_id');
        $schoolId = $request->school_id;
        
        // Try to find a specific fee for this program type and school
        $fee = Fee::where('program_type_id', $programTypeId)
            ->where(function($query) use ($schoolId) {
                $query->where('school_id', $schoolId)
                      ->orWhereNull('school_id');
            })
            ->where('is_active', 1)
            ->orderBy('school_id', 'desc') // Prioritize school-specific fees
            ->first();
            
        // If no fee found, use a default amount
        $feeAmount = $fee ? $fee->amount : 500; // Default fee of 500 GHC
        
        // Store fee amount in session
        session(['registration.fee_amount' => $feeAmount]);
        
        return view('student.registration.step3_payment', compact('feeAmount'));
    }
    
    /**
     * Process step 3 for In School programs
     */
    public function processStep3InSchool(Request $request)
    {
        $validated = $request->validate([
            'payer_type' => 'required|in:self,school',
        ]);
        
        // Store data in session
        session(['registration.payer_type' => $request->payer_type]);
        
        // Get program type and school for fee calculation
        $programTypeId = session('registration.program_type_id');
        $schoolId = session('registration.school_id');
        
        // Try to find a specific fee for this program type and school
        $fee = Fee::where('program_type_id', $programTypeId)
            ->where(function($query) use ($schoolId) {
                $query->where('school_id', $schoolId)
                      ->orWhereNull('school_id');
            })
            ->where('is_active', 1)
            ->orderBy('school_id', 'desc') // Prioritize school-specific fees
            ->first();
            
        // If no fee found, use a default amount
        $feeAmount = $fee ? $fee->amount : 0; // Use dynamic fee from database, or 0 as default
        
        // Check if the school is a partner school
        $school = School::find($schoolId);
        if ($school && $school->is_partner && $fee) {
            $feeAmount = $fee->amount - $fee->partner_discount;
        }
        
        // Store fee amount in session
        session(['registration.fee_amount' => $feeAmount]);
        
        if ($request->payer_type === 'self') {
            return view('student.registration.step3_payment', compact('feeAmount'));
        }
        
        // If school is paying, skip payment
        return redirect()->route('student.registration.details');
    }
    
    /**
     * Process payment and show student details form
     */
    public function processPayment(Request $request)
    {
        $validated = $request->validate([
            'payment_reference' => 'required|string|max:255',
        ]);
        
        // Store payment reference in session
        session(['registration.payment_reference' => $request->payment_reference]);
        
        return redirect()->route('student.registration.details');
    }
    
    /**
     * Show student details form
     */
    public function showDetailsForm()
    {
        $programTypeId = session('registration.program_type_id');
        $schoolId = session('registration.school_id');
        
        $programType = ProgramType::findOrFail($programTypeId);
        $school = $schoolId ? School::findOrFail($schoolId) : null;
        
        return view('student.registration.details', compact('programType', 'school'));
    }
    
    /**
     * Process student details and complete registration
     */
    /**
     * Generate a unique registration number for students
     */
    private function generateRegistrationNumber()
    {
        $year = date('Y');
        $month = date('m');
        $prefix = 'YEG'.$year.$month;
        
        // Get the count of students registered this month/year
        $count = Student::where('registration_number', 'like', $prefix.'%')->count();
        $nextNumber = $count + 1;
        
        // Format with leading zeros (e.g., YEG202506001)
        return $prefix.str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
    }
    
    public function processDetails(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:male,female,other',
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:255',
            'region' => 'required|string|max:255',
        ]);
        
        // Generate a unique registration number for the student
        $registrationNumber = $this->generateRegistrationNumber();
        
        // Create a student object we'll use throughout the method
        $studentObj = (object)[
            'registration_number' => $registrationNumber,
            'full_name' => $request->first_name . ' ' . $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
        ];
        
        try {
            // Create a new student record using the fields that exist in the database
            DB::table('students')->insert([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'full_name' => $request->first_name . ' ' . $request->last_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'date_of_birth' => $request->date_of_birth,
                'gender' => $request->gender,
                'address' => $request->address,
                'city' => $request->city,
                'region' => $request->region,
                'program_type_id' => session('registration.program_type_id'),
                'school_id' => session('registration.school_id') ?? null,
                'registration_number' => $registrationNumber,
                'status' => 'active',
                'payer_type' => session('registration.payer_type') ?? 'individual',
                'payment_status' => session('registration.payment_status') ?? 'pending',
                'age' => date_diff(date_create($request->date_of_birth), date_create('today'))->y,
                'created_at' => now(),
                'updated_at' => now()
            ]);
            
            // Get the student ID for further processing
            $studentId = DB::getPdo()->lastInsertId();
            $studentObj->id = $studentId;
            
            // Get program type name for the success page
            if (session('registration.program_type_id')) {
                $programType = ProgramType::find(session('registration.program_type_id'));
                if ($programType) {
                    session(['registration.program_type_name' => $programType->name]);
                }
            }
            
            // Get school name for the success page
            if (session('registration.school_id')) {
                $school = School::find(session('registration.school_id'));
                if ($school) {
                    session(['registration.school_name' => $school->name]);
                }
            }
            
            // Handle payment creation based on payment type
            if (session('registration.payer_type') === 'self') {
                Payment::create([
                    'student_id' => $studentId,
                    'amount' => session('registration.fee_amount'),
                    'payment_method' => 'mobile_money',
                    'reference_number' => session('registration.payment_reference') ?? 'PAYMENT-' . strtoupper(Str::random(8)),
                    'status' => 'completed'
                ]);
            }
            // Record school payment (to be paid later)
            elseif (session('registration.payer_type') === 'school') {
                Payment::create([
                    'student_id' => $studentId,
                    'amount' => session('registration.fee_amount'),
                    'payment_method' => 'school_payment',
                    'reference_number' => 'SCHOOL-' . strtoupper(Str::random(8)),
                    'status' => 'pending'
                ]);
            }
            
        } catch (\Exception $e) {
            // Log the error and return a friendly message
            \Log::error('Student registration error: ' . $e->getMessage());
            
            return redirect()->back()->with('error', 'There was a problem with your registration. Please try again or contact support. Error: ' . $e->getMessage());
        }
        
        // Store student info in session for success page
        session(['registration.student' => $studentObj]);
        session(['registration.registration_number' => $registrationNumber]);
        
        // Clear other registration session data
        session()->forget([
            'registration.program_type_id',
            'registration.program_type_name',
            'registration.school_id',
            'registration.payer_type',
            'registration.fee_amount',
            'registration.payment_reference',
        ]);
        
        return redirect()->route('student.registration.success');
    }
}
