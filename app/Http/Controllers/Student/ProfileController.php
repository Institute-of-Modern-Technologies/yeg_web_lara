<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use App\Models\Student;

class ProfileController extends Controller
{
    // Middleware is applied via routes, no constructor needed

    /**
     * Get the current student for the authenticated user
     */
    private function getCurrentStudent()
    {
        $user = Auth::user();
        
        // Find student by email
        $student = Student::where('email', $user->email)->first();
        
        return $student;
    }

    /**
     * Show the profile page
     */
    public function show()
    {
        $user = Auth::user();
        $student = $this->getCurrentStudent();
        
        if (!$student) {
            return redirect()->route('student.dashboard')->with('error', 'Student profile not found.');
        }

        return view('student.profile.show', compact('user', 'student'));
    }

    /**
     * Update the profile
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        $student = $this->getCurrentStudent();
        
        if (!$student) {
            return redirect()->route('student.dashboard')->with('error', 'Student profile not found.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'username' => ['nullable', 'string', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:20'],
            'city' => ['nullable', 'string', 'max:255'],
            'date_of_birth' => ['nullable', 'date'],
            'profile_photo' => ['nullable', 'image', 'max:10240'], // Max 10MB
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
            
            if (!empty($validated['username'])) {
                $user->username = $validated['username'];
            }

            // Handle profile photo upload
            if ($request->hasFile('profile_photo')) {
                $file = $request->file('profile_photo');
                \Log::info('Profile photo upload attempt', [
                    'user_id' => $user->id,
                    'file_name' => $file->getClientOriginalName(),
                    'file_size' => $file->getSize(),
                    'file_type' => $file->getMimeType()
                ]);
                
                // Validate the file
                if ($file->isValid()) {
                    // Delete old photo if it exists
                    if ($user->profile_photo && $user->profile_photo != 'default-profile.png') {
                        $oldPath = public_path('uploads/profile-photos/' . $user->profile_photo);
                        if (file_exists($oldPath)) {
                            unlink($oldPath);
                            \Log::info('Deleted old profile photo: ' . $user->profile_photo);
                        }
                    }
                    
                    // Create directory if it doesn't exist
                    $directory = public_path('uploads/profile-photos');
                    if (!file_exists($directory)) {
                        mkdir($directory, 0755, true);
                        \Log::info('Created profile-photos directory in public');
                    }
                    
                    // Store the new photo with a unique filename
                    $originalName = $file->getClientOriginalName();
                    // Replace spaces with underscores to avoid URL encoding issues
                    $cleanName = str_replace(' ', '_', pathinfo($originalName, PATHINFO_FILENAME));
                    $extension = $file->getClientOriginalExtension();
                    $filename = $cleanName . '_' . time() . '.' . $extension;
                    
                    \Log::info('Attempting to store file as: ' . $filename);
                    
                    // Move the file directly to public directory
                    $destination = public_path('uploads/profile-photos/' . $filename);
                    if ($file->move(dirname($destination), $filename)) {
                        $user->profile_photo = $filename;
                        \Log::info('Profile photo stored successfully in public: ' . $filename);
                    } else {
                        \Log::error('Failed to store profile photo');
                        throw new \Exception('Failed to store profile photo');
                    }
                } else {
                    \Log::error('Invalid file upload', ['error' => $file->getError()]);
                    throw new \Exception('Invalid file upload: ' . $file->getErrorMessage());
                }
            } else {
                \Log::info('No profile photo file in request');
            }

            // Update password if provided
            if (!empty($validated['password'])) {
                $user->password = Hash::make($validated['password']);
            }

            // Save user data
            $userSaved = $user->save();
            
            // Refresh user data to ensure we have the latest values
            $user->refresh();
            
            \Log::info('User save result', [
                'user_id' => $user->id,
                'saved' => $userSaved,
                'profile_photo' => $user->profile_photo,
                'profile_photo_after_refresh' => $user->fresh()->profile_photo
            ]);

            // Update student info
            $student->phone = $validated['phone'];
            $student->city = $validated['city'];
            $student->email = $validated['email']; // Keep student email in sync with user email
            
            if (!empty($validated['date_of_birth'])) {
                $student->date_of_birth = $validated['date_of_birth'];
                $student->age = \Carbon\Carbon::parse($validated['date_of_birth'])->age;
            }
            
            // Update full name if name changed
            $student->full_name = $validated['name'];
            $nameParts = explode(' ', $validated['name'], 2);
            $student->first_name = $nameParts[0];
            $student->last_name = $nameParts[1] ?? '';
            
            $studentSaved = $student->save();
            \Log::info('Student save result', [
                'student_id' => $student->id,
                'saved' => $studentSaved
            ]);

            return redirect()->route('student.profile')->with('success', 'Profile updated successfully!');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update profile: ' . $e->getMessage())->withInput();
        }
    }
}
