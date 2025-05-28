<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    /**
     * Display a listing of the events.
     */
    public function index()
    {
        // Check if user is authorized to manage events
        if (Auth::user()->user_type_id != 1) {
            return redirect()->back()->with('error', 'Unauthorized access');
        }
        
        // Get all events ordered by display order
        $events = Event::orderBy('display_order', 'asc')->get();
        
        return view('admin.events.index', compact('events'));
    }

    /**
     * Show the form for creating a new event.
     */
    public function create()
    {
        // Check if user is authorized to manage events
        if (Auth::user()->user_type_id != 1) {
            return redirect()->back()->with('error', 'Unauthorized access');
        }
        
        return view('admin.events.create');
    }

    /**
     * Store a newly created event in storage.
     */
    public function store(Request $request)
    {
        // Check if user is authorized to manage events
        if (Auth::user()->user_type_id != 1) {
            return redirect()->back()->with('error', 'Unauthorized access');
        }
        
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'media_type' => 'required|in:image,video',
            'media' => 'required|file|mimes:jpeg,png,jpg,gif,mp4,webm|max:10240',
            'duration' => 'nullable|string|max:50',
            'level' => 'nullable|string|max:50',
            'level_color' => 'nullable|string|max:50',
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
            $media->move(public_path('storage/events'), $mediaName);
            $mediaPath = 'events/' . $mediaName;
        }
        
        // Create new event
        Event::create([
            'title' => $request->title,
            'description' => $request->description,
            'media_type' => $request->media_type,
            'media_path' => $mediaPath,
            'duration' => $request->duration,
            'level' => $request->level,
            'level_color' => $request->level_color ?? '#ff00ff',
            'is_active' => $request->has('is_active'),
            'display_order' => $request->display_order ?? 0,
        ]);
        
        return redirect()->route('admin.events.index')
            ->with('success', 'Event created successfully!');
    }

    /**
     * Show the form for editing the specified event.
     */
    public function edit(string $id)
    {
        // Check if user is authorized to manage events
        if (Auth::user()->user_type_id != 1) {
            return redirect()->back()->with('error', 'Unauthorized access');
        }
        
        $event = Event::findOrFail($id);
        
        return view('admin.events.edit', compact('event'));
    }

    /**
     * Update the specified event in storage.
     */
    public function update(Request $request, string $id)
    {
        // Check if user is authorized to manage events
        if (Auth::user()->user_type_id != 1) {
            return redirect()->back()->with('error', 'Unauthorized access');
        }
        
        $event = Event::findOrFail($id);
        
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'media_type' => 'required|in:image,video',
            'media' => 'nullable|file|mimes:jpeg,png,jpg,gif,mp4,webm|max:10240',
            'duration' => 'nullable|string|max:50',
            'level' => 'nullable|string|max:50',
            'level_color' => 'nullable|string|max:50',
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
            if ($event->media_path) {
                $oldMediaPath = public_path('storage/' . $event->media_path);
                if (file_exists($oldMediaPath)) {
                    unlink($oldMediaPath);
                }
            }
            
            // Store new media
            $media = $request->file('media');
            $mediaName = time() . '_' . uniqid() . '_' . str_replace(' ', '_', $media->getClientOriginalName());
            $media->move(public_path('storage/events'), $mediaName);
            $event->media_path = 'events/' . $mediaName;
        }
        
        // Update event data
        $event->title = $request->title;
        $event->description = $request->description;
        $event->media_type = $request->media_type;
        $event->duration = $request->duration;
        $event->level = $request->level;
        $event->level_color = $request->level_color ?? '#ff00ff';
        $event->is_active = $request->has('is_active');
        $event->display_order = $request->display_order ?? 0;
        
        $event->save();
        
        return redirect()->route('admin.events.index')
            ->with('success', 'Event updated successfully!');
    }

    /**
     * Remove the specified event from storage.
     */
    public function destroy(string $id)
    {
        // Check if user is authorized to manage events
        if (Auth::user()->user_type_id != 1) {
            return redirect()->back()->with('error', 'Unauthorized access');
        }
        
        $event = Event::findOrFail($id);
        
        // Delete the media file if it exists
        if ($event->media_path) {
            $mediaPath = public_path('storage/' . $event->media_path);
            if (file_exists($mediaPath)) {
                unlink($mediaPath);
            }
        }
        
        $event->delete();
        
        return redirect()->route('admin.events.index')
            ->with('success', 'Event deleted successfully!');
    }

    /**
     * Update the display order of events.
     */
    public function updateOrder(Request $request)
    {
        // Check if user is authorized to manage events
        if (Auth::user()->user_type_id != 1) {
            return response()->json(['error' => 'Unauthorized access'], 403);
        }
        
        $events = $request->input('events', []);
        
        foreach ($events as $event) {
            $eventModel = Event::find($event['id']);
            if ($eventModel) {
                $eventModel->display_order = $event['position'];
                $eventModel->save();
            }
        }
        
        return response()->json(['success' => true]);
    }
}
