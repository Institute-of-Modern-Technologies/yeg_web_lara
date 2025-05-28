<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Happening;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class HappeningController extends Controller
{
    /**
     * Display a listing of the happenings.
     */
    public function index()
    {
        // Check if user is authorized
        if (Auth::user()->user_type_id != 1) {
            return redirect()->back()->with('error', 'Unauthorized access');
        }
        
        // Get all happenings ordered by display order
        $happenings = Happening::orderBy('display_order', 'asc')->get();
        
        return view('admin.happenings.index', compact('happenings'));
    }

    /**
     * Show the form for creating a new happening.
     */
    public function create()
    {
        // Check if user is authorized
        if (Auth::user()->user_type_id != 1) {
            return redirect()->back()->with('error', 'Unauthorized access');
        }
        
        return view('admin.happenings.create');
    }

    /**
     * Store a newly created happening in storage.
     */
    public function store(Request $request)
    {
        // Check if user is authorized
        if (Auth::user()->user_type_id != 1) {
            return redirect()->back()->with('error', 'Unauthorized access');
        }
        
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'author_name' => 'nullable|string|max:255',
            'published_date' => 'required|date',
            'category' => 'nullable|string|max:100',
            'media_type' => 'required|in:image,video',
            'media' => 'required|file|mimes:jpeg,png,jpg,gif,mp4,webm|max:10240',
            'is_active' => 'boolean',
            'display_order' => 'integer|min:0',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        // Handle media upload
        $mediaPath = null;
        if ($request->hasFile('media')) {
            $media = $request->file('media');
            $mediaName = time() . '_' . uniqid() . '_' . str_replace(' ', '_', $media->getClientOriginalName());
            $media->move(public_path('storage/happenings'), $mediaName);
            $mediaPath = 'happenings/' . $mediaName;
        }
        
        // Create new happening
        Happening::create([
            'title' => $request->title,
            'content' => $request->content,
            'author_name' => $request->author_name,
            'published_date' => $request->published_date,
            'category' => $request->category,
            'media_type' => $request->media_type,
            'media_path' => $mediaPath,
            'is_active' => $request->has('is_active'),
            'display_order' => $request->display_order ?? 0,
        ]);
        
        return redirect()->route('admin.happenings.index')
            ->with('success', 'Happening created successfully!');
    }

    /**
     * Show the form for editing the specified happening.
     */
    public function edit(string $id)
    {
        // Check if user is authorized
        if (Auth::user()->user_type_id != 1) {
            return redirect()->back()->with('error', 'Unauthorized access');
        }
        
        $happening = Happening::findOrFail($id);
        
        return view('admin.happenings.edit', compact('happening'));
    }

    /**
     * Update the specified happening in storage.
     */
    public function update(Request $request, string $id)
    {
        // Check if user is authorized
        if (Auth::user()->user_type_id != 1) {
            return redirect()->back()->with('error', 'Unauthorized access');
        }
        
        $happening = Happening::findOrFail($id);
        
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'author_name' => 'nullable|string|max:255',
            'published_date' => 'required|date',
            'category' => 'nullable|string|max:100',
            'media_type' => 'required|in:image,video',
            'media' => 'nullable|file|mimes:jpeg,png,jpg,gif,mp4,webm|max:10240',
            'is_active' => 'boolean',
            'display_order' => 'integer|min:0',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        // Handle media upload if new media is provided
        if ($request->hasFile('media')) {
            // Delete old media if it exists
            if ($happening->media_path) {
                $oldMediaPath = public_path('storage/' . $happening->media_path);
                if (file_exists($oldMediaPath)) {
                    unlink($oldMediaPath);
                }
            }
            
            // Store new media
            $media = $request->file('media');
            $mediaName = time() . '_' . uniqid() . '_' . str_replace(' ', '_', $media->getClientOriginalName());
            $media->move(public_path('storage/happenings'), $mediaName);
            $happening->media_path = 'happenings/' . $mediaName;
        }
        
        // Update happening data
        $happening->title = $request->title;
        $happening->content = $request->content;
        $happening->author_name = $request->author_name;
        $happening->published_date = $request->published_date;
        $happening->category = $request->category;
        $happening->media_type = $request->media_type;
        $happening->is_active = $request->has('is_active');
        $happening->display_order = $request->display_order ?? 0;
        
        $happening->save();
        
        return redirect()->route('admin.happenings.index')
            ->with('success', 'Happening updated successfully!');
    }

    /**
     * Remove the specified happening from storage.
     */
    public function destroy(string $id)
    {
        // Check if user is authorized
        if (Auth::user()->user_type_id != 1) {
            return redirect()->back()->with('error', 'Unauthorized access');
        }
        
        $happening = Happening::findOrFail($id);
        
        // Delete the media file if it exists
        if ($happening->media_path) {
            $mediaPath = public_path('storage/' . $happening->media_path);
            if (file_exists($mediaPath)) {
                unlink($mediaPath);
            }
        }
        
        $happening->delete();
        
        return redirect()->route('admin.happenings.index')
            ->with('success', 'Happening deleted successfully!');
    }

    /**
     * Update the display order of happenings.
     */
    public function updateOrder(Request $request)
    {
        // Check if user is authorized
        if (Auth::user()->user_type_id != 1) {
            return response()->json(['error' => 'Unauthorized access'], 403);
        }
        
        $happenings = $request->input('happenings', []);
        
        foreach ($happenings as $happening) {
            $happeningModel = Happening::find($happening['id']);
            if ($happeningModel) {
                $happeningModel->display_order = $happening['position'];
                $happeningModel->save();
            }
        }
        
        return response()->json(['success' => true]);
    }
}
