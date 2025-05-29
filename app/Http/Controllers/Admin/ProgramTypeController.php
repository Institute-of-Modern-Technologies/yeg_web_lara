<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProgramType;
use Illuminate\Http\Request;

class ProgramTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $programTypes = ProgramType::latest()->paginate(10);
        return view('admin.setups.program-types.index', compact('programTypes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:program_types,name',
        ]);

        ProgramType::create([
            'name' => $request->name
        ]);

        return redirect()->route('admin.program-types.index')
            ->with('success', 'Program type created successfully.');
    }

    /**
     * Display the specified resource for editing.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $programType = ProgramType::findOrFail($id);
        
        if (request()->ajax() || request()->wantsJson()) {
            return response()->json($programType);
        }
        
        return view('admin.setups.program-types.edit', compact('programType'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $programType = ProgramType::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255|unique:program_types,name,' . $id,
        ]);

        $programType->update([
            'name' => $request->name
        ]);

        return redirect()->route('admin.program-types.index')
            ->with('success', 'Program type updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $programType = ProgramType::findOrFail($id);
        $programType->delete();

        return redirect()->route('admin.program-types.index')
            ->with('success', 'Program type deleted successfully.');
    }
}
