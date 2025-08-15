<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        return view('admin.profile.show', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'profile_photo' => ['nullable', 'image', 'max:1024'], // Max 1MB
            'current_password' => ['nullable', 'required_with:password', function ($attribute, $value, $fail) use ($user) {
                if (!Hash::check($value, $user->password)) {
                    $fail('The current password is incorrect.');
                }
            }],
            'password' => ['nullable', 'min:8', 'confirmed'],
        ]);

        // Update basic info
        $user->name = $validated['name'];
        $user->email = $validated['email'];

        // Handle profile photo upload
        if ($request->hasFile('profile_photo')) {
            // Delete old photo if it exists
            if ($user->profile_photo && $user->profile_photo != 'default-profile.png') {
                $oldPath = public_path('uploads/profile-photos/' . $user->profile_photo);
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                    \Log::info('Deleted old admin profile photo: ' . $user->profile_photo);
                }
            }
            
            // Store the new photo with a unique filename
            $file = $request->file('profile_photo');
            $originalName = $file->getClientOriginalName();
            // Replace spaces with underscores to avoid URL encoding issues
            $safeFileName = str_replace(' ', '_', $originalName);
            $filename = time() . '_' . $safeFileName;
            // Create directory if it doesn't exist
            $directory = public_path('uploads/profile-photos');
            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
                \Log::info('Created profile-photos directory in public');
            }
            
            // Move the file directly to public directory
            $destination = public_path('uploads/profile-photos/' . $filename);
            if ($file->move(dirname($destination), $filename)) {
                $user->profile_photo = $filename;
                \Log::info('Admin profile photo stored successfully in public: ' . $filename);
            } else {
                \Log::error('Failed to store admin profile photo');
                return redirect()->back()->with('error', 'Failed to upload profile photo. Please try again.');
            }
        }

        // Update password if provided
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->route('admin.dashboard')->with('success', 'Profile updated successfully!');
    }
}
