<?php

namespace App\Http\Controllers;

use App\Models\HeroSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class HeroSectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Check if user is authorized to manage hero sections
        if (Auth::user()->user_type_id != 1) {
            return redirect()->back()->with('error', 'Unauthorized access');
        }
        
        // Get all hero sections ordered by display order
        $heroSections = HeroSection::orderBy('display_order', 'asc')->get();
        
        return view('admin.hero-sections.index', compact('heroSections'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Check if user is authorized to manage hero sections
        if (Auth::user()->user_type_id != 1) {
            return redirect()->back()->with('error', 'Unauthorized access');
        }
        
        return view('admin.hero-sections.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Check if user is authorized to manage hero sections
        if (Auth::user()->user_type_id != 1) {
            return redirect()->back()->with('error', 'Unauthorized access');
        }
        
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'button_text' => 'nullable|string|max:50',
            'button_link' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'display_order' => 'integer|min:0',
            'text_color' => 'nullable|string|max:50',
            'title_color' => 'nullable|string|max:50',
            'subtitle_color' => 'nullable|string|max:50',
            'overlay_color' => 'nullable|string|max:50',
            'overlay_opacity' => 'nullable|numeric|min:0|max:1',
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
            $image->move(public_path('storage/hero-sections'), $imageName);
            $imagePath = 'hero-sections/' . $imageName;
        }
        
        // Create new hero section
        $heroSection = HeroSection::create([
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'image_path' => $imagePath,
            'button_text' => $request->button_text,
            'button_link' => $request->button_link,
            'is_active' => $request->has('is_active') ? 1 : 0,
            'display_order' => $request->display_order,
            'text_color' => $request->text_color,
            'title_color' => $request->title_color,
            'subtitle_color' => $request->subtitle_color,
            'brand_text' => $request->brand_text,
            'brand_text_color' => $request->brand_text_color ?? '#ffcb05',
            'overlay_color' => $request->overlay_color,
            'overlay_opacity' => $request->overlay_opacity,
            'text_position' => $request->text_position ?? 'bottom',
        ]);
        
        return redirect()->route('admin.hero-sections.index')
            ->with('success', 'Hero section created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // We're not using this method since we manage hero sections in index view
        return redirect()->route('admin.hero-sections.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Check if user is authorized to manage hero sections
        if (Auth::user()->user_type_id != 1) {
            return redirect()->back()->with('error', 'Unauthorized access');
        }
        
        $heroSection = HeroSection::findOrFail($id);
        
        return view('admin.hero-sections.edit', compact('heroSection'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Check if user is authorized to manage hero sections
        if (Auth::user()->user_type_id != 1) {
            return redirect()->back()->with('error', 'Unauthorized access');
        }
        
        $heroSection = HeroSection::findOrFail($id);
        
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string',
            'button_text' => 'nullable|string|max:50',
            'button_link' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'display_order' => 'integer|min:0',
            'text_color' => 'nullable|string|max:50',
            'title_color' => 'nullable|string|max:50',
            'subtitle_color' => 'nullable|string|max:50',
            'brand_text' => 'nullable|string|max:255',
            'brand_text_color' => 'nullable|string|max:50',
            'overlay_color' => 'nullable|string|max:50',
            'overlay_opacity' => 'nullable|numeric|min:0|max:1',
            'text_position' => 'nullable|string|in:top,middle,bottom',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        // Handle image upload if new image is provided
        if ($request->hasFile('image')) {
            // Delete old image if it exists
            if ($heroSection->image_path) {
                $oldImagePath = public_path('storage/' . $heroSection->image_path);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
            
            // Store new image
            $image = $request->file('image');
            $imageName = time() . '_' . uniqid() . '_' . str_replace(' ', '_', $image->getClientOriginalName());
            $image->move(public_path('storage/hero-sections'), $imageName);
            $heroSection->image_path = 'hero-sections/' . $imageName;
        }
        
        // Update hero section data
        $heroSection->title = $request->title;
        $heroSection->subtitle = $request->subtitle;
        $heroSection->button_text = $request->button_text;
        $heroSection->button_link = $request->button_link;
        $heroSection->is_active = $request->has('is_active') ? 1 : 0;
        $heroSection->display_order = $request->display_order;
        $heroSection->text_color = $request->text_color;
        $heroSection->title_color = $request->title_color;
        $heroSection->subtitle_color = $request->subtitle_color;
        $heroSection->brand_text = $request->brand_text;
        $heroSection->brand_text_color = $request->brand_text_color ?? '#ffcb05';
        $heroSection->overlay_color = $request->overlay_color;
        $heroSection->overlay_opacity = $request->overlay_opacity;
        $heroSection->text_position = $request->text_position ?? 'bottom';
        
        $heroSection->save();
        
        return redirect()->route('admin.hero-sections.index')
            ->with('success', 'Hero section updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Check if user is authorized to manage hero sections
        if (Auth::user()->user_type_id != 1) {
            if (request()->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Unauthorized access'], 403);
            }
            return redirect()->back()->with('error', 'Unauthorized access');
        }
        
        $heroSection = HeroSection::findOrFail($id);
        
        // Delete image file if it exists
        if ($heroSection->image_path) {
            $imagePath = public_path('storage/' . $heroSection->image_path);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }
        
        $heroSection->delete();
        
        // Handle both AJAX and regular form submissions
        if (request()->expectsJson()) {
            return response()->json([
                'success' => true, 
                'message' => 'Hero section deleted successfully!'
            ]);
        }
        
        return redirect()->route('admin.hero-sections.index')
            ->with('success', 'Hero section deleted successfully!');
    }
    
    /**
     * Update the order of hero sections.
     */
    public function updateOrder(Request $request)
    {
        // Check if user is authorized to manage hero sections
        if (Auth::user()->user_type_id != 1) {
            return response()->json(['success' => false, 'message' => 'Unauthorized access'], 403);
        }
        
        $validator = Validator::make($request->all(), [
            'items' => 'required|array',
            'items.*.id' => 'required|exists:hero_sections,id',
            'items.*.order' => 'required|integer|min:0',
        ]);
        
        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }
        
        foreach ($request->items as $item) {
            HeroSection::where('id', $item['id'])->update(['display_order' => $item['order']]);
        }
        
        return response()->json(['success' => true, 'message' => 'Order updated successfully']);
    }
}
