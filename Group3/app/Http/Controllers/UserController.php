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
                'certifications' => null, // Initialize certifications as null
                'skills' => null, // Initialize certifications as null
                'education' => null, // Initialize certifications as null
                'work_history' => null, // Initialize certifications as null
                'email' => null,  // Initialize email as null
                'address' => null,  // Initialize address as null
                'birthdate' => null,  // Initialize birthdate as null
                'phone' => null,  // Initialize phone as null
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
                                'resume.professional_skills',
                                'resume.certifications',
                                'resume.skills',
                                'resume.education',
                                'resume.work_history',
                                'resume.email',
                                'resume.address',
                                'resume.birthdate',
                                'resume.phone',
                            )
                            ->first();

        // Decode the professional_skills if it is a JSON string
        if ($userWithResume->professional_skills) {
            $userWithResume->professional_skills = json_decode($userWithResume->professional_skills, true);  // Convert JSON string to array
        } else {
            $userWithResume->professional_skills = [];  // If no skills, set as empty array
        }

        if ($userWithResume->certifications) {
            $userWithResume->certifications = json_decode($userWithResume->certifications, true);  // Convert JSON string to array
        } else {
            $userWithResume->certifications = [];  // If no certifications, set as empty array
        }

        if ($userWithResume->skills) {
            $userWithResume->skills = json_decode($userWithResume->skills, true);  // Convert JSON string to array
        } else {
            $userWithResume->skills = [];  // If no skills, set as empty array
        }

        if ($userWithResume->education) {
            $userWithResume->education = json_decode($userWithResume->education, true);  // Convert JSON string to array
        } else {
            $userWithResume->education = [];  // If no education, set as empty array
        }
        
        if ($userWithResume->work_history) {
            $userWithResume->work_history = json_decode($userWithResume->work_history, true);  // Convert JSON string to array
        } else {
            $userWithResume->work_history = [];  // If no work_history, set as empty array
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
        'address' => 'nullable|string|max:255',
        'birthdate' => 'nullable|string|max:255',
        'phone' => 'nullable|string|max:11',
        'email' => 'nullable|string|email|max:255',
        'objective' => 'nullable|string|max:255',
        'professional_skills' => 'nullable|array',
        'certifications' => 'nullable|array',
        'skills' => 'nullable|array',
        'education' => 'nullable|array',
        'work_history' => 'nullable|array',
    ]);

    // Get user data
    $userid = $validated['userid'];
    $fullname = $validated['fullname'];
    $address  = $validated['address'];
    $birthdate  = $validated['birthdate'];
    $phone  = $validated['phone'];
    $email  = $validated['email'];
    $objective = $validated['objective'];
    $professionalSkills = $validated['professional_skills']; 
    $certifications=$validated['certifications'];
    $skills=$validated['skills'];
    $education=$validated['education'];
    $work_history=$validated['work_history'];

    // Save the new fullname (assuming you want to save it to the resume table)
    $user = DB::table('resume')
                ->where('userid', $userid)
                ->first();

    if ($user) {
        // Update the user's fullname in the resume table
        DB::table('resume')
            ->where('userid', $userid)
            ->update([
            'fullname' => $fullname,
            'address' => $address,
            'birthdate' => $birthdate,
            'phone' => $phone,
            'email' => $email,
            'objective' => $objective,
            'professional_skills' => json_encode($professionalSkills),
            'certifications' => json_encode($certifications),
            'skills' => json_encode($skills),
            'education' => json_encode($education),
            'work_history' => json_encode($work_history),
        ]);
            

        // Return a success message
        return response()->json(['success' => true, 'message' => 'User updated successfully']);
    }

    return response()->json(['success' => false, 'message' => 'User not found']);
}


    



    public function getUserPicture($userid)
{
    // Fetch user data from the users table
    $user = DB::table('users')->where('userid', $userid)->first();

    // Check if the picture field is null or empty
    if ($user && !empty($user->picture)) {
        return response()->json([
            'picture' => asset('images/users/' . $user->picture)
        ]);
    }

    // Return the default picture if no user picture exists
    return response()->json([
        'picture' => asset('images/default_icon.png')
    ]);
}



public function updateUserPicture(Request $request)
{
    try {
        // Validate the request
        $request->validate([
            'userid' => 'required|exists:users,userid', // Ensure userid exists in users table
            'picture' => 'required|image|mimes:jpeg,png,jpg|max:2048', // Validate image
        ]);

        $userId = $request->input('userid');
        $file = $request->file('picture');

        // Log the received user ID for debugging
        \Log::info('The user ID received: ' . $userId);

        // Get the original filename
        $originalFileName = $file->getClientOriginalName();
        
        // Get the file extension
        $extension = $file->getClientOriginalExtension();
        
        // Generate a unique file name by appending a unique ID to the original filename
        $fileName = pathinfo($originalFileName, PATHINFO_FILENAME) . '-' . uniqid() . '.' . $extension;

        // Path to save the file in 'public/images/users/' directory
        $filePath = public_path('images/users/' . $fileName);
        
        // Store the image in the "public/images/users" directory
        $file->move(public_path('images/users'), $fileName);

        // Update the user's picture in the database with only the filename
        DB::table('users')
            ->where('userid', $userId)
            ->update(['picture' => $fileName]);

        // Return a success response
        return response()->json(['success' => true, 'picture' => asset('images/users/' . $fileName)]);
    } catch (\Exception $e) {
        // Log the exception message for debugging
        \Log::error('Error updating user picture: ', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);

        return response()->json(['success' => false, 'message' => 'An error occurred. Please try again later.'], 500);
    }
}





}

