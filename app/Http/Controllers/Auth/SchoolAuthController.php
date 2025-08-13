<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\School;

class SchoolAuthController extends Controller
{
    /**
     * Show the school login form
     */
    public function showLoginForm()
    {
        return view('auth.school-login');
    }

    /**
     * Handle school login attempt
     */
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // Find user by username
        $user = User::where('username', $request->username)->first();

        if (!$user) {
            return back()->withErrors([
                'username' => 'Invalid username or password.',
            ])->withInput($request->only('username'));
        }

        // Check password
        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors([
                'username' => 'Invalid username or password.',
            ])->withInput($request->only('username'));
        }

        // Check if user is associated with a school
        $school = School::where('email', $user->email)->first();
        if (!$school) {
            return back()->withErrors([
                'username' => 'No school account found for this user.',
            ])->withInput($request->only('username'));
        }

        // Log the user in
        Auth::login($user, $request->filled('remember'));

        // Store school info in session for easy access
        session(['school_id' => $school->id, 'school_name' => $school->name]);

        return redirect()->intended(route('school.dashboard'));
    }

    /**
     * Handle school logout
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('school.login');
    }
}
