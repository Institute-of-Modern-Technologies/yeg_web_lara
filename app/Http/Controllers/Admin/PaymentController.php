<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Student;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    /**
     * Display the form for creating a new payment for the given student.
     *
     * @param  int  $studentId
     * @return \Illuminate\Http\Response
     */
    public function create($studentId)
    {
        try {
            $student = Student::findOrFail($studentId);
            
            // Get the applicable fee for this student
            $fee = \App\Models\Fee::where('program_type_id', $student->program_type_id)
                ->where(function($query) use ($student) {
                    $query->where('school_id', $student->school_id)
                          ->orWhereNull('school_id');
                })
                ->where('is_active', true)
                ->orderByRaw('school_id IS NULL')
                ->first();

            $amountToBePaid = $fee ? ($fee->amount - $fee->partner_discount) : 0;
            
            // Calculate previous payments
            $previousPayments = Payment::where('student_id', $studentId)
                ->where('status', 'completed')
                ->sum('final_amount');
            
            // Calculate balance
            $balance = $amountToBePaid - $previousPayments;
            
            // If the request is an AJAX request, return a partial view for the modal
            if (request()->ajax()) {
                return view('admin.payments.partials.create_form', compact('student', 'fee', 'amountToBePaid', 'previousPayments', 'balance'))->render();
            }
            
            // Otherwise redirect to student view (fallback)
            return redirect()->route('admin.students.show', $studentId);
        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Student not found or error loading payment form'
                ], 404);
            }
            
            return redirect()->route('admin.students.index')->with('error', 'Student not found');
        }
    }

    /**
     * Store a newly created payment in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            // Validate the request
            $validator = Validator::make($request->all(), [
                'student_id' => 'required|exists:students,id',
                'amount' => 'required|numeric|min:0',
                'discount' => 'nullable|numeric|min:0',
                'final_amount' => 'required|numeric|min:0',
                'payment_method' => 'required|string|in:cash', // Currently only cash is supported
                'notes' => 'nullable|string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Generate a unique reference number in format YEGdaymonthyearrandom_suffix
            $date = now();
            $day = $date->format('d');
            $month = $date->format('m');
            $year = $date->format('Y');
            $randomSuffix = mt_rand(1000, 9999); // 4-digit random number
            $reference_number = "YEG{$day}{$month}{$year}{$randomSuffix}";
            
            // Create the payment record
            $payment = Payment::create([
                'student_id' => $request->student_id,
                'amount' => $request->amount,
                'discount' => $request->discount ?? 0,
                'final_amount' => $request->final_amount,
                'reference_number' => $reference_number,
                'status' => 'completed', // Always set to completed
                'payment_method' => $request->payment_method,
                'notes' => $request->notes
            ]);

            // Get student details for the response
            $student = Student::find($request->student_id);

            return response()->json([
                'success' => true,
                'message' => 'Payment recorded successfully',
                'payment' => $payment,
                'payment_id' => $payment->id,
                'student' => [
                    'id' => $student->id,
                    'name' => $student->first_name . ' ' . $student->last_name
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified payment.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $payment = Payment::with('student')->findOrFail($id);
            
            return view('admin.payments.show', compact('payment'));
        } catch (\Exception $e) {
            return redirect()->route('admin.students.index')->with('error', 'Payment not found');
        }
    }
    
    /**
     * Display the receipt for a specific payment.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showReceipt($id)
    {
        try {
            $payment = Payment::with(['student.programType', 'student.school'])->findOrFail($id);
            
            // Get the applicable fee for this student
            $fee = \App\Models\Fee::where('program_type_id', $payment->student->program_type_id)
                ->where(function($query) use ($payment) {
                    $query->where('school_id', $payment->student->school_id)
                          ->orWhereNull('school_id');
                })
                ->where('is_active', true)
                ->orderByRaw('school_id IS NULL')
                ->first();

            $amountToBePaid = $fee ? ($fee->amount - $fee->partner_discount) : 0;
            
            // Calculate previous payments (excluding current payment)
            $previousPayments = \App\Models\Payment::where('student_id', $payment->student_id)
                ->where('status', 'completed')
                ->where('id', '!=', $payment->id)
                ->sum('final_amount');
            
            // Total paid INCLUDING the current payment
            $totalPaid = $previousPayments + $payment->final_amount;
            
            // Calculate balance after this payment
            $balance = $amountToBePaid - $totalPaid;
            
            return view('admin.payments.receipt', compact('payment', 'fee', 'amountToBePaid', 'totalPaid', 'balance'));
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Payment not found'
            ], 404);
        }
    }

    /**
     * Get all payments for a student.
     *
     * @param  int  $studentId
     * @return \Illuminate\Http\Response
     */
    public function getStudentPayments($studentId)
    {
        try {
            $student = Student::with(['programType', 'school'])->findOrFail($studentId);
            $payments = Payment::where('student_id', $studentId)
                ->orderBy('created_at', 'desc')
                ->get();
            
            // Calculate the correct fee amount and total paid (same logic as billing controller)
            $fee = \App\Models\Fee::where('program_type_id', $student->program_type_id)
                ->where(function($query) use ($student) {
                    $query->where('school_id', $student->school_id)
                          ->orWhereNull('school_id');
                })
                ->where('is_active', true)
                ->orderByRaw('school_id IS NULL')
                ->first();
            
            $amountToBePaid = $fee ? ($fee->amount - $fee->partner_discount) : 0;
            $totalPaid = Payment::where('student_id', $studentId)
                ->where('status', 'completed')
                ->sum('final_amount');
            $balance = $amountToBePaid - $totalPaid;
            
            // Add calculated values to student object
            $student->fee_amount = $amountToBePaid;
            $student->total_paid = $totalPaid;
            $student->balance = $balance;
            
            // If it's an AJAX request, return JSON
            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'student' => [
                        'id' => $student->id,
                        'name' => $student->first_name . ' ' . $student->last_name
                    ],
                    'payments' => $payments
                ]);
            }
            
            // Otherwise, return HTML view
            return view('admin.payments.history', compact('student', 'payments'));
        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'An error occurred: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->route('admin.billing.index')->with('error', 'Student not found');
        }
    }
}
