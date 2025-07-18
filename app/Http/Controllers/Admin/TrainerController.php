<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\User;
use App\Models\UserType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class TrainerController extends Controller
{
    /**
     * Display a listing of the trainers.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = Teacher::query();
        
        // Apply search filter
        if ($request->filled('search')) {
            $searchTerm = '%' . $request->search . '%';
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', $searchTerm)
                  ->orWhere('email', 'like', $searchTerm)
                  ->orWhere('phone', 'like', $searchTerm);
            });
        }
        
        // Apply status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Get results ordered by most recent
        $trainers = $query->orderBy('created_at', 'desc')->get();
        
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
        try {
            $validated = $request->validate([
                'status' => 'required|in:pending,approved,rejected',
            ]);
    
            $oldStatus = $trainer->status;
            $newStatus = $validated['status'];
            
            $trainer->update([
                'status' => $newStatus
            ]);
    
            $statusMessages = [
                'pending' => 'Trainer has been marked as pending review.',
                'approved' => 'Trainer has been approved successfully.',
                'rejected' => 'Trainer has been rejected.'
            ];
            
            // If trainer is being approved for the first time, create a user account
            if ($newStatus === 'approved' && $oldStatus !== 'approved') {
                $user = $this->createTrainerUser($trainer);
                if ($user) {
                    $this->sendApprovalNotification($trainer, $user);
                }
            }
    
            return redirect()->route('admin.trainers.index')
                ->with('success', $statusMessages[$validated['status']]);
                
        } catch (\Exception $e) {
            Log::error('Failed to update trainer status: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to update trainer status: ' . $e->getMessage());
        }
    }
    
    /**
     * Create a user account for a trainer
     * 
     * @param  \App\Models\Teacher  $trainer
     * @return \App\Models\User|null
     */
    private function createTrainerUser($trainer)
    {
        // Don't create user account if email is missing
        if (empty($trainer->email)) {
            return null;
        }
        
        // Check if user already exists with this email
        $existingUser = User::where('email', $trainer->email)->first();
        if ($existingUser) {
            return $existingUser;
        }
        
        try {
            // Get or create trainer user type
            $trainerUserType = UserType::firstOrCreate(
                ['slug' => 'trainer'],
                ['name' => 'Trainer', 'description' => 'Trainer account with teaching privileges']
            );
            
            // Create username from name
            $nameParts = explode(' ', $trainer->name);
            $firstName = $nameParts[0] ?? '';
            $baseUsername = strtolower($firstName ?: $trainer->name);
            $username = $baseUsername;
            $counter = 1;
            
            // Check if username exists
            while (User::where('username', $username)->exists()) {
                $username = $baseUsername . $counter++;
            }
            
            // Generate a random password
            $password = Str::random(8);
            
            // Create user account
            $user = User::create([
                'name' => $trainer->name,
                'email' => $trainer->email,
                'username' => $username,
                'password' => Hash::make($password),
                'user_type_id' => $trainerUserType->id
            ]);
            
            // Store the plaintext password temporarily to include in notification
            $user->temp_password = $password;
            
            return $user;
            
        } catch (\Exception $e) {
            Log::error('Failed to create user for trainer: ' . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Send approval notification with login credentials to trainer
     * 
     * @param  \App\Models\Teacher  $trainer
     * @param  \App\Models\User  $user
     * @return void
     */
    private function sendApprovalNotification($trainer, $user)
    {
        // This method will be implemented when email functionality is ready
        // For now, we'll just log that this would send an email
        Log::info('Approval notification would be sent to trainer: ' . $trainer->email);
        Log::info('Username: ' . $user->username . ', Temporary Password: ' . $user->temp_password);
        
        // In a real implementation, you would send an email with credentials:
        // Mail::to($trainer->email)->send(new TrainerApprovalMail($trainer, $user));
    }
    
    /**
     * Create user accounts for all existing approved trainers that don't have accounts yet
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createAccountsForApprovedTrainers()
    {
        try {
            // Get all approved trainers
            $approvedTrainers = Teacher::where('status', 'approved')->get();
            
            $created = 0;
            $skipped = 0;
            
            foreach ($approvedTrainers as $trainer) {
                // Check if trainer email exists and is not already a user
                if (!empty($trainer->email)) {
                    $existingUser = User::where('email', $trainer->email)->first();
                    
                    if (!$existingUser) {
                        // Create user account
                        $user = $this->createTrainerUser($trainer);
                        if ($user) {
                            $created++;
                            // Send notification
                            $this->sendApprovalNotification($trainer, $user);
                        }
                    } else {
                        $skipped++;
                    }
                } else {
                    $skipped++;
                }
            }
            
            $message = "Action completed: {$created} user accounts created";
            if ($skipped > 0) {
                $message .= ", {$skipped} skipped (already have accounts or missing email)";
            }
            
            return redirect()->route('admin.trainers.index')
                ->with('success', $message);
                
        } catch (\Exception $e) {
            Log::error('Failed to create accounts for approved trainers: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to create accounts: ' . $e->getMessage());
        }
    }
}
