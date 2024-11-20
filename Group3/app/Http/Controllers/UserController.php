<?php
namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function login(Request $request)
    {
        // Retrieve the credentials from the request
        $credentials = $request->only('username', 'password');
    
        // Log the login attempt
        \Log::info('Login attempt for username: ' . $credentials['username']);
    
        // Fetch the user from the database
        $user = DB::table('users')->where('username', $credentials['username'])->first();
    
        // Check if user exists and if the password matches
        if ($user) {
            \Log::info('User found with username: ' . $credentials['username'] . ', Usertype: ' . $user->usertype);
    
            // Check if password matches
            if ($user->password === $credentials['password']) {
                session(['user_id' => $user->userid]); // Store user ID in session
    
                // Log success message
                \Log::info('Login successful for username: ' . $credentials['username']);
                
                // Check usertype and log access to the respective page
                if ($user->usertype === 'Admin') {
                    \Log::info('Usertype is Admin. Accessing the dashboard.');
                    return response()->json([
                        'success' => true,
                        'userid' => $user->userid,  // Send the user ID back in the response
                        'username' => $user->username,  // Send the username as well
                        'usertype' => $user->usertype,
                        'message' => 'Login successful! Redirect to dashboard.',
                    ]);
                }
    
                if ($user->usertype === 'Standard') {
                    \Log::info('Usertype is Standard. Accessing the resume page.');
                    return response()->json([
                        'success' => true,
                        'userid' => $user->userid,
                        'username' => $user->username,
                        'usertype' => $user->usertype,
                        'message' => 'Login successful! Redirect to resume page.',
                    ]);
                }
            } else {
                \Log::warning('Login failed: Incorrect password for username: ' . $credentials['username']);
            }
        } else {
            \Log::warning('Login failed: User not found for username: ' . $credentials['username']);
        }
    
        // Return error response if login fails
        return response()->json([
            'success' => false,
            'error' => 'Invalid credentials',
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
        'phone' => 'nullable|string',
        'email' => 'nullable|string|max:255',
        'objective' => 'nullable|string',
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

        // Now update the user's picture in the database with only the filename
        DB::table('users')
            ->where('userid', $userId)
            ->update(['picture' => $fileName]);

        // Return a success response with the picture URL
        return response()->json([
            'success' => true,
            'pictureUrl' => asset('images/users/' . $fileName) // Send the full URL back to the frontend
        ]);
    } catch (\Exception $e) {
        // Log the exception message for debugging
        \Log::error('Error updating user picture: ', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);

        return response()->json(['success' => false, 'message' => 'An error occurred. Please try again later.'], 500);
    }
}

public function updateUserStatus(Request $request)
{
    try {
        // Validate the request data
        $request->validate([
            'userid' => 'required|exists:users,userid', // Ensure userid exists in users table
            'status' => 'required|string|in:N/A,Received,Reviewed,Referred,Selected,Hired', // Validate status
        ]);

        // Get the user ID and new status
        $userId = $request->input('userid');
        $status = $request->input('status');

        // Update the status in the database
        DB::table('users')
            ->where('userid', $userId)
            ->update(['status' => $status]);

        // Return a success response
        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully!',
        ]);
    } catch (\Exception $e) {
        // Log the error for debugging
        \Log::error('Error updating user status: ' . $e->getMessage());

        // Return an error response
        return response()->json([
            'success' => false,
            'message' => 'An error occurred while updating the status. Please try again.',
        ], 500);
    }
}

































public function viewResume($username)
{
    // Fetch the user based on the username, join with the 'resume' table
    $user = DB::table('users')
        ->leftJoin('resume', 'users.userid', '=', 'resume.userid')  // Join with 'resume' table on 'userid'
        ->where('users.username', $username)  // Filter by username
        ->select('users.*', 'resume.*')  // Select all columns from both tables
        ->first();  // Get the first result (should be a unique user)

    // If the user does not exist, return a 404 error
    if (!$user) {
        abort(404, 'User not found');
    }

    // Pass the user data to the view
    return view('view_resume', ['admin' => $user]);
}



}

