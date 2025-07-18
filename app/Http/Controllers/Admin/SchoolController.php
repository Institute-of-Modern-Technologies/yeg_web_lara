<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\School;
use App\Models\User;
use App\Models\UserType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SchoolController extends Controller
{
    /**
     * Display a listing of the schools.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = School::query();
        
        // Apply search if provided
        if ($request->filled('search')) {
            $searchTerm = '%' . $request->search . '%';
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', $searchTerm)
                  ->orWhere('email', 'like', $searchTerm)
                  ->orWhere('phone', 'like', $searchTerm)
                  ->orWhere('location', 'like', $searchTerm);
            });
        }
        
        $schools = $query->latest()->paginate(10);
        
        // Preserve search parameters in pagination
        $schools->appends($request->only('search'));
        
        return view('admin.schools.index', compact('schools'));
    }

    /**
     * Show the form for creating a new school.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.schools.create');
    }

    /**
     * Store a newly created school in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'location' => 'required|string|max:255',
            'gps_coordinates' => 'nullable|string|max:100',
            'owner_name' => 'required|string|max:255',
            'avg_students' => 'nullable|integer|min:1',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:pending,approved,rejected',
        ]);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('school-logos', 'public');
            $validated['logo'] = $logoPath;
        }

        // Create the school
        $school = School::create($validated);

        return redirect()->route('admin.schools.index')
            ->with('success', 'School created successfully.');
    }

    /**
     * Show the form for editing the specified school.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $school = School::findOrFail($id);
        return view('admin.schools.edit', compact('school'));
    }

    /**
     * Update the specified school in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $school = School::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'location' => 'required|string|max:255',
            'gps_coordinates' => 'nullable|string|max:100',
            'owner_name' => 'required|string|max:255',
            'avg_students' => 'nullable|integer|min:1',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:pending,approved,rejected',
        ]);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($school->logo && Storage::disk('public')->exists($school->logo)) {
                Storage::disk('public')->delete($school->logo);
            }
            
            $logoPath = $request->file('logo')->store('school-logos', 'public');
            $validated['logo'] = $logoPath;
        }

        // Update the school
        $school->update($validated);

        return redirect()->route('admin.schools.index')
            ->with('success', 'School updated successfully.');
    }

    /**
     * Remove the specified school from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $school = School::findOrFail($id);
        
        // Delete logo if exists
        if ($school->logo && Storage::disk('public')->exists($school->logo)) {
            Storage::disk('public')->delete($school->logo);
        }
        
        $school->delete();

        return redirect()->route('admin.schools.index')
            ->with('success', 'School deleted successfully.');
    }

    /**
     * Update school status.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateStatus(Request $request, $id)
    {
        $school = School::findOrFail($id);
        
        $request->validate([
            'status' => 'required|in:pending,approved,rejected',
        ]);
        
        $school->update([
            'status' => $request->status,
        ]);
        
        // If school is approved, create a user account for the school
        if ($request->status === 'approved') {
            try {
                // Get or create school admin user type
                $schoolAdminType = UserType::firstOrCreate(
                    ['slug' => 'school_admin'],
                    ['name' => 'School Admin', 'description' => 'School administrator account']
                );
                
                // Create username from school name
                $baseUsername = Str::slug($school->name);
                $username = $baseUsername;
                $counter = 1;
                
                // Check if username exists and generate a unique one if needed
                while (User::where('username', $username)->exists()) {
                    $username = $baseUsername . $counter++;
                }
                
                // Create the user - generate a placeholder email if none exists
                $email = $school->email;
                if (!$email) {
                    // Generate a placeholder email using the username
                    $email = $username . '@placeholder.yeg.edu';
                }
                
                User::create([
                    'name' => $school->name,
                    'email' => $email,
                    'username' => $username,
                    'password' => Hash::make('school123'),
                    'user_type_id' => $schoolAdminType->id
                ]);
                
                \Log::info('User account created for school: ' . $username);
            } catch (\Exception $e) {
                \Log::error('Failed to create user account for school: ' . $e->getMessage());
                // Continue with school approval even if user creation fails
            }
        }
        
        return redirect()->route('admin.schools.index')
            ->with('success', 'School status updated successfully.');
    }
}
