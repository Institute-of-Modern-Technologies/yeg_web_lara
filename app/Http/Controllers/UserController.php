<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    /**
     * Display a listing of users.
     */
    public function index()
    {
        // Check if user is authorized to manage users
        if (Auth::user()->user_type_id != 1) {
            return redirect()->back()->with('error', 'Unauthorized access');
        }
        
        $users = User::all();
        return view('admin.users.index', compact('users'));
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        try {
            // Check if user is authorized to create users
            if (Auth::user()->user_type_id != 1) {
                return response()->json(['success' => false, 'message' => 'Unauthorized access'], 403);
            }

            // Log incoming request data for debugging
            Log::info('User creation request received', ['request_data' => $request->all()]);

            // Validate the request data
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'username' => 'required|string|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
                'user_type_id' => 'required|integer|in:1,2,3',
            ]);

            if ($validator->fails()) {
                Log::warning('User creation validation failed', ['errors' => $validator->errors()->toArray()]);
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }

            // Create the user
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'user_type_id' => $request->user_type_id,
            ]);

            Log::info('User created successfully', ['user_id' => $user->id]);
            
            return response()->json([
                'success' => true, 
                'message' => 'User created successfully!',
                'user' => $user
            ]);
        } catch (\Exception $e) {
            Log::error('Error creating user', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while creating the user: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get the specified user for editing.
     */
    public function edit(User $user)
    {
        // Check if user is authorized to edit users
        if (Auth::user()->user_type_id != 1) {
            return response()->json(['success' => false, 'message' => 'Unauthorized access'], 403);
        }

        try {
            // Return the user data for editing
            return response()->json([
                'success' => true,
                'user' => $user
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching user for editing', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching the user data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        // Check if user is authorized to update users
        if (Auth::user()->user_type_id != 1) {
            return response()->json(['success' => false, 'message' => 'Unauthorized access'], 403);
        }

        // Validate the request data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'user_type_id' => 'required|integer|in:1,2,3',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        // Update the user
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'username' => $request->username,
            'user_type_id' => $request->user_type_id,
        ]);

        // Update password if provided
        if ($request->filled('password')) {
            $validator = Validator::make($request->all(), [
                'password' => 'required|string|min:8|confirmed',
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }

            $user->update([
                'password' => Hash::make($request->password),
            ]);
        }

        return response()->json([
            'success' => true, 
            'message' => 'User updated successfully!',
            'user' => $user
        ]);
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        // Check if user is authorized to delete users
        if (Auth::user()->user_type_id != 1) {
            return response()->json(['success' => false, 'message' => 'Unauthorized access'], 403);
        }

        // Don't allow users to delete themselves
        if (Auth::id() === $user->id) {
            return response()->json(['success' => false, 'message' => 'You cannot delete your own account'], 400);
        }

        $user->delete();

        return response()->json([
            'success' => true, 
            'message' => 'User deleted successfully!'
        ]);
    }
}
