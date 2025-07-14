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
use App\Models\User;
use App\Models\UserType;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

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
        // Validate based on which option was selected
        if ($request->has('school_name') && !empty($request->school_name)) {
            // Manual school entry option selected
            $validated = $request->validate([
                'school_name' => 'required|string|max:255',
            ]);
            
            // Create a new temporary school entry or find existing one with same name
            $school = School::firstOrCreate(
                ['name' => $request->school_name],
                [
                    'status' => 'pending',
                    'slug' => Str::slug($request->school_name),
                    'address' => 'Pending',
                    'contact_person' => 'Pending',
                    'email' => 'pending@example.com',
                    'phone' => '0000000000',
                ]
            );
            
            // Store data in session
            session(['registration.school_id' => $school->id]);
            session(['registration.manual_school_entry' => true]);
            
        } else {
            // School selection from dropdown
            $validated = $request->validate([
                'school_id' => 'required|exists:schools,id',
            ]);
            
            // Store data in session
            session(['registration.school_id' => $request->school_id]);
            session(['registration.manual_school_entry' => false]);
            
            // Get the school information
            $school = School::find($request->school_id);
        }
        
        return view('student.registration.step3_inschool', compact('school'));
    }
    
    /**
     * Process step 2 for other programs
     */
    public function processStep2Other(Request $request)
    {
        // Debug received data
        \Log::info('Starting processStep2Other with data:', [
            'all_input' => $request->all(),
            'school_selection' => $request->input('school_selection'),
            'school_id' => $request->input('school_id'),
            'debug_program_type' => $request->input('debug_program_type'), 
            'debug_timestamp' => $request->input('debug_timestamp'),
            'form_url' => $request->fullUrl(),
            'session_program_type' => session('registration.program_type_id')
        ]);

        try {
            // Get the program type ID from session
            $programTypeId = session('registration.program_type_id');
            
            // Debug important session data
            \Log::info('Session state before validation:', [
                'session_id' => session()->getId(),
                'program_type_id' => $programTypeId,
                'previous_school_id' => session('registration.school_id'),
                'previous_school_selection' => session('registration.school_selection')
            ]);
            
            if (!$programTypeId) {
                // If program type is missing, redirect to step 1 with an error message
                \Log::warning('Missing program_type_id in session, redirecting to step 1');
                return redirect('/students/register')
                    ->with('error', 'Please select a program type first. Session data appears to be lost.');
            }
            
            // Validate based on school selection
            $validationRules = [
                'school_selection' => 'required|in:not_yet,select_school',
            ];
            
            // Only require school_id if select_school is chosen
            if ($request->school_selection === 'select_school') {
                $validationRules['school_id'] = 'required|exists:schools,id';
                \Log::info('School selection is select_school, school_id validation added');
            }
            
            try {
                \Log::info('Running validation with rules:', $validationRules);
                $validated = $request->validate($validationRules);
                \Log::info('Validation passed');
            } catch (\Illuminate\Validation\ValidationException $ve) {
                \Log::warning('Validation failed:', ['errors' => $ve->errors()]);
                throw $ve; // Re-throw to be caught by the outer try-catch
            }
            
            // Store data in session based on selection
            $schoolId = ($request->school_selection === 'select_school') ? $request->school_id : null;
            session(['registration.school_id' => $schoolId]);
            session(['registration.school_selection' => $request->school_selection]);
            
            \Log::info('School data saved to session:', [
                'school_id' => $schoolId,
                'school_selection' => $request->school_selection
            ]);
            
            // Try to find a specific fee for this program type and school
            $fee = Fee::where('program_type_id', $programTypeId)
                ->where(function($query) use ($schoolId) {
                    $query->where('school_id', $schoolId)
                        ->orWhereNull('school_id');
                })
                ->where('is_active', 1)
                ->orderBy('school_id', 'desc') // Prioritize school-specific fees
                ->first();
                
            // If no fee found, log an error and don't set a fee amount
            if (!$fee) {
                \Log::error('No fee configuration found for program type: ' . $programTypeId . ' and school: ' . $schoolId);
                // Don't set a fee amount if no configuration is found
                $feeAmount = null;
            } else {
                $feeAmount = $fee->amount;
                
                // Check if the school is a partner school and apply discount if applicable
                if ($request->school_selection === 'select_school' && $schoolId) {
                    $school = School::find($schoolId);
                    if ($school && $school->is_partner && $fee->partner_discount > 0) {
                        $feeAmount = $fee->amount - $fee->partner_discount;
                        \Log::info('Applied partner school discount', [
                            'original_fee' => $fee->amount,
                            'discount' => $fee->partner_discount,
                            'final_fee' => $feeAmount
                        ]);
                    }
                }
            }
            
            \Log::info('Fee calculation:', [
                'fee_found' => (bool)$fee,
                'fee_amount' => $feeAmount,
                'fee_id' => $fee ? $fee->id : null
            ]);
            
            // Store fee amount in session only if it's not null
            if ($feeAmount !== null) {
                session(['registration.fee_amount' => $feeAmount]);
            } else {
                // Clear any previously set fee amount
                session()->forget('registration.fee_amount');
            }
            
            // Debug: Log final session values
            \Log::info('Final session state before redirecting to step3:', [
                'program_type_id' => session('registration.program_type_id'),
                'school_id' => session('registration.school_id'),
                'school_selection' => session('registration.school_selection'),
                'fee_amount' => session('registration.fee_amount')
            ]);
            
            \Log::info('Skipping payment page and going directly to details form');
            return redirect()->route('student.registration.details');
            
        } catch (\Exception $e) {
            \Log::error('Error in processStep2Other:', [
                'exception' => get_class($e),
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(), 
                'trace' => $e->getTraceAsString()
            ]);
            return back()->withInput()->with('error', 'An error occurred: ' . $e->getMessage());
        }
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
            
        // If no fee found, log an error and don't set a fee amount
        if (!$fee) {
            \Log::error('No fee configuration found for program type: ' . $programTypeId . ' and school: ' . $schoolId);
            // Don't set a fee amount if no configuration is found
            $feeAmount = null;
        } else {
            $feeAmount = $fee->amount;
            
            // Check if the school is a partner school and apply discount if applicable
            if ($request->school_selection === 'select_school' && $schoolId) {
                $school = School::find($schoolId);
                if ($school && $school->is_partner && $fee->partner_discount > 0) {
                    $feeAmount = $fee->amount - $fee->partner_discount;
                    \Log::info('Applied partner school discount', [
                        'original_fee' => $fee->amount,
                        'discount' => $fee->partner_discount,
                        'final_fee' => $feeAmount
                    ]);
                }
            }
        }
        
        // Store fee amount in session only if it's not null
        if ($feeAmount !== null) {
            session(['registration.fee_amount' => $feeAmount]);
        }
        
        // Skip payment page for now and go directly to details form
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
        // Ensure we have the program type selected
        if (!session('registration.program_type_id')) {
            return redirect()->route('student.registration.step1')
                ->with('error', 'Please select a program type first.');
        }
        
        // Get the program type from the session
        $programType = ProgramType::findOrFail(session('registration.program_type_id'));
        
        // Get all schools for the dropdown
        $schools = School::all();
        
        return view('student.registration.details', compact('schools', 'programType'));
    }
    
    /**
     * Process the student details form submission
     */
    public function processDetailsForm(Request $request)
    {
        try {
            // Check for required session data first
            if (!session()->has('registration.program_type_id')) {
                return redirect()->route('student.registration.step1')
                    ->with('error', 'Please select a program type first.');
            }
            
            // Log the incoming request data for debugging
            \Log::debug('Student registration form submission', [
                'request_data' => $request->all()
            ]);
            
            // Basic validation for non-conditional fields
            $baseValidation = [
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'nullable|email|max:255',
                'phone' => 'nullable|string|max:20',
                'parent_contact' => 'required|string|max:20',
                'date_of_birth' => 'required|date',
                'gender' => 'required|in:male,female,other',
                'address' => 'required|string|max:500',
                'city' => 'required|string|max:255',
                'region' => 'required|string|max:255',
                'class' => 'required|string|max:255',
                'school_selection' => 'required|in:existing,new',
            ];
            
            // Add conditional validation based on school selection
            if ($request->school_selection === 'existing') {
                $baseValidation['school_id'] = 'required|exists:schools,id';
            } else {
                $baseValidation['school_name'] = 'required|string|max:255';
            }
            
            $validated = $request->validate($baseValidation);
            
            // Handle school selection based on form data
            if ($request->school_selection === 'existing') {
                // Use selected school
                session(['registration.school_id' => $request->school_id]);
            } else {
                // Create or find school by name
                $school = School::firstOrCreate(
                    ['name' => $request->school_name],
                    [
                        'status' => 'pending',
                        'location' => $request->city ?? 'Pending', // Required field
                        'phone' => '0000000000', // Required field
                        'owner_name' => 'Pending', // Required field
                        'email' => 'pending@example.com',
                        'contact_person' => 'Pending',
                        'slug' => Str::slug($request->school_name),
                        'gps_coordinates' => null,
                        'avg_students' => null,
                        'logo' => null
                    ]
                );
                session(['registration.school_id' => $school->id]);
            }
            
            // Generate a unique registration number
            $registrationNumber = $this->generateRegistrationNumber();
            
            // Combine first and last name into full_name
            $fullName = $request->first_name . ' ' . $request->last_name;
            
            // Calculate age from date of birth
            $age = date_diff(date_create($request->date_of_birth), date_create('today'))->y;
            
            // Create student record
            $student = new Student();
            $student->first_name = $request->first_name;
            $student->last_name = $request->last_name;
            $student->full_name = $fullName;
            $student->email = $request->email;
            $student->phone = $request->phone;
            $student->parent_contact = $request->parent_contact;
            $student->date_of_birth = $request->date_of_birth;
            $student->age = $age;
            $student->gender = $request->gender;
            $student->address = $request->address;
            $student->city = $request->city;
            $student->region = $request->region;
            $student->class = $request->class;
            $student->registration_number = $registrationNumber;
            $student->status = 'pending';
            
            // Ensure we have a valid program_type_id
            $programTypeId = session('registration.program_type_id');
            if (!$programTypeId) {
                throw new \Exception('Program type not found in session.');
            }
            $student->program_type_id = $programTypeId;
            
            // Ensure we have a valid school_id
            $schoolId = session('registration.school_id');
            if (!$schoolId) {
                throw new \Exception('School ID not found in session.');
            }
            $student->school_id = $schoolId;
            
            // Log student object before save
            \Log::debug('Student object before save', [
                'student_data' => $student->toArray()
            ]);
            
            $student->save();
            
            // For further processing
            $studentId = $student->id;
            
            // Create comprehensive student object for the session
            $studentObj = (object)[
                'id' => $studentId,
                'registration_number' => $registrationNumber,
                'full_name' => $fullName,
                'email' => $request->email,
                'phone' => $request->phone,
                'parent_contact' => $request->parent_contact ?? null,
                'gender' => $request->gender ?? null,
                'date_of_birth' => $request->date_of_birth ?? null,
                'address' => $request->address ?? null,
                'city' => $request->city ?? null,
                'region' => $request->region ?? null,
                'class' => $request->class ?? null
            ];
            
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
            
            // Handle payment creation based on payment type if available in session
            if (session('registration.payer_type') === 'self') {
                Payment::create([
                    'student_id' => $studentId,
                    'amount' => session('registration.fee_amount'),
                    'payment_method' => 'mobile_money',
                    'reference_number' => session('registration.payment_reference') ?? 'PAYMENT-' . strtoupper(Str::random(8)),
                    'status' => 'completed'
                ]);
            } elseif (session('registration.payer_type') === 'school') {
                Payment::create([
                    'student_id' => $studentId,
                    'amount' => session('registration.fee_amount'),
                    'payment_method' => 'school_payment',
                    'reference_number' => 'SCHOOL-' . strtoupper(Str::random(8)),
                    'status' => 'pending'
                ]);
            }
            
            // Store student info in session for success page
            session(['registration.student' => $studentObj]);
            session(['registration.registration_number' => $registrationNumber]);
            
            // Clear other registration session data
            session()->forget([
                'registration.program_type_id',
                'registration.school_id',
                'registration.payer_type',
                'registration.fee_amount',
                'registration.payment_reference',
            ]);
            
            // Flash success message to session
            session()->flash('success', 'Registration completed successfully! Your registration number is ' . $registrationNumber);
            
            // Use absolute URL instead of named route to ensure proper redirection
            return redirect('/students/register/success');
            
        } catch (\Exception $e) {
            // Log the error and return a friendly message
            \Log::error('Student registration details error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // During development, show the actual error message
            $errorMsg = config('app.debug') ? 'Error: ' . $e->getMessage() : 'There was a problem saving your registration. Please try again or contact support.';
            return redirect()->back()->withInput()->with('error', $errorMsg);
        }
    }
    
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
        
        // Format with leading zeros (e.g., YEG202507001)
        return $prefix.str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
    }
}
