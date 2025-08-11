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
}
