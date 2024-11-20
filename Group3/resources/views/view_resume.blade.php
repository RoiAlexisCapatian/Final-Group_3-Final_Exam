<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" href="/bag.ico" type="image/x-icon">
    <!-- <link rel="stylesheet" href="/css/dashboard.css"> -->
    <!-- <script src="/js/script.js"></script> -->


    <title>{{ $admin->username }}'s CV</title>

</head>
<body>
<style>
@import url("https://fonts.googleapis.com/css2?family=Open+Sans:wght@200;300;400;500;600;700&display=swap");
body {
    font-family: sans-serif;
    place-self: center;
}
.paper {
    position: relative;
    border: 1px solid;
    width: min-content;
    height: min-content;
    margin: 20px;
    box-shadow: 0px 0px 5px 0px #0000005e;
    border-radius: 10px;
    display: grid;
    padding: 30px;
    grid-column-gap: 30px;
    text-align: left;
}
.col_1 {position: relative;display: flex;grid-column: 1;flex-direction: column;width: 500px;}

.header1 {font-size: xx-large;margin: 20px 0px 50px 10px;}
.header3 {font-size: x-large;margin: 20px 0px 30px 0px;}
.professional_skills{position: relative;}
.professional_skills p::before {
    content: "•"; 
    color: black; 
    font-size: 1.2rem;
    display: inline-block;
    margin-right: 0.5rem; 
}
.certifications{position: relative;}
.certifications p::before {
    content: "•"; 
    color: black; 
    font-size: 1.2rem;
    display: inline-block;
    margin-right: 0.5rem; 
}
.paper p{margin: 0px 0px 10px 0px;text-align: justify; padding: 5px;}

.bold{font-weight: bold;text-align: justify;font-size: 13px;}

.col_2 {position: relative;display: flex;grid-column: 2;flex-direction: column;width: 300px;margin: 20px 0px 0px 0px;}
.col_2 contact{position: relative;}
.col_2 p::before {
    content: "•"; 
    color: black; 
    font-size: 1.2rem;
    display: inline-block;
    margin-right: 0.5rem; 
}

.paper .margin{margin:20px 0px 20px 0px}    
</style>
<!-- Modal Structure -->
            <!-- Modal Form (No <form> element here) -->
            <div class="paper">
                        <div class="col_1">
                    <!-- Hidden input field for the user ID -->
                    <input type="hidden" name="userid" id="userid" value="{{ $admin->userid }}">

                    <!-- Input field for fullname -->
                    <h1 id="fullname" style="text-align: center;" contenteditable="false">{{ $admin->fullname }}</h1>
            <div style="width: 100%; border: 1px solid;"></div>
            <h3 class="header3">RESUME OBJECTIVE</h3>
            <p id="objective" contenteditable="false">{{ $admin->objective }}</p>
            <div style="width: 100%;  border: 1px solid;"></div>
            <h3 class="header3">PROFESSIONAL SKILLS</h3>
            <div class="professional_skills">
            @php
                $professionalSkills = json_decode($admin->professional_skills); 
            @endphp

            @if(is_array($professionalSkills) && count($professionalSkills) > 0)
                @foreach($professionalSkills as $index => $professionalSkills)
                    <p>{{ $professionalSkills }}</p>
                @endforeach
            @else
                <p>No professional skills available.</p>
            @endif 
            </div>
            <div style="width: 100%;  border: 1px solid;"></div>
            <h3 class="header3">CERTIFICATIONS</h3>
            <div class="certifications">
            @php
                $certifications = json_decode($admin->certifications); 
            @endphp

            @if(is_array($certifications) && count($certifications) > 0)
                @foreach($certifications as $index => $certifications)
                    <p>{{ $certifications }}</p>
                @endforeach
            @else
                <p>No certifications available.</p>
            @endif 
            </div>
        </div>
        <div class="col_2">
            <!-- PROFIEL -->
            <div style="position: relative;align-self: center;width: fit-content;">
    <!-- User picture with default icon -->
    <img id="userPicture" style="width: 200px; height:200px;border: 1px solid; align-self: center;" src="{{ asset('images/default_icon.png') }}" alt="Image">
      <script>
        
function fetchUserPicture() {
    const pictureElement = document.getElementById('userPicture');
    const userid = document.getElementById('userid').value; // Get the user ID from the hidden input field

    // Ensure userid is not empty
    if (!userid) {
        console.error('User ID is missing.');
        return;
    } else {
        console.log('User ID:', userid);
    }

    // Send an AJAX request to get the user's picture
    fetch(`/get-user-picture/${userid}`)
        .then(response => response.json())
        .then(data => {
            const pictureSrc = data.picture || "{{ asset('images/default_icon.png') }}"; // Use user picture if available, else default

            // Log whether the picture value exists for the given user
            if (data.picture) {
                console.log('User has a picture:', data.picture); // Log the picture path
            } else {
                console.log('No picture found for this user, using default.');
            }

            console.log('Using picture:', pictureSrc); // Log the picture being used

            pictureElement.src = pictureSrc; // Set the image source
        })
        .catch(error => {
            console.error('Error fetching picture:', error);
        });
}
fetchUserPicture();
        </script>
</div>

            <!--  -->

            <br>
            <h3 class="header3 margin">CONTACT</h3>
            <span class="contact bold">Address:&nbsp;&nbsp;<p id="address" contenteditable="false" style="white-space: nowrap;">{{ $admin->address }}</p></span>
            <span class="contact bold">Birthdate:&nbsp;&nbsp;<p id="birthdate" contenteditable="false" style="white-space: nowrap;">{{ $admin->birthdate }}</p></span>
            <span class="contact bold">Phone:&nbsp;&nbsp;<p id="phone"  contenteditable="false" stle="white-space: nowrap;margin: 0;">{{ $admin->phone }}</p></span>
            <span class="contact bold">Email:&nbsp;&nbsp;<p id="email" contenteditable="false" style="white-space: nowrap;margin: 0;">{{ $admin->email }}</p></span>
            <div style="width: 100%;  border: 1px solid;"></div>
            <h3 class="header3 margin">SKILLS</h3>
            <div class="skills">
            @php
                $skills = json_decode($admin->skills); 
            @endphp

            @if(is_array($skills) && count($skills) > 0)
                @foreach($skills as $index => $skills)
                    <p>{{ $skills }}</p>
                @endforeach
            @else
                <p>No skills available.</p>
            @endif 
            </div>            
            <div style="width: 100%;  border: 1px solid;"></div>
            <h3 class="header3 margin">EDUCATION</h3>
            <div class="education">
            @php
                $education = json_decode($admin->education); 
            @endphp

            @if(is_array($education) && count($education) > 0)
                @foreach($education as $index => $education)
                    <p>{{ $education }}</p>
                @endforeach
            @else
                <p>No education available.</p>
            @endif                 
            </div>
            <div style="width: 100%;  border: 1px solid;"></div>
            <h3 class="header3 margin">WORK HISTORY</h3>
            <div class="work_history">
            @php
                $work_history = json_decode($admin->work_history); 
            @endphp

            @if(is_array($work_history) && count($work_history) > 0)
                @foreach($work_history as $index => $work_history)
                    <p>{{ $work_history }}</p>
                @endforeach
            @else
                <p>No work_history available.</p>
            @endif 
            </div>
        </div>
    </div> 

  
</body>
</html>
