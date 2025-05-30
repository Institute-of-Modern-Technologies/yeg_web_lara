<?php

namespace App\Http\Controllers;

use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SchoolRegistrationController extends Controller
{
    /**
     * Show the school registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm()
    {
        return view('schools.register');
    }

    /**
     * Register a new school.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
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
        ]);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('school-logos', 'public');
            $validated['logo'] = $logoPath;
        }

        // Set default status for public registrations
        $validated['status'] = 'pending';

        // Create the school
        $school = School::create($validated);

        return redirect()->route('school.register.success')->with('success', 'School registration submitted successfully. Your application is now pending approval.');
    }

    /**
     * Show registration success page.
     *
     * @return \Illuminate\Http\Response
     */
    public function showSuccess()
    {
        return view('schools.register-success');
    }
}
