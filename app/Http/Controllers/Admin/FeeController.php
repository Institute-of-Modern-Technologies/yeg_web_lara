<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Fee;
use App\Models\ProgramType;
use App\Models\School;

class FeeController extends Controller
{
    /**
     * Display a listing of the fees.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Fee::with(['programType', 'school']);

        // Handle search functionality
        if ($request->has('search')) {
            $search = $request->search;
            $query->whereHas('programType', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            })->orWhereHas('school', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        $fees = $query->latest()->paginate(10);
        return view('admin.setups.fees.index', compact('fees'));
    }

    /**
     * Show the form for creating a new fee.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $programTypes = ProgramType::where('is_active', true)->get();
        $schools = School::where('status', 'approved')->get();
        return view('admin.setups.fees.create', compact('programTypes', 'schools'));
    }

    /**
     * Store a newly created fee in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Get program type to determine validation rules
        $programType = ProgramType::findOrFail($request->program_type_id);
        $isInSchool = strtolower($programType->name) === 'in school';
        
        // Build validation rules based on program type
        $rules = [
            'program_type_id' => 'required|exists:program_types,id',
            'amount' => 'required|numeric|min:0',
        ];
        
        if ($isInSchool) {
            $rules['school_id'] = 'required|exists:schools,id';
            $rules['school_commission'] = 'required|numeric|min:0';
            $rules['imt_commission'] = 'required|numeric|min:0';
        } else {
            $rules['school_id'] = 'nullable|exists:schools,id';
            $rules['partner_discount'] = 'required|numeric|min:0';
        }
        
        $request->validate($rules);
        
        // Prepare fee data
        $feeData = [
            'program_type_id' => $request->program_type_id,
            'amount' => $request->amount,
            'is_active' => $request->has('is_active') ? 1 : 0,
        ];
        
        // Set school_id for both program types if provided
        if ($request->school_id) {
            $feeData['school_id'] = $request->school_id;
        }
        
        // Add specific fields based on program type
        if ($isInSchool) {
            $feeData['school_commission'] = $request->school_commission;
            $feeData['imt_commission'] = $request->imt_commission;
            $feeData['partner_discount'] = 0; // Default value for in-school programs
        } else {
            $feeData['partner_discount'] = $request->partner_discount;
            $feeData['school_commission'] = null;
            $feeData['imt_commission'] = null;
        }
        
        Fee::create($feeData);

        return redirect()->route('admin.fees.index')
            ->with('success', 'Fee created successfully.');
    }

    /**
     * Show the form for editing the specified fee.
     *
     * @param  \App\Models\Fee  $fee
     * @return \Illuminate\Http\Response
     */
    public function edit(Fee $fee)
    {
        $programTypes = ProgramType::where('is_active', true)->get();
        $schools = School::where('status', 'approved')->get();
        return view('admin.setups.fees.edit', compact('fee', 'programTypes', 'schools'));
    }

    /**
     * Update the specified fee in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Fee  $fee
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Fee $fee)
    {
        // Get program type to determine validation rules
        $programType = ProgramType::findOrFail($request->program_type_id);
        $isInSchool = strtolower($programType->name) === 'in school';
        
        // Build validation rules based on program type
        $rules = [
            'program_type_id' => 'required|exists:program_types,id',
            'amount' => 'required|numeric|min:0',
        ];
        
        if ($isInSchool) {
            $rules['school_id'] = 'required|exists:schools,id';
            $rules['school_commission'] = 'required|numeric|min:0';
            $rules['imt_commission'] = 'required|numeric|min:0';
        } else {
            $rules['school_id'] = 'nullable|exists:schools,id';
            $rules['partner_discount'] = 'required|numeric|min:0';
        }
        
        $request->validate($rules);
        
        // Prepare fee data
        $feeData = [
            'program_type_id' => $request->program_type_id,
            'amount' => $request->amount,
            'is_active' => $request->has('is_active') ? 1 : 0,
        ];
        
        // Set school_id for both program types if provided
        if ($request->filled('school_id')) {
            $feeData['school_id'] = $request->school_id;
        } else {
            $feeData['school_id'] = null;
        }
        
        // Add specific fields based on program type
        if ($isInSchool) {
            $feeData['school_commission'] = $request->school_commission;
            $feeData['imt_commission'] = $request->imt_commission;
            $feeData['partner_discount'] = 0; // Default value for in-school programs
        } else {
            $feeData['partner_discount'] = $request->partner_discount;
            $feeData['school_commission'] = null;
            $feeData['imt_commission'] = null;
        }
        
        $fee->update($feeData);

        return redirect()->route('admin.fees.index')
            ->with('success', 'Fee updated successfully.');
    }

    /**
     * Remove the specified fee from storage.
     *
     * @param  \App\Models\Fee  $fee
     * @return \Illuminate\Http\Response
     */
    public function destroy(Fee $fee)
    {
        $fee->delete();

        return redirect()->route('admin.fees.index')
            ->with('success', 'Fee deleted successfully.');
    }

    /**
     * Toggle the status of the specified fee.
     *
     * @param  \App\Models\Fee  $fee
     * @return \Illuminate\Http\Response
     */
    public function toggleStatus(Fee $fee)
    {
        $fee->is_active = !$fee->is_active;
        $fee->save();

        return redirect()->route('admin.fees.index')
            ->with('success', 'Fee status updated successfully.');
    }
}
