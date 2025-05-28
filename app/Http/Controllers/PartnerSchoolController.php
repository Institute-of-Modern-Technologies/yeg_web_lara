<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PartnerSchool;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PartnerSchoolController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Check if user is authorized
        if (Auth::user()->user_type_id != 1) {
            return redirect()->back()->with('error', 'Unauthorized access');
        }
        
        // Get all partner schools ordered by display order
        $partnerSchools = PartnerSchool::orderBy('display_order', 'asc')->get();
        
        return view('admin.partner-schools.index', compact('partnerSchools'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Check if user is authorized
        if (Auth::user()->user_type_id != 1) {
            return redirect()->back()->with('error', 'Unauthorized access');
        }
        
        return view('admin.partner-schools.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Check if user is authorized
        if (Auth::user()->user_type_id != 1) {
            return redirect()->back()->with('error', 'Unauthorized access');
        }
        
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'required|file|mimes:jpeg,png,jpg,gif|max:2048',
            'website_url' => 'nullable|url|max:255',
            'is_active' => 'boolean',
            'display_order' => 'integer|min:0',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        // Handle image upload
        $imagePath = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . uniqid() . '_' . str_replace(' ', '_', $image->getClientOriginalName());
            $image->move(public_path('storage/partner-schools'), $imageName);
            $imagePath = 'partner-schools/' . $imageName;
        }
        
        // Create new partner school
        PartnerSchool::create([
            'name' => $request->name,
            'description' => $request->description,
            'image_path' => $imagePath,
            'website_url' => $request->website_url,
            'is_active' => $request->has('is_active'),
            'display_order' => $request->display_order ?? 0,
        ]);
        
        return redirect()->route('admin.partner-schools.index')
            ->with('success', 'Partner school created successfully!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Check if user is authorized
        if (Auth::user()->user_type_id != 1) {
            return redirect()->back()->with('error', 'Unauthorized access');
        }
        
        $partnerSchool = PartnerSchool::findOrFail($id);
        
        return view('admin.partner-schools.edit', compact('partnerSchool'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Check if user is authorized
        if (Auth::user()->user_type_id != 1) {
            return redirect()->back()->with('error', 'Unauthorized access');
        }
        
        $partnerSchool = PartnerSchool::findOrFail($id);
        
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|file|mimes:jpeg,png,jpg,gif|max:2048',
            'website_url' => 'nullable|url|max:255',
            'is_active' => 'boolean',
            'display_order' => 'integer|min:0',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        // Handle image upload if new image is provided
        if ($request->hasFile('image')) {
            // Delete old image if it exists
            if ($partnerSchool->image_path) {
                $oldImagePath = public_path('storage/' . $partnerSchool->image_path);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
            
            // Store new image
            $image = $request->file('image');
            $imageName = time() . '_' . uniqid() . '_' . str_replace(' ', '_', $image->getClientOriginalName());
            $image->move(public_path('storage/partner-schools'), $imageName);
            $partnerSchool->image_path = 'partner-schools/' . $imageName;
        }
        
        // Update partner school data
        $partnerSchool->name = $request->name;
        $partnerSchool->description = $request->description;
        $partnerSchool->website_url = $request->website_url;
        $partnerSchool->is_active = $request->has('is_active');
        $partnerSchool->display_order = $request->display_order ?? 0;
        
        $partnerSchool->save();
        
        return redirect()->route('admin.partner-schools.index')
            ->with('success', 'Partner school updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Check if user is authorized
        if (Auth::user()->user_type_id != 1) {
            return redirect()->back()->with('error', 'Unauthorized access');
        }
        
        $partnerSchool = PartnerSchool::findOrFail($id);
        
        // Delete the image file if it exists
        if ($partnerSchool->image_path) {
            $imagePath = public_path('storage/' . $partnerSchool->image_path);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }
        
        $partnerSchool->delete();
        
        return redirect()->route('admin.partner-schools.index')
            ->with('success', 'Partner school deleted successfully!');
    }
    
    /**
     * Update the display order of partner schools.
     */
    public function updateOrder(Request $request)
    {
        // Check if user is authorized
        if (Auth::user()->user_type_id != 1) {
            return response()->json(['error' => 'Unauthorized access'], 403);
        }
        
        $partnerSchools = $request->input('partnerSchools', []);
        
        foreach ($partnerSchools as $partnerSchool) {
            $partnerSchoolModel = PartnerSchool::find($partnerSchool['id']);
            if ($partnerSchoolModel) {
                $partnerSchoolModel->display_order = $partnerSchool['position'];
                $partnerSchoolModel->save();
            }
        }
        
        return response()->json(['success' => true]);
    }
}
