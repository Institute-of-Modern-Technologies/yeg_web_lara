<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use Illuminate\Http\Request;

class TrainerController extends Controller
{
    /**
     * Display a listing of the trainers.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $trainers = Teacher::orderBy('created_at', 'desc')->get();
        return view('admin.trainers.index', compact('trainers'));
    }

    /**
     * Show the form for creating a new trainer.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.trainers.create');
    }

    /**
     * Store a newly created trainer in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:teachers,email',
            'phone' => 'required|string|max:20',
            'location' => 'required|string|max:255',
            'surrounding_areas' => 'nullable|string|max:255',
            'educational_background' => 'required|string',
            'relevant_experience' => 'required|string',
            'expertise_areas' => 'required|array',
            'other_expertise' => 'nullable|string|max:255',
            'program_applied' => 'required|string|max:255',
            'preferred_locations' => 'required|array',
            'other_location' => 'nullable|string|max:255',
            'experience_teaching_kids' => 'required|boolean',
            'cv_status' => 'required|in:yes,no,will_send',
            'why_instructor' => 'required|string',
            'video_introduction' => 'nullable|file|mimes:mp4,mov,avi|max:50000',
            'confirmation_agreement' => 'required|boolean',
            'status' => 'required|in:pending,approved,rejected',
        ]);

        // Handle array fields if they're empty
        if (!isset($validated['expertise_areas'])) {
            $validated['expertise_areas'] = [];
        }
        
        if (!isset($validated['preferred_locations'])) {
            $validated['preferred_locations'] = [];
        }
        
        // Handle file upload
        if ($request->hasFile('video_introduction')) {
            $file = $request->file('video_introduction');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/videos'), $fileName);
            $validated['video_introduction'] = 'uploads/videos/' . $fileName;
        }
        
        // Convert array fields to JSON for storage
        $validated['expertise_areas'] = json_encode($validated['expertise_areas']);
        $validated['preferred_locations'] = json_encode($validated['preferred_locations']);

        Teacher::create($validated);
        
        return redirect()->route('admin.trainers.index')
            ->with('success', 'Trainer created successfully');
    }

    /**
     * Display the specified trainer.
     *
     * @param  \App\Models\Teacher  $trainer
     * @return \Illuminate\View\View
     */
    public function show(Teacher $trainer)
    {
        return view('admin.trainers.show', compact('trainer'));
    }

    /**
     * Show the form for editing the specified trainer.
     *
     * @param  \App\Models\Teacher  $trainer
     * @return \Illuminate\View\View
     */
    public function edit(Teacher $trainer)
    {
        return view('admin.trainers.edit', compact('trainer'));
    }

    /**
     * Update the specified trainer in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Teacher  $trainer
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Teacher $trainer)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:teachers,email,' . $trainer->id,
            'phone' => 'required|string|max:20',
            'location' => 'required|string|max:255',
            'surrounding_areas' => 'nullable|string|max:255',
            'educational_background' => 'required|string',
            'relevant_experience' => 'required|string',
            'expertise_areas' => 'required|array',
            'other_expertise' => 'nullable|string|max:255',
            'program_applied' => 'required|string|max:255',
            'preferred_locations' => 'required|array',
            'other_location' => 'nullable|string|max:255',
            'experience_teaching_kids' => 'required|boolean',
            'cv_status' => 'required|in:yes,no,will_send',
            'why_instructor' => 'required|string',
            'video_introduction' => 'nullable|file|mimes:mp4,mov,avi|max:50000',
            'confirmation_agreement' => 'required|boolean',
            'status' => 'required|in:pending,approved,rejected',
        ]);

        // Handle array fields if they're empty
        if (!isset($validated['expertise_areas'])) {
            $validated['expertise_areas'] = [];
        }
        
        if (!isset($validated['preferred_locations'])) {
            $validated['preferred_locations'] = [];
        }
        
        // Handle file upload
        if ($request->hasFile('video_introduction')) {
            $file = $request->file('video_introduction');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/videos'), $fileName);
            
            // Delete old video if exists
            if ($trainer->video_introduction && file_exists(public_path($trainer->video_introduction))) {
                unlink(public_path($trainer->video_introduction));
            }
            
            $validated['video_introduction'] = 'uploads/videos/' . $fileName;
        }
        
        // Convert array fields to JSON for storage
        $validated['expertise_areas'] = json_encode($validated['expertise_areas']);
        $validated['preferred_locations'] = json_encode($validated['preferred_locations']);
        
        $trainer->update($validated);
        
        return redirect()->route('admin.trainers.index')
            ->with('success', 'Trainer updated successfully');
    }

    /**
     * Remove the specified trainer from storage.
     *
     * @param  \App\Models\Teacher  $trainer
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Teacher $trainer)
    {
        $trainer->delete();
        
        return redirect()->route('admin.trainers.index')
            ->with('success', 'Trainer deleted successfully');
    }

    /**
     * Update the status of a trainer.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Teacher  $trainer
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateStatus(Request $request, Teacher $trainer)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,approved,rejected',
        ]);

        $trainer->update([
            'status' => $validated['status']
        ]);

        $statusMessages = [
            'pending' => 'Trainer has been marked as pending review.',
            'approved' => 'Trainer has been approved successfully.',
            'rejected' => 'Trainer has been rejected.'
        ];

        return redirect()->route('admin.trainers.index')
            ->with('success', $statusMessages[$validated['status']]);
    }
}
