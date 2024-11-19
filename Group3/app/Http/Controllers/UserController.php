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

    public function showDashboard()
    {
        // Fetching all data from the users table without any restrictions
        $admins = DB::table('users')
                    ->select('*')
                    ->where('usertype', 'Standard')
                    ->get();

        // Passing data to the view
        return view('dashboard', compact('admins'));
    }

 public function getUser($userid)
{
    // Fetch user data from 'users' table
    $user = DB::table('users')
                ->where('userid', $userid)
                ->first();

    if ($user) {
        // Check if the 'userid' exists in the 'resume' table
        $resume = DB::table('resume')
                    ->where('userid', $userid)
                    ->first();

        // If no record found in 'resume', insert a new record with fullname as username
        if (!$resume) {
            DB::table('resume')->insert([
                'userid' => $userid,
                'fullname' => $user->username, // Set fullname as the username from the 'users' table
                'objective' => null, // Initialize objective as null
                'professional_skills' => null, // Initialize professional_skills as null
            ]);
        }

        // Retrieve the user's data, joining 'users' and 'resume' tables
        $userWithResume = DB::table('users')
                            ->leftJoin('resume', 'users.userid', '=', 'resume.userid')
                            ->where('users.userid', $userid)
                            ->select(
                                'users.userid',
                                'users.username',
                                DB::raw('IFNULL(resume.fullname, users.username) as fullname'),
                                'resume.objective', // Include objective in the result
                                'resume.professional_skills'
                            )
                            ->first();

        // Decode the professional_skills if it is a JSON string
        if ($userWithResume->professional_skills) {
            $userWithResume->professional_skills = json_decode($userWithResume->professional_skills, true);  // Convert JSON string to array
        }

        // Return the data to the client
        return response()->json($userWithResume);
    }

    // If no user found in 'users' table
    return response()->json(['error' => 'User not found'], 404);
}





















    public function updateUser(Request $request)
    {
        // Validate incoming request
        $validated = $request->validate([
            'userid' => 'required|integer',
            'fullname' => 'required|string|max:255',
            'objective' => 'nullable|string|max:255',
        ]);
    
        // Get user data
        $userid = $validated['userid'];
        $fullname = $validated['fullname'];
        $objective = $validated['objective'];
    
        // Save the new fullname (assuming you want to save it to the resume table)
        $user = DB::table('resume')
                    ->where('userid', $userid)
                    ->first();
    
        if ($user) {
            // Update the user's fullname in the resume table
            DB::table('resume')
                ->where('userid', $userid)
                ->update(['fullname' => $fullname,'objective' => $objective,]);
                
    
            // Return a success message
            return response()->json(['success' => true, 'message' => 'User updated successfully']);
        }
    
        return response()->json(['success' => false, 'message' => 'User not found']);
    }
    

}

