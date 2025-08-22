<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Testimonial;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TestimonialController extends Controller
{
    /**
     * Display a listing of the testimonials.
     */
    public function index()
    {
        // Check if user is authorized
        if (Auth::user()->user_type_id != 1) {
            return redirect()->back()->with('error', 'Unauthorized access');
        }
        
        // Get all testimonials ordered by display order
        $testimonials = Testimonial::orderBy('display_order', 'asc')->get();
        
        return view('admin.testimonials.index', compact('testimonials'));
    }

    /**
     * Show the form for creating a new testimonial.
     */
    public function create()
    {
        // Check if user is authorized
        if (Auth::user()->user_type_id != 1) {
            return redirect()->back()->with('error', 'Unauthorized access');
        }
        
        return view('admin.testimonials.create');
    }

    /**
     * Store a newly created testimonial in storage.
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
            'role' => 'nullable|string|max:255',
            'institution' => 'nullable|string|max:255',
            'content' => 'required|string',
            'image' => 'required|file|mimes:jpeg,png,jpg,gif|max:2048',
            'rating' => 'required|integer|min:1|max:5',
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
            
            // Ensure directory exists
            $targetDir = public_path('images/testimonials');
            if (!file_exists($targetDir)) {
                mkdir($targetDir, 0755, true);
            }
            
            // Move file to public/images/testimonials
            $image->move($targetDir, $imageName);
            $imagePath = 'images/testimonials/' . $imageName;
        }
        
        // Create new testimonial
        Testimonial::create([
            'name' => $request->name,
            'role' => $request->role,
            'institution' => $request->institution,
            'content' => $request->content,
            'image_path' => $imagePath,
            'rating' => $request->rating,
            'is_active' => $request->has('is_active'),
            'display_order' => $request->display_order ?? 0,
        ]);
        
        return redirect()->route('admin.testimonials.index')
            ->with('success', 'Testimonial created successfully!');
    }

    /**
     * Show the form for editing the specified testimonial.
     */
    public function edit(string $id)
    {
        // Check if user is authorized
        if (Auth::user()->user_type_id != 1) {
            return redirect()->back()->with('error', 'Unauthorized access');
        }
        
        $testimonial = Testimonial::findOrFail($id);
        
        return view('admin.testimonials.edit', compact('testimonial'));
    }

    /**
     * Update the specified testimonial in storage.
     */
    public function update(Request $request, string $id)
    {
        // Check if user is authorized
        if (Auth::user()->user_type_id != 1) {
            return redirect()->back()->with('error', 'Unauthorized access');
        }
        
        $testimonial = Testimonial::findOrFail($id);
        
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'role' => 'nullable|string|max:255',
            'institution' => 'nullable|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|file|mimes:jpeg,png,jpg,gif|max:2048',
            'rating' => 'required|integer|min:1|max:5',
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
            // Remove old image if exists
            if ($testimonial->image_path) {
                $oldImagePath = public_path($testimonial->image_path);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
            
            // Ensure directory exists
            $targetDir = public_path('images/testimonials');
            if (!file_exists($targetDir)) {
                mkdir($targetDir, 0755, true);
            }
            
            // Store new image
            $image = $request->file('image');
            $imageName = time() . '_' . uniqid() . '_' . str_replace(' ', '_', $image->getClientOriginalName());
            $image->move($targetDir, $imageName);
            $testimonial->image_path = 'images/testimonials/' . $imageName;
        }
        
        // Update testimonial data
        $testimonial->name = $request->name;
        $testimonial->role = $request->role;
        $testimonial->institution = $request->institution;
        $testimonial->content = $request->content;
        $testimonial->rating = $request->rating;
        $testimonial->is_active = $request->has('is_active');
        $testimonial->display_order = $request->display_order ?? 0;
        
        $testimonial->save();
        
        return redirect()->route('admin.testimonials.index')
            ->with('success', 'Testimonial updated successfully!');
    }

    /**
     * Remove the specified testimonial from storage.
     */
    public function destroy(string $id)
    {
        // Check if user is authorized
        if (Auth::user()->user_type_id != 1) {
            return redirect()->back()->with('error', 'Unauthorized access');
        }
        
        $testimonial = Testimonial::findOrFail($id);
        
        // Delete the image file if it exists
        if ($testimonial->image_path) {
            $imagePath = public_path($testimonial->image_path);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }
        
        $testimonial->delete();
        
        return redirect()->route('admin.testimonials.index')
            ->with('success', 'Testimonial deleted successfully!');
    }

    /**
     * Update the display order of testimonials.
     */
    public function updateOrder(Request $request)
    {
        // Check if user is authorized
        if (Auth::user()->user_type_id != 1) {
            return response()->json(['error' => 'Unauthorized access'], 403);
        }
        
        $testimonials = $request->input('testimonials', []);
        
        foreach ($testimonials as $testimonial) {
            $testimonialModel = Testimonial::find($testimonial['id']);
            if ($testimonialModel) {
                $testimonialModel->display_order = $testimonial['position'];
                $testimonialModel->save();
            }
        }
        
        return response()->json(['success' => true]);
    }
    
    /**
     * Toggle the active status of a testimonial.
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
        
        $testimonial = Testimonial::findOrFail($id);
        
        // Set the status explicitly based on request or toggle if not provided
        if ($request->has('is_active')) {
            $testimonial->is_active = $request->input('is_active') == '1';
        } else {
            $testimonial->is_active = !$testimonial->is_active;
        }
        
        $testimonial->save();
        
        $message = $testimonial->is_active ? 'Testimonial activated' : 'Testimonial deactivated';
        
        // Return JSON response for AJAX requests
        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'is_active' => $testimonial->is_active,
                'message' => $message
            ]);
        }
        
        // Return redirect for form submissions
        return redirect()->back()->with('success', $message);
    }
}
