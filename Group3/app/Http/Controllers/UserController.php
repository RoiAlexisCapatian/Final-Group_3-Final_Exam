<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function login(Request $request)
{
    $credentials = $request->only('username', 'password');

    // Log the login attempt
    \Log::info('Login attempt for username: ' . $credentials['username']);

    // Fetch user from database (Ensure the password is stored in plain text or another method)
    $user = DB::table('users')->where('username', $credentials['username'])->first();

    // Check if user exists and password matches
    if ($user && $user->password === $credentials['password']) {
        session(['user_id' => $user->userid]); // Store user ID in session

        return response()->json([
            'success' => true,
            'userid' => $user->userid,  // Return the user ID here
            'message' => 'Login successful!'
        ]);
    }

    // If login fails, return with error message
    return response()->json([
        'success' => false,
        'error' => 'Invalid credentials'
    ]);
}

}

