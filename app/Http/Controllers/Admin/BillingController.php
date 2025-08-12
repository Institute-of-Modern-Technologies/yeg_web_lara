<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Fee;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BillingController extends Controller
{
    /**
     * Display the billing page with student payment information
     */
    public function index(Request $request)
    {
        $query = Student::with(['programType', 'school', 'payments'])
            ->select('students.*')
            ->leftJoin('fees', function($join) {
                $join->on('students.program_type_id', '=', 'fees.program_type_id')
                     ->where(function($query) {
                         $query->whereColumn('students.school_id', 'fees.school_id')
                               ->orWhereNull('fees.school_id');
                     });
            })
            // Only show students that admin can manage
            ->where(function($query) {
                $query->where('admin_can_manage', true)
                      ->orWhere('is_school_managed', false)
                      ->orWhereNull('is_school_managed');
            })
            ->addSelect([
                'fee_amount' => Fee::selectRaw('COALESCE(amount - partner_discount, amount)')
                    ->whereColumn('program_type_id', 'students.program_type_id')
                    ->where(function($query) {
                        $query->whereColumn('school_id', 'students.school_id')
                              ->orWhereNull('school_id');
                    })
                    ->where('is_active', true)
                    ->orderByRaw('school_id IS NULL')
                    ->limit(1),
                'total_paid' => Payment::selectRaw('COALESCE(SUM(final_amount), 0)')
                    ->whereColumn('student_id', 'students.id')
                    ->where('status', 'completed')
            ]);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('registration_number', 'like', "%{$search}%");
            });
        }

        // Filter by payment status
        if ($request->filled('payment_status')) {
            $status = $request->payment_status;
            if ($status === 'paid') {
                $query->havingRaw('COALESCE(fee_amount, 0) <= COALESCE(total_paid, 0)');
            } elseif ($status === 'partial') {
                $query->havingRaw('COALESCE(total_paid, 0) > 0 AND COALESCE(total_paid, 0) < COALESCE(fee_amount, 0)');
            } elseif ($status === 'unpaid') {
                $query->havingRaw('COALESCE(total_paid, 0) = 0');
            }
        }

        $students = $query->orderBy('full_name')->paginate(20);

        // Calculate totals for summary
        $totalStudents = Student::count();
        $totalAmountToBePaid = Student::leftJoin('fees', function($join) {
                $join->on('students.program_type_id', '=', 'fees.program_type_id')
                     ->where(function($query) {
                         $query->whereColumn('students.school_id', 'fees.school_id')
                               ->orWhereNull('fees.school_id');
                     });
            })
            ->where('fees.is_active', true)
            ->sum(DB::raw('COALESCE(fees.amount - fees.partner_discount, fees.amount, 0)'));

        $totalAmountPaid = Payment::where('status', 'completed')->sum('final_amount');
        $totalOutstandingBalance = $totalAmountToBePaid - $totalAmountPaid;
        
        // Get program types for filter
        $programTypes = \App\Models\ProgramType::orderBy('name')->get();

        return view('admin.billing.index', compact(
            'students', 
            'totalStudents', 
            'totalAmountToBePaid', 
            'totalAmountPaid', 
            'totalOutstandingBalance',
            'programTypes'
        ));
    }

    /**
     * Show detailed billing information for a specific student
     */
    public function show(Student $student)
    {
        $student->load(['programType', 'school', 'payments' => function($query) {
            $query->orderBy('created_at', 'desc');
        }]);

        // Get the applicable fee for this student
        $fee = Fee::where('program_type_id', $student->program_type_id)
            ->where(function($query) use ($student) {
                $query->where('school_id', $student->school_id)
                      ->orWhereNull('school_id');
            })
            ->where('is_active', true)
            ->orderByRaw('school_id IS NULL')
            ->first();

        $amountToBePaid = $fee ? ($fee->amount - $fee->partner_discount) : 0;
        $totalPaid = $student->payments->where('status', 'completed')->sum('final_amount');
        $balance = $amountToBePaid - $totalPaid;

        return view('admin.billing.show', compact('student', 'fee', 'amountToBePaid', 'totalPaid', 'balance'));
    }

    /**
     * Generate a modern PDF bill for a student
     */
    public function generateBill(Student $student)
    {
        $student->load(['programType', 'school', 'payments' => function($query) {
            $query->where('status', 'completed')->orderBy('created_at', 'desc');
        }]);

        // Get the applicable fee for this student
        $fee = Fee::where('program_type_id', $student->program_type_id)
            ->where(function($query) use ($student) {
                $query->where('school_id', $student->school_id)
                      ->orWhereNull('school_id');
            })
            ->where('is_active', true)
            ->orderByRaw('school_id IS NULL')
            ->first();

        $amountToBePaid = $fee ? ($fee->amount - $fee->partner_discount) : 0;
        $totalPaid = $student->payments->sum('final_amount');
        $balance = $amountToBePaid - $totalPaid;

        // Generate bill data
        $billData = [
            'student' => $student,
            'fee' => $fee,
            'amountToBePaid' => $amountToBePaid,
            'totalPaid' => $totalPaid,
            'balance' => $balance,
            'payments' => $student->payments,
            'billNumber' => 'BILL-' . date('Y') . '-' . str_pad($student->id, 6, '0', STR_PAD_LEFT),
            'billDate' => now()->format('F d, Y'),
            'dueDate' => now()->addDays(30)->format('F d, Y'),
        ];

        // If it's an AJAX request, return JSON for now (can be enhanced to return PDF)
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Bill generated successfully',
                'data' => $billData
            ]);
        }

        // Return bill view for HTML display
        return view('admin.billing.bill', $billData);
    }

    /**
     * Get bill information for input forms
     */
    public function getBillInfo(Student $student)
    {
        try {
            // Calculate billing information
            $fee = Fee::where('program_type_id', $student->program_type_id)
                ->where(function($query) use ($student) {
                    $query->where('school_id', $student->school_id)
                          ->orWhereNull('school_id');
                })
                ->where('is_active', true)
                ->orderByRaw('school_id IS NULL')
                ->first();

            $amountToBePaid = $fee ? ($fee->amount - $fee->partner_discount) : 0;
            $totalPaid = Payment::where('student_id', $student->id)
                ->where('status', 'completed')
                ->sum('final_amount');
            $balance = $amountToBePaid - $totalPaid;

            // Create WhatsApp message
            $whatsappMessage = "Hello {$student->first_name},\n\n";
            $whatsappMessage .= "Here is your billing statement from Institute of Modern Technologies:\n\n";
            $whatsappMessage .= "📋 *BILLING DETAILS*\n";
            $whatsappMessage .= "Student: {$student->first_name} {$student->last_name}\n";
            $whatsappMessage .= "Program: {$student->programType->name}\n";
            $whatsappMessage .= "School: {$student->school->name}\n\n";
            $whatsappMessage .= "💰 *PAYMENT SUMMARY*\n";
            $whatsappMessage .= "Amount to be Paid: GH₵" . number_format($amountToBePaid, 2) . "\n";
            $whatsappMessage .= "Amount Paid: GH₵" . number_format($totalPaid, 2) . "\n";
            $whatsappMessage .= "Outstanding Balance: GH₵" . number_format($balance, 2) . "\n\n";
            
            if ($balance > 0) {
                $whatsappMessage .= "⚠️ You have an outstanding balance of GH₵" . number_format($balance, 2) . "\n";
                $whatsappMessage .= "Please contact our office to arrange payment.\n\n";
            } else {
                $whatsappMessage .= "✅ Your account is fully paid. Thank you!\n\n";
            }
            
            $whatsappMessage .= "For detailed bill, visit: " . route('admin.billing.generate', $student->id) . "\n\n";
            $whatsappMessage .= "Thank you,\nInstitute of Modern Technologies";

            // Create email message
            $emailMessage = "Dear {$student->first_name} {$student->last_name},\n\n";
            $emailMessage .= "We hope this email finds you well. Please find your current billing statement below:\n\n";
            $emailMessage .= "STUDENT INFORMATION:\n";
            $emailMessage .= "- Full Name: {$student->first_name} {$student->last_name}\n";
            $emailMessage .= "- Student ID: " . ($student->student_id ?? 'N/A') . "\n";
            $emailMessage .= "- Program: {$student->programType->name}\n";
            $emailMessage .= "- School: {$student->school->name}\n";
            $emailMessage .= "- Email: {$student->email}\n\n";
            $emailMessage .= "PAYMENT SUMMARY:\n";
            $emailMessage .= "- Amount to be Paid: GH₵" . number_format($amountToBePaid, 2) . "\n";
            $emailMessage .= "- Amount Paid: GH₵" . number_format($totalPaid, 2) . "\n";
            $emailMessage .= "- Outstanding Balance: GH₵" . number_format($balance, 2) . "\n\n";
            
            if ($balance > 0) {
                $emailMessage .= "OUTSTANDING BALANCE:\n";
                $emailMessage .= "You have an outstanding balance of GH₵" . number_format($balance, 2) . ". ";
                $emailMessage .= "Please contact our office to arrange payment or make a payment as soon as possible.\n\n";
            } else {
                $emailMessage .= "ACCOUNT STATUS:\n";
                $emailMessage .= "Congratulations! Your account is fully paid. Thank you for your prompt payment.\n\n";
            }
            
            $emailMessage .= "For a detailed billing statement, please visit: " . route('admin.billing.generate', $student->id) . "\n\n";
            $emailMessage .= "If you have any questions about your billing statement, please contact our office.\n\n";
            $emailMessage .= "Thank you,\n";
            $emailMessage .= "Institute of Modern Technologies\n";
            $emailMessage .= "Email: info@imt.edu.gh\n";
            $emailMessage .= "Phone: +233 XX XXX XXXX";

            return response()->json([
                'success' => true,
                'phone' => $student->phone,
                'email' => $student->email,
                'whatsapp_message' => $whatsappMessage,
                'email_subject' => 'Billing Statement - Institute of Modern Technologies',
                'email_message' => $emailMessage,
                'student_name' => $student->first_name . ' ' . $student->last_name,
                'balance' => $balance
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load bill information: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Send bill via WhatsApp
     */
    public function sendBillViaWhatsApp(Student $student)
    {
        try {
            // Get student's phone number
            $phoneNumber = $student->phone;
            
            if (!$phoneNumber) {
                return response()->json([
                    'success' => false,
                    'message' => 'Student phone number not found. Please update student contact information.'
                ]);
            }

            // Calculate billing information
            $fee = Fee::where('program_type_id', $student->program_type_id)
                ->where(function($query) use ($student) {
                    $query->where('school_id', $student->school_id)
                          ->orWhereNull('school_id');
                })
                ->where('is_active', true)
                ->orderByRaw('school_id IS NULL')
                ->first();

            $amountToBePaid = $fee ? ($fee->amount - $fee->partner_discount) : 0;
            $totalPaid = Payment::where('student_id', $student->id)
                ->where('status', 'completed')
                ->sum('final_amount');
            $balance = $amountToBePaid - $totalPaid;

            // Format phone number for WhatsApp (remove any non-digits and add country code if needed)
            $formattedPhone = preg_replace('/[^0-9]/', '', $phoneNumber);
            if (!str_starts_with($formattedPhone, '233') && strlen($formattedPhone) == 10) {
                $formattedPhone = '233' . substr($formattedPhone, 1);
            }

            // Create WhatsApp message
            $message = "Hello {$student->first_name},\n\n";
            $message .= "Here is your billing statement from Institute of Modern Technologies:\n\n";
            $message .= "📋 *BILLING DETAILS*\n";
            $message .= "Student: {$student->first_name} {$student->last_name}\n";
            $message .= "Program: {$student->programType->name}\n";
            $message .= "School: {$student->school->name}\n\n";
            $message .= "💰 *PAYMENT SUMMARY*\n";
            $message .= "Amount to be Paid: GH₵" . number_format($amountToBePaid, 2) . "\n";
            $message .= "Amount Paid: GH₵" . number_format($totalPaid, 2) . "\n";
            $message .= "Outstanding Balance: GH₵" . number_format($balance, 2) . "\n\n";
            
            if ($balance > 0) {
                $message .= "⚠️ You have an outstanding balance of GH₵" . number_format($balance, 2) . "\n";
                $message .= "Please contact our office to arrange payment.\n\n";
            } else {
                $message .= "✅ Your account is fully paid. Thank you!\n\n";
            }
            
            $message .= "For detailed bill, visit: " . route('admin.billing.generate', $student->id) . "\n\n";
            $message .= "Thank you,\nInstitute of Modern Technologies";

            // Create WhatsApp URL
            $whatsappUrl = "https://wa.me/{$formattedPhone}?text=" . urlencode($message);

            return response()->json([
                'success' => true,
                'message' => 'WhatsApp message prepared successfully. Opening WhatsApp...',
                'whatsapp_url' => $whatsappUrl
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to prepare WhatsApp message: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Send bill via Email
     */
    public function sendBillViaEmail(Student $student)
    {
        try {
            // Get custom content from request
            $requestData = json_decode(request()->getContent(), true);
            $customEmail = $requestData['email'] ?? $student->email;
            $customSubject = $requestData['subject'] ?? 'Billing Statement - Institute of Modern Technologies';
            $customMessage = $requestData['message'] ?? '';
            
            if (!$customEmail) {
                return response()->json([
                    'success' => false,
                    'message' => 'Email address is required.'
                ]);
            }

            if (!$customMessage) {
                return response()->json([
                    'success' => false,
                    'message' => 'Email message is required.'
                ]);
            }

            // Calculate billing information for email data
            $fee = Fee::where('program_type_id', $student->program_type_id)
                ->where(function($query) use ($student) {
                    $query->where('school_id', $student->school_id)
                          ->orWhereNull('school_id');
                })
                ->where('is_active', true)
                ->orderByRaw('school_id IS NULL')
                ->first();

            $amountToBePaid = $fee ? ($fee->amount - $fee->partner_discount) : 0;
            $totalPaid = Payment::where('student_id', $student->id)
                ->where('status', 'completed')
                ->sum('final_amount');
            $balance = $amountToBePaid - $totalPaid;

            // Prepare email data with custom content
            $emailData = [
                'student' => $student,
                'fee' => $fee,
                'amountToBePaid' => $amountToBePaid,
                'totalPaid' => $totalPaid,
                'balance' => $balance,
                'billUrl' => route('admin.billing.generate', $student->id),
                'customMessage' => $customMessage,
                'customSubject' => $customSubject
            ];

            // Send email using Laravel's Mail facade with custom content
            \Mail::send('emails.custom-billing-statement', $emailData, function($message) use ($student, $customEmail, $customSubject) {
                $message->to($customEmail, $student->first_name . ' ' . $student->last_name)
                        ->subject($customSubject)
                        ->from(config('mail.from.address'), config('mail.from.name'));
            });

            return response()->json([
                'success' => true,
                'message' => "Email sent successfully to {$customEmail}"
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send email: ' . $e->getMessage()
            ]);
        }
    }
}
