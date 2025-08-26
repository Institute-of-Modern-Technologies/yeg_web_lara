<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PartnerSchool;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Helpers\ImageUploadHelper;

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
            'image' => 'required|file|mimes:jpeg,png,jpg,gif,svg|max:2048|dimensions:min_width=100,min_height=100',
            'website_url' => 'nullable|url|max:255',
            'is_active' => 'boolean',
            'display_order' => 'integer|min:0',
        ], [
            'image.dimensions' => 'The image must be at least 100x100 pixels.',
            'image.mimes' => 'The image must be a file of type: jpeg, png, jpg, gif, svg.',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        // Handle image upload using ImageUploadHelper
        $imagePath = null;
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $imagePath = ImageUploadHelper::uploadImageToPublic($request->file('image'), 'partner-schools');
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
            'image' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg|max:2048|dimensions:min_width=100,min_height=100',
            'website_url' => 'nullable|url|max:255',
            'is_active' => 'boolean',
            'display_order' => 'integer|min:0',
        ], [
            'image.dimensions' => 'The image must be at least 100x100 pixels.',
            'image.mimes' => 'The image must be a file of type: jpeg, png, jpg, gif, svg.',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        // Handle image upload if a new image is provided using ImageUploadHelper
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            // Delete old image if it exists
            if ($partnerSchool->image_path) {
                ImageUploadHelper::deleteImageFromPublic($partnerSchool->image_path);
            }
            
            // Upload new image using the helper
            $partnerSchool->image_path = ImageUploadHelper::uploadImageToPublic($request->file('image'), 'partner-schools');
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
        
        // Delete the image file if it exists using ImageUploadHelper
        if ($partnerSchool->image_path) {
            ImageUploadHelper::deleteImageFromPublic($partnerSchool->image_path);
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
    
    /**
     * Toggle the active status of a partner school.
     */
    public function toggleActive(Request $request, string $id)
    {
        // Check if user is authorized
        if (Auth::user()->user_type_id != 1) {
            if ($request->wantsJson()) {
                return response()->json(['error' => 'Unauthorized access'], 403);
            }
            return redirect()->back()->with('error', 'Unauthorized access');
        }
        
        $partnerSchool = PartnerSchool::findOrFail($id);
        
        // Set the status explicitly based on request or toggle if not provided
        if ($request->has('is_active_value')) {
            $partnerSchool->is_active = $request->input('is_active_value') == '1';
        } else if ($request->has('is_active')) {
            $partnerSchool->is_active = $request->input('is_active') == '1';
        } else {
            $partnerSchool->is_active = !$partnerSchool->is_active;
        }
        
        $partnerSchool->save();
        
        $message = $partnerSchool->is_active ? 'Partner school activated' : 'Partner school deactivated';
        
        // Return JSON response for AJAX requests
        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'is_active' => $partnerSchool->is_active,
                'message' => $message
            ]);
        }
        
        // Return redirect for form submissions
        return redirect()->back()->with('success', $message);
    }
}
