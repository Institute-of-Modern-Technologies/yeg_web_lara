<?php

namespace App\Http\Controllers\School;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use App\Models\School;

class ProfileController extends Controller
{
    /**
     * Constructor - Simple auth check
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Get the current school for the authenticated user
     */
    private function getCurrentSchool()
    {
        $user = Auth::user();
        
        // First check session
        if (session('school_id')) {
            return School::find(session('school_id'));
        }
        
        // Fallback to email lookup
        $school = School::where('email', $user->email)->first();
        
        if ($school) {
            // Store in session for future requests
            session(['school_id' => $school->id, 'school_name' => $school->name]);
            return $school;
        }
        
        return null;
    }

    /**
     * Show the profile page
     */
    public function show()
    {
        $user = Auth::user();
        $school = $this->getCurrentSchool();
        
        if (!$school) {
            Auth::logout();
            return redirect()->route('login')->with('error', 'School account not found.');
        }

        return view('school.profile.show', compact('user', 'school'));
    }

    /**
     * Update the profile
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        $school = $this->getCurrentSchool();
        
        if (!$school) {
            return redirect()->route('login')->with('error', 'School account not found.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($user->id)],
            'school_name' => ['required', 'string', 'max:255'],
            'school_phone' => ['nullable', 'string', 'max:20'],
            'school_address' => ['nullable', 'string', 'max:500'],
            'profile_photo' => ['nullable', 'image', 'max:1024'], // Max 1MB
            'current_password' => ['nullable', 'required_with:password', function ($attribute, $value, $fail) use ($user) {
                if (!Hash::check($value, $user->password)) {
                    $fail('The current password is incorrect.');
                }
            }],
            'password' => ['nullable', 'min:8', 'confirmed'],
        ]);

        try {
            // Update user info
            $user->name = $validated['name'];
            $user->email = $validated['email'];
            $user->username = $validated['username'];

            // Handle profile photo upload
            if ($request->hasFile('profile_photo')) {
                // Delete old photo if it exists
                if ($user->profile_photo && $user->profile_photo != 'default-profile.png') {
                    Storage::disk('public')->delete('profile-photos/' . $user->profile_photo);
                }
                
                // Store the new photo with a unique filename
                $file = $request->file('profile_photo');
                $originalName = $file->getClientOriginalName();
                // Replace spaces with underscores to avoid URL encoding issues
                $cleanName = str_replace(' ', '_', pathinfo($originalName, PATHINFO_FILENAME));
                $extension = $file->getClientOriginalExtension();
                $filename = $cleanName . '_' . time() . '.' . $extension;
                
                $file->storeAs('profile-photos', $filename, 'public');
                $user->profile_photo = $filename;
            }

            // Update password if provided
            if (!empty($validated['password'])) {
                $user->password = Hash::make($validated['password']);
            }

            $user->save();

            // Update school info
            $school->name = $validated['school_name'];
            $school->email = $validated['email']; // Keep school email in sync with user email
            $school->phone = $validated['school_phone'];
            $school->address = $validated['school_address'];
            $school->save();

            // Update session with new school name
            session(['school_name' => $school->name]);

            return redirect()->route('school.profile')->with('success', 'Profile updated successfully!');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update profile: ' . $e->getMessage())->withInput();
        }
    }
}
