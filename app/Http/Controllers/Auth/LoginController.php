<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\UserType;

class LoginController extends Controller
{
    /**
     * Show the login form
     */
    public function showLoginForm()
    {
        // If user is already logged in, redirect to appropriate dashboard
        if (Auth::check()) {
            $user = Auth::user();
            $userType = UserType::find($user->user_type_id);
            
            if ($userType) {
                switch ($userType->slug) {
                    case 'super_admin':
                        return redirect('/admin/dashboard');
                    case 'school_admin':
                        return redirect('/school/dashboard');
                    case 'student':
                        return redirect('/student/dashboard');
                    default:
                        // For unrecognized user types, default to welcome page
                        return redirect('/');
                }
            }
        }

        return view('auth.login');
    }

    /**
     * Handle a login request to the application.
     */
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // Find the user by username first
        $user = User::where('username', $request->username)->first();
        
        if (!$user) {
            return back()->with('error', 'The provided credentials do not match our records');
        }
        
        // Attempt to authenticate the user with remember me functionality
        if (Auth::attempt([
            'username' => $request->username,
            'password' => $request->password,
        ], $request->has('remember'))) {
            // Force the session to be regenerated and kept alive longer
            $request->session()->regenerate();
            
            // Get the authenticated user with their user type
            $user = Auth::user();
            $userType = UserType::find($user->user_type_id);
            
            if (!$userType) {
                Auth::logout();
                return back()->with('error', 'Your account has not been properly configured. Please contact an administrator.');
            }

            // Redirect based on user type
            switch ($userType->slug) {
                case 'super_admin':
                    return redirect()->intended('/admin/dashboard');
                case 'school_admin':
                    return redirect()->intended('/school/dashboard');
                case 'student':
                    return redirect()->intended('/student/dashboard');
                default:
                    return redirect()->intended('/');
            }
        }

        return back()->with('error', 'The provided credentials do not match our records');
    }

    /**
     * Log the user out of the application.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
