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

            // Always autogenerate a reference number in format YEG/day/month/year
            $date = now();
            $day = $date->format('d');
            $month = $date->format('m');
            $year = $date->format('Y');
            $reference_number = "YEG/{$day}/{$month}/{$year}";
            
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
     * Get all payments for a student.
     *
     * @param  int  $studentId
     * @return \Illuminate\Http\Response
     */
    public function getStudentPayments($studentId)
    {
        try {
            $student = Student::findOrFail($studentId);
            $payments = Payment::where('student_id', $studentId)
                ->orderBy('created_at', 'desc')
                ->get();
            
            return response()->json([
                'success' => true,
                'student' => [
                    'id' => $student->id,
                    'name' => $student->first_name . ' ' . $student->last_name
                ],
                'payments' => $payments
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        }
    }
}
