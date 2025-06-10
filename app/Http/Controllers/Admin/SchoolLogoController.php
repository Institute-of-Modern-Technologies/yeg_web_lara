<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SchoolLogo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class SchoolLogoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $schoolLogos = SchoolLogo::orderBy('display_order')->get();
        return view('admin.school-logos.index', compact('schoolLogos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.school-logos.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'logo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'display_order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('school_logos', 'public');
        }

        SchoolLogo::create([
            'name' => $request->name,
            'logo_path' => $logoPath ?? null,
            'display_order' => $request->display_order ?? 0,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.school-logos.index')
            ->with('success', 'School logo added successfully');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $schoolLogo = SchoolLogo::findOrFail($id);
        return view('admin.school-logos.edit', compact('schoolLogo'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'display_order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $schoolLogo = SchoolLogo::findOrFail($id);
        
        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($schoolLogo->logo_path && Storage::disk('public')->exists($schoolLogo->logo_path)) {
                Storage::disk('public')->delete($schoolLogo->logo_path);
            }
            
            $logoPath = $request->file('logo')->store('school_logos', 'public');
            $schoolLogo->logo_path = $logoPath;
        }

        $schoolLogo->name = $request->name;
        $schoolLogo->display_order = $request->display_order ?? 0;
        $schoolLogo->is_active = $request->has('is_active');
        $schoolLogo->save();

        return redirect()->route('admin.school-logos.index')
            ->with('success', 'School logo updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $schoolLogo = SchoolLogo::findOrFail($id);
        
        // Delete logo file
        if ($schoolLogo->logo_path && Storage::disk('public')->exists($schoolLogo->logo_path)) {
            Storage::disk('public')->delete($schoolLogo->logo_path);
        }
        
        $schoolLogo->delete();
        
        return redirect()->route('admin.school-logos.index')
            ->with('success', 'School logo deleted successfully');
    }
    
    /**
     * Update the order of logos
     */
    public function updateOrder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'logos' => 'required|array',
            'logos.*.id' => 'required|exists:school_logos,id',
            'logos.*.order' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        foreach ($request->logos as $logo) {
            SchoolLogo::where('id', $logo['id'])->update(['display_order' => $logo['order']]);
        }

        return response()->json(['success' => true]);
    }
    
    /**
     * Toggle active status
     */
    public function toggleActive(Request $request, $id)
    {
        $schoolLogo = SchoolLogo::findOrFail($id);
        $schoolLogo->is_active = !$schoolLogo->is_active;
        $schoolLogo->save();
        
        return redirect()->route('admin.school-logos.index')
            ->with('success', 'School logo status updated successfully');
    }
}
