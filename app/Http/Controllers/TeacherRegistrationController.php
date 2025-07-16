<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TeacherRegistrationController extends Controller
{
    /**
     * Show the teacher registration form
     */
    public function showRegistrationForm()
    {
        return view('teachers.register');
    }
    
    /**
     * Process the teacher registration form submission
     */
    public function submitRegistration(Request $request)
    {
        // Validate the form data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'location' => 'required|string|max:255',
            'surrounding_areas' => 'nullable|string|max:255',
            'educational_background' => 'required|string',
            'relevant_experience' => 'required|string',
            'expertise_areas' => 'required|array|min:1',
            'other_expertise' => 'nullable|string|max:255',
            'program_applied' => 'required|string|in:partnered_schools,after_school,weekend,flexible',
            'preferred_locations' => 'required|array|min:1',
            'other_location' => 'nullable|string|max:255',
            'experience_teaching_kids' => 'required|boolean',
            'cv_status' => 'required|in:yes,no,will_send',
            'why_instructor' => 'required|string',
            'video_introduction' => 'nullable|file|mimes:mp4,avi,mov,wmv|max:102400', // 100MB max
            'confirmation_agreement' => 'required|accepted',
        ]);
        
        // Handle video upload if provided
        if ($request->hasFile('video_introduction')) {
            $videoPath = $request->file('video_introduction')->store('teacher_videos', 'public');
            $validated['video_introduction'] = $videoPath;
        }
        
        // Format the arrays for JSON storage
        $validated['expertise_areas'] = json_encode($request->expertise_areas);
        $validated['preferred_locations'] = json_encode($request->preferred_locations);
        
        // Create the teacher record
        $teacher = Teacher::create($validated);
        
        // Generate a reference number
        $referenceNumber = 'TR-' . str_pad($teacher->id, 4, '0', STR_PAD_LEFT);
        
        // Prepare session data for success page
        $sessionData = [
            'name' => $teacher->name,
            'email' => $teacher->email,
            'phone' => $teacher->phone,
            'program_applied' => $teacher->program_applied,
            'reference_number' => $referenceNumber
        ];
        
        // Redirect to success page with teacher data in session
        return redirect()->route('teacher.register.success')
            ->with('teacher', $sessionData)
            ->with('success', 'Your teacher registration has been submitted successfully!');
    }
    
    /**
     * Show the registration success page
     */
    public function showSuccessPage()
    {
        return view('teachers.register-success');
    }
}
