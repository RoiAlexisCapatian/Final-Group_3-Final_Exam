<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <link rel="icon" href="/bag.ico" type="image/x-icon">
    <link rel="stylesheet" href="/css/dashboard.css">
    <!-- <script src="/js/script.js')"></script> -->
    <title>Dashboard</title>
</head>
<body>
    <main>
        <div style="row-gap: 5px;display: flex; flex-direction: column; align-items: flex-end;">
            <!-- ADMIN VIEW -->
            <div style="cursor: default; display: flex; background: blue; font-size: 1.2rem; color: white; border: 1px solid; padding: 8px 14px; border-radius: 7px; flex-direction: row; flex-wrap: nowrap; justify-content: center; align-items: center; column-gap: 5px;"><span>Admin</span><img src="{{ asset('images/admin.png') }}" width="25px" height="25px" alt="admin"></div>
            <!-- ADMIN TABLE -->
            <table class="resume_table">
            <thead>
                <tr class="resume_header">
                    <th class="userid_header">ID</th>
                    <th class="picture_header">Picture</th>
                    <th class="firstname_header">Username</th>
                    <th class="usertype_header">Usertype</th>
                    <th class="status_header">Status</th>
                    <th class="action_header">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($admins as $admin)
                <tr class="resume_row">
                    <td class="userid_td">{{ $admin->userid }}</td>
                    <td class="picture_td">
                    <img id="table_profile_{{ $admin->userid }}" src="{{ asset($admin->picture ? 'images/users/' . $admin->picture : 'images/default_icon.png') }}" alt="user image" width="50px" height="50px">
                    </td>
                    <td class="username_td">{{ $admin->username ?? 'N/A' }}</td>
                    <td class="usertype_td">{{ $admin->usertype }}</td>
                    <td class="status_td" id="status_td_{{ $admin->userid }}">
                        <select id="status_select_{{ $admin->userid }}" name="status">
                            <option value="N/A" {{ $admin->status == 'N/A' ? 'selected' : '' }}>N/A</option>
                            <option value="Received" {{ $admin->status == 'Received' ? 'selected' : '' }}>Received</option>
                            <option value="Reviewed" {{ $admin->status == 'Reviewed' ? 'selected' : '' }}>Reviewed</option>
                            <option value="Referred" {{ $admin->status == 'Referred' ? 'selected' : '' }}>Referred</option>
                            <option value="Selected" {{ $admin->status == 'Selected' ? 'selected' : '' }}>Selected</option>
                            <option value="Hired" {{ $admin->status == 'Hired' ? 'selected' : '' }}>Hired</option>
                        </select>
                    </td>
                    <td class="action_td">
                        <button class="view-button" onclick="showModal('{{ $admin->userid }}')">View</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
            </table>

        <!-- ADMIN LOGOUT -->
        <button class="logout" onclick="logout()"><span>Logout</span><img src="{{ asset('images/logout.png') }}" width="20px" height="20px" admin="logout"></button>
        </div>




<!-- Modal Structure -->
<div id="modal" class="modal">
    <!-- MINI TOOLS -->
    <div style="display: flex; width: -webkit-fill-available; justify-content: flex-end; right: 7rem; position: fixed; z-index: 1;">
        <div style="background-color: #eeffba; width: fit-content; padding: 13px; border-radius: 0px 0px 10px 10px; display: flex; gap: 15px;">
            <!-- Edit Button -->
            <button class="edit-button" style="border-radius:7px;padding:10px;border: none; background:#cfcfff; cursor: pointer; transition: all 0.3s ease;" 
                    title="Edit" 
                    onmouseover="this.style.backgroundColor='lightgreen'; this.style.transform='scale(1.2)';" 
                    onmouseout="this.style.backgroundColor='#cfcfff'; this.style.transform='scale(1)';" 
                    onclick="enableEdit()">
                <img src="{{ asset('images/edit.png') }}" alt="Edit" style="width: 24px; height: 24px;">
            </button>

            <!-- Save Button -->
            <button id="saveButton" class="save-button" type="button" style="border-radius:7px; padding:10px; border:none; background:#cfcfff; cursor:pointer; transition:all 0.3s ease;" 
                    title="Save"
                    onmouseover="this.style.backgroundColor='lightgreen'; this.style.transform='scale(1.2)';" 
                    onmouseout="this.style.backgroundColor='#cfcfff'; this.style.transform='scale(1)';" 
                    onclick="saveChanges()">
                <img src="{{ asset('images/save.png') }}" alt="Save" style="width: 24px; height: 24px;">
            </button>
        </div>
    </div>

    <div class="modal-content">
        <span class="close-button" onclick="closeModal()">&times;</span>
        <h2 id="modal-title" style="text-align: left; margin: 0;"></h2>
        <!-- Output Message Container -->
        <div id="output_message" style="margin-top: 20px; padding: 10px; background-color: #f8d7da; color: #721c24; display: none;">
            <!-- This is where the success/error message will appear -->
        </div>

        <div id="modal-content">
            <!-- Modal Form (No <form> element here) -->
            <div class="paper">
                        <div class="col_1">
                    <!-- Hidden input field for the user ID -->
                    <input type="hidden" name="userid" id="userid">

                    <!-- Input field for fullname -->
                    <h1 id="fullname" style="text-align: center;" contenteditable="false"></h1>
            <div style="width: 100%; border: 1px solid;"></div>
            <h3 class="header3">RESUME OBJECTIVE</h3>
            <p id="objective" contenteditable="false"></p>
            <div style="width: 100%;  border: 1px solid;"></div>
            <h3 class="header3">PROFESSIONAL SKILLS</h3>
            <div class="professional_skills"></div>
            <div style="width: 100%;  border: 1px solid;"></div>
            <h3 class="header3">CERTIFICATIONS</h3>
            <div class="certifications"></div>
        </div>
        <div class="col_2">
            <!-- PROFIEL -->
            <div style="position: relative;align-self: center;width: fit-content;">
    <!-- User picture with default icon -->
    <img id="userPicture" style="width: 200px; height:200px;border: 1px solid; align-self: center;" src="{{ asset('images/default_icon.png') }}" alt="Image">
    
<!-- Camera icon container -->
<div id="camera_container" style="display:none; position: absolute; bottom: -15px; right: -15px; transition: transform 0.3s ease-in-out;" 
         onmouseover="this.style.transform='scale(1.2)'" 
         onmouseout="this.style.transform='scale(1)'">
        <label for="fileInput" style="cursor: pointer; border-radius: 50%; overflow: hidden; display: inline-block;">
            <img src="{{ asset('images/camera.png') }}" 
                 style="width: 40px; height: 40px; border-radius: 50%; background: white; border: 2px solid; padding: 5px;" 
                 alt="Camera">
        </label>
        <!-- Hidden file input for uploading image -->
        <input type="file" id="fileInput" style="display: none;" accept="image/png, image/jpeg">
    </div>
<script>

    // Function to update the image preview
    function updateImagePreview(fileInputId, imgElementId) {
        const fileInput = document.getElementById(fileInputId);
        const imgElement = document.getElementById(imgElementId);
        const file = fileInput.files[0]; // Get the selected file

        // Check if a file was selected and it's an image
        if (file && (file.type === 'image/png' || file.type === 'image/jpeg')) {
            const reader = new FileReader(); // Create a FileReader to read the file

            reader.onload = function(e) {
                imgElement.src = e.target.result; // Set the image src to the file's content
                console.log('Preview updated:', file.name); // Log the file name for debugging
            };

            reader.readAsDataURL(file); // Read the file as a data URL
        } else {
            console.error('Please select a valid image file (PNG or JPG).');
        }
    }

    // Function to send the file to the server
    async function updateUserPicture(fileInput) {
    const formData = new FormData();
    const userid = document.getElementById('userid').value; // Get the user ID from the hidden input field
    formData.append('userid', userid); // Append the user ID
    formData.append('picture', fileInput.files[0]); // Append the selected file

    try {
        const response = await fetch('/update-user-picture', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: formData
        });

        if (response.ok) {
            const result = await response.json();
            console.log('Server response:', result);  // Check the response

            const pictureUrl = result.pictureUrl;  // Assuming the server returns the new picture's URL

            // Check if the image URL is valid
            if (pictureUrl) {
                // Dynamically update the profile image by user ID
                const profileImage = document.getElementById('table_profile_' + userid);
                if (profileImage) {
                    console.log('The table_profile_id matches the selected userid! Updating picture...');
                    profileImage.src = pictureUrl; // Update the image source dynamically
                } else {
                    console.error('Profile image not found for user ID:', userid);
                }

                alert('Picture updated successfully!');
            } else {
                alert('Failed to get picture URL.');
            }
        } else {
            const result = await response.json();
            console.error('Failed to update picture:', result.message);
            alert('Failed to update picture. Please try again.');
        }
    } catch (error) {
        console.error('Error updating picture:', error);
        alert('An error occurred. Please try again.');
    }
}



    // Attach the change event to the file input
    document.getElementById('fileInput').addEventListener('change', function() {
        updateImagePreview('fileInput', 'userPicture');
        updateUserPicture(this);
    });    
</script>    
</div>

            <!--  -->

            <br>
            <h3 class="header3 margin">CONTACT</h3>
            <span class="contact bold">Address:&nbsp;&nbsp;<p id="address" contenteditable="false" style="white-space: nowrap;"></p></span>
            <span class="contact bold">Birthdate:&nbsp;&nbsp;<p id="birthdate" contenteditable="false" style="white-space: nowrap;"></p></span>
            <span class="contact bold">Phone:&nbsp;&nbsp;<p id="phone"  contenteditable="false" stle="white-space: nowrap;margin: 0;"></p></span>
            <span class="contact bold">Email:&nbsp;&nbsp;<p id="email" contenteditable="false" style="white-space: nowrap;margin: 0;"></p></span>
            <div style="width: 100%;  border: 1px solid;"></div>
            <h3 class="header3 margin">SKILLS</h3>
            <div class="skills"></div>            
            <div style="width: 100%;  border: 1px solid;"></div>
            <h3 class="header3 margin">EDUCATION</h3>
            <div class="education"></div>
            <div style="width: 100%;  border: 1px solid;"></div>
            <h3 class="header3 margin">WORK HISTORY</h3>
            <div class="work_history"></div>
        </div>
    </div> 
        </div>
    </div>
</div>



    </main>



    <script>
        // Check if the 'userid' is present in localStorage
        const userid = localStorage.getItem('userid');
        console.log("userid from localStorage:", userid); // Log for debugging

        // If 'userid' is not found, redirect to the login page
        if (!userid) {
            window.location.href = '/';
        }
        function logout() {
    // Remove 'userid' from localStorage
    localStorage.removeItem('userid');
    // Redirect to login page or perform logout action
    window.location.href = '/';
    }        


    function showModal(userid) {
    const modal = document.getElementById('modal');
    const fullnameElement = document.getElementById('fullname');
    const objectiveElement = document.getElementById('objective');
    const professionalSkillsContainer = document.querySelector('.professional_skills');
    const certificationsContainer = document.querySelector('.certifications');
    const skillsContainer = document.querySelector('.skills');
    const educationContainer = document.querySelector('.education');
    const workHistoryContainer = document.querySelector('.work_history');

     // New elements to show address, birthdate, phone, and email
     const addressElement = document.getElementById('address');
    const birthdateElement = document.getElementById('birthdate');
    const phoneElement = document.getElementById('phone');
    const emailElement = document.getElementById('email');


    const useridField = document.getElementById('userid'); // Hidden field to store the user ID
    const modalTitle = document.getElementById('modal-title'); // Element to display the welcome message

    // Set the userid in the hidden input field
    useridField.value = userid; // Dynamically set the hidden input value
    
    // Log the userid to make sure it's correct
    console.log(`showModal called with userid: ${userid}`);
    fetchUserPicture();
    // Fetch user data from the server
    fetch(`/get-user/${userid}`)
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                alert('Error: ' + data.error);
            } else {
                // Set the content of the fullname (contenteditable)
                fullnameElement.textContent = data.fullname || 'None';  // This will display the fullname inside the contenteditable field

                // Set the content of the objective (contenteditable)
                objectiveElement.textContent = data.objective || 'None';  // This will display the objective inside the contenteditable field
                

                // Populate the contact details (address, birthdate, phone, email)
                addressElement.textContent = `${data.address || 'Not provided'}`;
                birthdateElement.textContent = `${data.birthdate || 'Not provided'}`;
                phoneElement.textContent = `${data.phone || '9123456789'}`;
                emailElement.textContent = `${data.email || 'example@gmail.com'}`;

                // console.log(`Professional skills for userid ${userid}:`, data.professional_skills);
                professionalSkillsContainer.innerHTML = '';
                certificationsContainer.innerHTML = '';
                skillsContainer.innerHTML = '';
                educationContainer.innerHTML = '';
                workHistoryContainer.innerHTML = '';
                 // Populate professional skills
                 if (Array.isArray(data.professional_skills)) {
                    data.professional_skills.forEach((skill, index) => {
                        const skillParagraph = document.createElement('p');
                        skillParagraph.textContent = skill;
                        skillParagraph.setAttribute('data-index', index); // Optional, for tracking
                        professionalSkillsContainer.appendChild(skillParagraph);
                    });
                } else {
                    professionalSkillsContainer.textContent = 'No skills available.';
                }
                if (Array.isArray(data.certifications)) {
                    data.certifications.forEach((certification, index) => {
                        const certificationParagraph = document.createElement('p');
                        certificationParagraph.textContent = certification;
                        certificationParagraph.setAttribute('data-index', index); // Optional, for tracking
                        certificationsContainer.appendChild(certificationParagraph);
                    });
                } else {
                    certificationsContainer.textContent = 'No certifications available.';
                }
                if (Array.isArray(data.skills)) {
                    data.skills.forEach((skill, index) => {
                        const skillParagraph = document.createElement('p');
                        skillParagraph.textContent = skill;
                        skillParagraph.setAttribute('data-index', index); // Optional, for tracking
                        skillsContainer.appendChild(skillParagraph);
                    });
                } else {
                    skillsContainer.textContent = 'No skills available.';
                }
                if (Array.isArray(data.education)) {
                    data.education.forEach((education, index) => {
                        const educationParagraph = document.createElement('p');
                        educationParagraph.textContent = education;
                        educationParagraph.setAttribute('data-index', index); // Optional, for tracking
                        educationContainer.appendChild(educationParagraph);
                    });
                } else {
                    educationContainer.textContent = 'No education available.';
                }
                if (Array.isArray(data.work_history)) {
                    data.work_history.forEach((work, index) => {
                        const workParagraph = document.createElement('p');
                        workParagraph.textContent = work;
                        workParagraph.setAttribute('data-index', index); // Optional, for tracking
                        workHistoryContainer.appendChild(workParagraph);
                    });
                } else {
                    workHistoryContainer.textContent = 'No work history available.';
                }
                
                // Show the modal
                modal.style.display = 'block';

                // Update the modal title with a welcome message using the username
                if (modalTitle) {
                    modalTitle.textContent = `Welcome ${data.username || 'User'}!`;
                }

                // Log the data to make sure the correct user data is retrieved
                console.log(`Modal data for userid ${userid}:`, data);
            }
        })
        .catch(error => {
            console.error('Fetch error:', error);
            alert('Error fetching user data');
        });
}




function enableEdit() {
    const saveButton = document.getElementById('saveButton');
    saveButton.disabled = false; // Enable the save button when edit starts
    const outputMessage = document.getElementById('output_message'); // Assuming there's an element with this ID for output messages
    // Clear the output message content
    if (outputMessage) {
        outputMessage.textContent = ''; // Clear the text content of the output message element
        outputMessage.style.display='none';
    }


    editFields();
    editObjective();
    editProfessionalSkills();
    editCertifications();
    editSkills();
    editEducation();
    editWorkHistory();

    // Disable the edit button to prevent multiple activations
    const editButton = document.querySelector('.edit-button');
    if (editButton) {
        editButton.disabled = true;
    } else {
        console.error("Edit button not found!");
    }
}

// Function to handle editing multiple fields including fullname, address, birthdate, phone, and email
function editFields() {
    const fullnameElement = document.getElementById('fullname');
    const addressElement = document.getElementById('address');
    const birthdateElement = document.getElementById('birthdate');
    const phoneElement = document.getElementById('phone');
    const emailElement = document.getElementById('email');

    // Enable editing for each field and apply visual indicators
    const fields = [ addressElement, birthdateElement, phoneElement, emailElement,fullnameElement];

    fields.forEach(element => {
        if (element) {
            element.contentEditable = true; // Allow editing
            element.style.border = '1px solid #ccc'; // Visual indication for edit mode
            
            element.focus(); // Focus the field for convenience
    
            
            if (element === phoneElement) {
    // Remove non-numeric characters and allow free input
    element.addEventListener('input', function(event) {
        // Only allow digits
        element.textContent = element.textContent.replace(/[^0-9]/g, '');

        // Ensure left-to-right text direction
        element.style.direction = 'ltr';
        element.style.textAlign = 'left';
    });
    
}
        } else {
            console.error(`Element not found: ${element.id}`);
        }
    });

     // Show the camera container (set display to block)
     const cameraContainer = document.getElementById('camera_container');
    if (cameraContainer) {
        cameraContainer.style.display = 'block'; // Make camera container visible
    } else {
        console.error('Camera container not found.');
    }
}



// Separate function to handle editing the objective
function editObjective() {
    const objectiveElement = document.getElementById('objective');
    objectiveElement.contentEditable = true; // Allow editing
    objectiveElement.style.border = '1px solid #ccc'; // Visual indication for edit mode
}



// Function to edit professional skills
function editProfessionalSkills() {
    const professionalSkillsContainer = document.querySelector('.professional_skills');
    const skillElements = professionalSkillsContainer.querySelectorAll('p');

    // Make existing skills editable
    skillElements.forEach(skill => {
        skill.contentEditable = true; // Allow editing
        skill.style.border = '1px dashed #ccc'; // Visual indication for edit mode
        skill.addEventListener('click', () => skill.focus());
    });

    // Add [+] and [-] buttons for dynamic professional skill management
    if (!document.querySelector('.add-professional-skill')) {
        const addButton = createAddProfessionalSkillButton();
        const removeButton = createRemoveProfessionalSkillButton();

        professionalSkillsContainer.parentElement.insertBefore(addButton, professionalSkillsContainer);
        professionalSkillsContainer.parentElement.insertBefore(removeButton, professionalSkillsContainer.nextSibling);

        updateRemoveProfessionalSkillButtonVisibility();
    }
}
function createAddProfessionalSkillButton() {
    const addButton = document.createElement('span');
    addButton.textContent = '[+]';
    addButton.classList.add('add-professional-skill');
    addButton.style.cursor = 'pointer';
    addButton.style.margin = '5px 0px';
    addButton.style.background = 'antiquewhite';
    addButton.style.width = '1.2rem';
    addButton.style.padding = '5px';
    addButton.style.borderRadius = '3px';
    addButton.style.textAlign = 'center';

    addButton.addEventListener('click', () => {
        const professionalSkillsContainer = document.querySelector('.professional_skills');
        const newSkill = document.createElement('p');
        newSkill.textContent = 'New Professional Skill'; // Default text for a new skill
        newSkill.contentEditable = true;
        newSkill.style.border = '1px dashed #ccc';
        professionalSkillsContainer.appendChild(newSkill);
        updateRemoveProfessionalSkillButtonVisibility(); // Update visibility of [-] button
    });

    return addButton;
}
function createRemoveProfessionalSkillButton() {
    const removeButton = document.createElement('span');
    removeButton.textContent = '[-]';
    removeButton.classList.add('remove-professional-skill');
    removeButton.style.cursor = 'pointer';
    removeButton.style.margin = '5px 0px';
    removeButton.style.background = 'antiquewhite';
    removeButton.style.width = '1.2rem';
    removeButton.style.padding = '5px';
    removeButton.style.borderRadius = '3px';
    removeButton.style.textAlign = 'center';

    removeButton.addEventListener('click', () => {
        const professionalSkillsContainer = document.querySelector('.professional_skills');
        const lastSkill = professionalSkillsContainer.querySelector('p:last-child');
        if (lastSkill) {
            professionalSkillsContainer.removeChild(lastSkill);
        }
        updateRemoveProfessionalSkillButtonVisibility(); // Update visibility of [-] button
    });

    return removeButton;
}
function updateRemoveProfessionalSkillButtonVisibility() {
    const professionalSkillsContainer = document.querySelector('.professional_skills');
    const removeButton = document.querySelector('.remove-professional-skill');

    // Ensure there's at least one skill before displaying the [-] button
    if (professionalSkillsContainer && removeButton) {
        const skills = professionalSkillsContainer.querySelectorAll('p');

        // If there's only one skill, hide the [-] button
        if (skills.length <= 1) {
            removeButton.style.display = 'none';
        } else {
            removeButton.style.display = 'inline'; // Show the [-] button if more than one skill exists
        }
    }
}







function editCertifications() {
    const certificationsContainer = document.querySelector('.certifications');
    const certificationElements = certificationsContainer.querySelectorAll('p');

    // Make existing certifications editable
    certificationElements.forEach(certification => {
        certification.contentEditable = true; // Allow editing
        certification.style.border = '1px dashed #ccc'; // Visual indication for edit mode
        certification.addEventListener('click', () => certification.focus()); // Focus when clicked
    });

    // Add [+] and [-] buttons for dynamic certification management
    if (!document.querySelector('.add-certification')) {
        const addCertificationButton = createAddCertificationButton();
        const removeCertificationButton = createRemoveCertificationButton();

        certificationsContainer.parentElement.insertBefore(addCertificationButton, certificationsContainer);
        certificationsContainer.parentElement.insertBefore(removeCertificationButton, certificationsContainer.nextSibling);

        updateRemoveCertificationButtonVisibility();
    }
}
function createAddCertificationButton() {
    const addButton = document.createElement('span');
    addButton.textContent = '[+]';
    addButton.classList.add('add-certification');
    addButton.style.cursor = 'pointer';
    addButton.style.margin = '5px 0px';
    addButton.style.background = 'antiquewhite';
    addButton.style.width = '1.2rem';
    addButton.style.padding = '5px';
    addButton.style.borderRadius = '3px';
    addButton.style.textAlign = 'center';

    addButton.addEventListener('click', () => {
        const certificationsContainer = document.querySelector('.certifications');
        const newCertification = document.createElement('p');
        newCertification.textContent = 'New Certification'; // Default text for a new certification
        newCertification.contentEditable = true;
        newCertification.style.border = '1px dashed #ccc';
        certificationsContainer.appendChild(newCertification);
        updateRemoveCertificationButtonVisibility(); // Update visibility of [-] button
    });

    return addButton;
}
// Helper function to create the [-] button for certifications
function createRemoveCertificationButton() {
    const removeButton = document.createElement('span');
    removeButton.textContent = '[-]';
    removeButton.classList.add('remove-certification');
    removeButton.style.cursor = 'pointer';
    removeButton.style.margin = '5px 0px';
    removeButton.style.background = 'antiquewhite';
    removeButton.style.width = '1.2rem';
    removeButton.style.padding = '5px';
    removeButton.style.borderRadius = '3px';
    removeButton.style.textAlign = 'center';

    removeButton.addEventListener('click', () => {
        const certificationsContainer = document.querySelector('.certifications');
        const lastCertification = certificationsContainer.querySelector('p:last-child');
        if (lastCertification) {
            certificationsContainer.removeChild(lastCertification);
        }
        updateRemoveCertificationButtonVisibility(); // Update visibility of [-] button
    });

    return removeButton;
}
// Helper function to update visibility of the [-] button for certifications
function updateRemoveCertificationButtonVisibility() {
    const certificationsContainer = document.querySelector('.certifications');
    const removeButton = document.querySelector('.remove-certification');

    // Ensure there's at least one certification before displaying the [-] button
    if (certificationsContainer && removeButton) {
        const certifications = certificationsContainer.querySelectorAll('p');

        // If there's only one certification, hide the [-] button
        if (certifications.length <= 1) {
            removeButton.style.display = 'none';
        } else {
            removeButton.style.display = 'inline'; // Show the [-] button if more than one certification exists
        }
    }
}




// Function to edit skills
function editSkills() {
    const skillsContainer = document.querySelector('.skills');
    const skillElements = skillsContainer.querySelectorAll('p');

    // Make existing skills editable
    skillElements.forEach(skill => {
        skill.contentEditable = true;
        skill.style.border = '1px dashed #ccc';
        skill.addEventListener('click', () => skill.focus());
    });

    // Add [+] and [-] buttons for dynamic skills management
    if (!document.querySelector('.add-skill')) {
        const addSkillButton = createAddSkillButton();
        const removeSkillButton = createRemoveSkillButton();

        skillsContainer.parentElement.insertBefore(addSkillButton, skillsContainer);
        skillsContainer.parentElement.insertBefore(removeSkillButton, skillsContainer.nextSibling);

        updateRemoveSkillButtonVisibility();
    }
}
function createAddSkillButton() {
    const addButton = document.createElement('span');
    addButton.textContent = '[+]';
    addButton.classList.add('add-skill');
    addButton.style.cursor = 'pointer';
    addButton.style.margin = '5px 0px';
    addButton.style.background = 'antiquewhite';
    addButton.style.width = '1.2rem';
    addButton.style.padding = '5px';
    addButton.style.borderRadius = '3px';
    addButton.style.textAlign = 'center';

    addButton.addEventListener('click', () => {
        const skillsContainer = document.querySelector('.skills');
        const newSkill = document.createElement('p');
        newSkill.textContent = 'New Skill';
        newSkill.contentEditable = true;
        newSkill.style.border = '1px dashed #ccc';
        skillsContainer.appendChild(newSkill);
        updateRemoveSkillButtonVisibility();
    });

    return addButton;
}
function createRemoveSkillButton() {
    const removeButton = document.createElement('span');
    removeButton.textContent = '[-]';
    removeButton.classList.add('remove-skill');
    removeButton.style.cursor = 'pointer';
    removeButton.style.margin = '5px 0px';
    removeButton.style.background = 'antiquewhite';
    removeButton.style.width = '1.2rem';
    removeButton.style.padding = '5px';
    removeButton.style.borderRadius = '3px';
    removeButton.style.textAlign = 'center';

    removeButton.addEventListener('click', () => {
        const skillsContainer = document.querySelector('.skills');
        const lastSkill = skillsContainer.querySelector('p:last-child');
        if (lastSkill) {
            skillsContainer.removeChild(lastSkill);
        }
        updateRemoveSkillButtonVisibility();
    });

    return removeButton;
}
function updateRemoveSkillButtonVisibility() {
    const skillsContainer = document.querySelector('.skills');
    const removeButton = document.querySelector('.remove-skill');

    if (skillsContainer && removeButton) {
        const skills = skillsContainer.querySelectorAll('p');
        if (skills.length <= 1) {
            removeButton.style.display = 'none';
        } else {
            removeButton.style.display = 'inline';
        }
    }
}

// Function to edit education
function editEducation() {
    const educationContainer = document.querySelector('.education');
    const educationElements = educationContainer.querySelectorAll('p');

    // Make existing education items editable
    educationElements.forEach(education => {
        education.contentEditable = true;
        education.style.border = '1px dashed #ccc';
        education.addEventListener('click', () => education.focus());
    });

    // Add [+] and [-] buttons for dynamic education management
    if (!document.querySelector('.add-education')) {
        const addEducationButton = createAddEducationButton();
        const removeEducationButton = createRemoveEducationButton();

        educationContainer.parentElement.insertBefore(addEducationButton, educationContainer);
        educationContainer.parentElement.insertBefore(removeEducationButton, educationContainer.nextSibling);

        updateRemoveEducationButtonVisibility();
    }
}
function createAddEducationButton() {
    const addButton = document.createElement('span');
    addButton.textContent = '[+]';
    addButton.classList.add('add-education');
    addButton.style.cursor = 'pointer';
    addButton.style.margin = '5px 0px';
    addButton.style.background = 'antiquewhite';
    addButton.style.width = '1.2rem';
    addButton.style.padding = '5px';
    addButton.style.borderRadius = '3px';
    addButton.style.textAlign = 'center';

    addButton.addEventListener('click', () => {
        const educationContainer = document.querySelector('.education');
        const newEducation = document.createElement('p');
        newEducation.textContent = 'New Education';
        newEducation.contentEditable = true;
        newEducation.style.border = '1px dashed #ccc';
        educationContainer.appendChild(newEducation);
        updateRemoveEducationButtonVisibility();
    });

    return addButton;
}
function createRemoveEducationButton() {
    const removeButton = document.createElement('span');
    removeButton.textContent = '[-]';
    removeButton.classList.add('remove-education');
    removeButton.style.cursor = 'pointer';
    removeButton.style.margin = '5px 0px';
    removeButton.style.background = 'antiquewhite';
    removeButton.style.width = '1.2rem';
    removeButton.style.padding = '5px';
    removeButton.style.borderRadius = '3px';
    removeButton.style.textAlign = 'center';

    removeButton.addEventListener('click', () => {
        const educationContainer = document.querySelector('.education');
        const lastEducation = educationContainer.querySelector('p:last-child');
        if (lastEducation) {
            educationContainer.removeChild(lastEducation);
        }
        updateRemoveEducationButtonVisibility();
    });

    return removeButton;
}
function updateRemoveEducationButtonVisibility() {
    const educationContainer = document.querySelector('.education');
    const removeButton = document.querySelector('.remove-education');

    if (educationContainer && removeButton) {
        const educationItems = educationContainer.querySelectorAll('p');
        if (educationItems.length <= 1) {
            removeButton.style.display = 'none';
        } else {
            removeButton.style.display = 'inline';
        }
    }
}

// Function to edit work history
function editWorkHistory() {
    const workHistoryContainer = document.querySelector('.work_history');
    const workElements = workHistoryContainer.querySelectorAll('p');

    // Make existing work history items editable
    workElements.forEach(work => {
        work.contentEditable = true;
        work.style.border = '1px dashed #ccc';
        work.addEventListener('click', () => work.focus());
    });

    // Add [+] and [-] buttons for dynamic work history management
    if (!document.querySelector('.add-work')) {
        const addWorkButton = createAddWorkButton();
        const removeWorkButton = createRemoveWorkButton();

        workHistoryContainer.parentElement.insertBefore(addWorkButton, workHistoryContainer);
        workHistoryContainer.parentElement.insertBefore(removeWorkButton, workHistoryContainer.nextSibling);

        updateRemoveWorkButtonVisibility();
    }
}
function createAddWorkButton() {
    const addButton = document.createElement('span');
    addButton.textContent = '[+]';
    addButton.classList.add('add-work');
    addButton.style.cursor = 'pointer';
    addButton.style.margin = '5px 0px';
    addButton.style.background = 'antiquewhite';
    addButton.style.width = '1.2rem';
    addButton.style.padding = '5px';
    addButton.style.borderRadius = '3px';
    addButton.style.textAlign = 'center';

    addButton.addEventListener('click', () => {
        const workHistoryContainer = document.querySelector('.work_history');
        const newWork = document.createElement('p');
        newWork.textContent = 'New Work History';
        newWork.contentEditable = true;
        newWork.style.border = '1px dashed #ccc';
        workHistoryContainer.appendChild(newWork);
        updateRemoveWorkButtonVisibility();
    });

    return addButton;
}
function createRemoveWorkButton() {
    const removeButton = document.createElement('span');
    removeButton.textContent = '[-]';
    removeButton.classList.add('remove-work');
    removeButton.style.cursor = 'pointer';
    removeButton.style.margin = '5px 0px';
    removeButton.style.background = 'antiquewhite';
    removeButton.style.width = '1.2rem';
    removeButton.style.padding = '5px';
    removeButton.style.borderRadius = '3px';
    removeButton.style.textAlign = 'center';

    removeButton.addEventListener('click', () => {
        const workHistoryContainer = document.querySelector('.work_history');
        const lastWork = workHistoryContainer.querySelector('p:last-child');
        if (lastWork) {
            workHistoryContainer.removeChild(lastWork);
        }
        updateRemoveWorkButtonVisibility();
    });

    return removeButton;
}
function updateRemoveWorkButtonVisibility() {
    const workHistoryContainer = document.querySelector('.work_history');
    const removeButton = document.querySelector('.remove-work');

    if (workHistoryContainer && removeButton) {
        const workItems = workHistoryContainer.querySelectorAll('p');
        if (workItems.length <= 1) {
            removeButton.style.display = 'none';
        } else {
            removeButton.style.display = 'inline';
        }
    }
}







// Show output message function
function showOutputMessage(message, isError = false) {
    const outputMessageDiv = document.getElementById('output_message');
    outputMessageDiv.style.display = 'block';
    outputMessageDiv.innerText = message;
    
    // Add a background color based on success or error
    if (isError) {
        outputMessageDiv.style.backgroundColor = '#f8d7da';
        outputMessageDiv.style.color = '#721c24';
    } else {
        outputMessageDiv.style.backgroundColor = '#d4edda';
        outputMessageDiv.style.color = '#155724';
    }
}

// Save button click handler
function saveChanges() {
    // Get the user ID and editable elements
    const userid = document.getElementById('userid').value;
    const fullnameElement = document.getElementById('fullname');
    const addressElement = document.getElementById('address');
    const birthdateElement = document.getElementById('birthdate');
    const phoneElement = document.getElementById('phone');
    const emailElement = document.getElementById('email');
    const pictureElement = document.getElementById('camera_container')


    const objectiveElement = document.getElementById('objective');
    const professionalSkillsContainer = document.querySelector('.professional_skills');
    const certificationsContainer = document.querySelector('.certifications');
    const skillsContainer = document.querySelector('.skills');
    const educationContainer = document.querySelector('.education');
    const workHistoryContainer = document.querySelector('.work_history');

    const newFullname = fullnameElement.textContent || fullnameElement.innerText;
    const newAddress = addressElement.textContent || addressElement.innerText;
    const newBirthdate = birthdateElement.textContent || birthdateElement.innerText;
    const newPhone = phoneElement.textContent || phoneElement.innerText;
    const newEmail = emailElement.textContent || emailElement.innerText;
    const newObjective = objectiveElement.textContent || objectiveElement.innerText;

    // Collect all updated professional skills as an array
    const professionalSkills = Array.from(professionalSkillsContainer.querySelectorAll('p'))
        .map(skill => skill.textContent.trim());

    // Collect all updated certifications as an array
    const certifications = Array.from(certificationsContainer.querySelectorAll('p'))
        .map(certification => certification.textContent.trim());

    const skills = Array.from(skillsContainer.querySelectorAll('p'))
        .map(skill => skill.textContent.trim());

    const education = Array.from(educationContainer.querySelectorAll('p'))
        .map(education => education.textContent.trim());

    const workHistory = Array.from(workHistoryContainer.querySelectorAll('p'))
        .map(work => work.textContent.trim());

    // Validate inputs
    if (!userid || !newFullname) {
        showOutputMessage('User ID or Full Name is missing!', true);
        return;
    }
    if (!newObjective) {
        showOutputMessage('Objective is missing.', true);
        return;
    }
    if (!newAddress) {
        showOutputMessage('At least one of the following is required: Address, Birthdate, Phone, Email.', true);
        return;
    }
    if (!newBirthdate) {
        showOutputMessage('At least one of the following is required: Address, Birthdate, Phone, Email.', true);
        return;
    }
    if (!newPhone) {
        showOutputMessage('At least one of the following is required: Address, Birthdate, Phone, Email.', true);
        return;
    }
    if (!newEmail) {
        showOutputMessage('At least one of the following is required: Address, Birthdate, Phone, Email.', true);
        return;
    }

    // Get the CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Prepare the data to send
    const data = {
        userid: userid,
        fullname: newFullname,
        address: newAddress,
        birthdate: newBirthdate,
        phone: newPhone,
        email: newEmail,
        objective: newObjective,
        professional_skills: professionalSkills, // Send as an array
        certifications: certifications, // Send as an array
        skills: skills, // Send as an array
        education: education, // Send as an array
        work_history: workHistory, // Send as an array
        _token: csrfToken // Include CSRF token for security
    };

    // Prepare the AJAX request
    const xhr = new XMLHttpRequest();
    xhr.open('POST', '/update-user', true);
    xhr.setRequestHeader('Content-Type', 'application/json');

    // Handle the response
    xhr.onload = function() {
        console.log(xhr.responseText);  
        if (xhr.status >= 200 && xhr.status < 300) {
            const response = JSON.parse(xhr.responseText);
            if (response.success) {
                showOutputMessage('Changes saved successfully!', false); // Success message
                fullnameElement.contentEditable = false;
                addressElement.contentEditable = false;
                birthdateElement.contentEditable = false;
                phoneElement.contentEditable = false;
                emailElement.contentEditable = false;
                objectiveElement.contentEditable = false;
                pictureElement.style.display = 'none';

                fullnameElement.style.border = '';
                objectiveElement.style.border = '';
                addressElement.style.border = '';
                birthdateElement.style.border = '';
                phoneElement.style.border = '';
                emailElement.style.border = '';

                // Make professional skills non-editable
                const professionalSkillElements = professionalSkillsContainer.querySelectorAll('p');
                professionalSkillElements.forEach(professional_skills => {
                    professional_skills.contentEditable = false;
                    professional_skills.style.border = ''; // Reset the border style
                });
                
                // Make certifications non-editable
                const certificationElements = certificationsContainer.querySelectorAll('p');
                certificationElements.forEach(certification => {
                    certification.contentEditable = false;
                    certification.style.border = ''; // Reset the border style
                });

                // Make skills non-editable
                const skillElements = skillsContainer.querySelectorAll('p');
                skillElements.forEach(skill => {
                    skill.contentEditable = false;
                    skill.style.border = ''; // Reset the border style
                });

                // Make education non-editable
                const educationElements = educationContainer.querySelectorAll('p');
                educationElements.forEach(education => {
                    education.contentEditable = false;
                    education.style.border = ''; // Reset the border style
                });

                // Make work history non-editable
                const workElements = workHistoryContainer.querySelectorAll('p');
                workElements.forEach(work => {
                    work.contentEditable = false;
                    work.style.border = ''; // Reset the border style
                });


                // Disable save button and enable edit button
                const saveButton = document.getElementById('saveButton');
                saveButton.disabled = true;

                const editButton = document.querySelector('.edit-button');
                if (editButton) {
                    editButton.disabled = false; // Enable the edit button again
                }

                // Remove the [+] and [-] buttons for both skills and certifications
                const addProfessionalSkillButton = document.querySelector('.add-professional-skill');
                const removeProfessionalSkillButton = document.querySelector('.remove-professional-skill');
                if (addProfessionalSkillButton) {
                    addProfessionalSkillButton.remove(); // Remove the [+] button for skills
                }
                if (removeProfessionalSkillButton) {
                    removeProfessionalSkillButton.remove(); // Remove the [-] button for skills
                }

                // For certifications: remove the [+] and [-] buttons
                const addCertificationButton = document.querySelector('.add-certification');
                const removeCertificationButton = document.querySelector('.remove-certification');
                if (addCertificationButton) {
                    addCertificationButton.remove(); // Remove the [+] button for certifications
                }
                if (removeCertificationButton) {
                    removeCertificationButton.remove(); // Remove the [-] button for certifications
                }

                // For skills: remove the [+] and [-] buttons
                const addSkillButton = document.querySelector('.add-skill');
                const removeSkillButton = document.querySelector('.remove-skill');
                if (addSkillButton) {
                    addSkillButton.remove(); // Remove the [+] button for skills
                }
                if (removeSkillButton) {
                    removeSkillButton.remove(); // Remove the [-] button for skills
                }

                // For education: remove the [+] and [-] buttons
                const addEducationButton = document.querySelector('.add-education');
                const removeEducationButton = document.querySelector('.remove-education');
                if (addEducationButton) {
                    addEducationButton.remove(); // Remove the [+] button for education
                }
                if (removeEducationButton) {
                    removeEducationButton.remove(); // Remove the [-] button for education
                }

                // For work history: remove the [+] and [-] buttons
                const addWorkButton = document.querySelector('.add-work');
                const removeWorkButton = document.querySelector('.remove-work');
                if (addWorkButton) {
                    addWorkButton.remove(); // Remove the [+] button for work history
                }
                if (removeWorkButton){
                    removeWorkButton.remove();
                }
                
            } else {
                showOutputMessage(response.message || 'Failed to save changes.', true);
            }
        } else {
            showOutputMessage('Error: ' + xhr.statusText, true); // Error handling for request failure
        }
    };

    // Handle errors
    xhr.onerror = function() {
        showOutputMessage('Error submitting the form.', true);
    };

    // Send the data as a JSON string
    xhr.send(JSON.stringify(data));
}











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





































function closeModal() {
    const modal = document.getElementById('modal');
    const outputMessage = document.getElementById('output_message'); // Assuming there's an element with this ID for output messages

    // Close the modal
    modal.style.display = 'none';

    // Clear the output message content
    if (outputMessage) {
        outputMessage.textContent = ''; // Clear the text content of the output message element
        outputMessage.style.display='none';
    }

    // Reset the contentEditable and borders of elements
    const fullnameElement = document.getElementById('fullname');
    const addressElement = document.getElementById('address');
    const birthdateElement = document.getElementById('birthdate');
    const phoneElement = document.getElementById('phone');
    const emailElement = document.getElementById('email');
    const objectiveElement = document.getElementById('objective');
    const pictureElement = document.getElementById('camera_container'); // Assuming this is the element for the camera

    // Reset contentEditable to false and borders
    fullnameElement.contentEditable = false;
    addressElement.contentEditable = false;
    birthdateElement.contentEditable = false;
    phoneElement.contentEditable = false;
    emailElement.contentEditable = false;
    objectiveElement.contentEditable = false;
    
    // Reset border styles
    fullnameElement.style.border = '';
    objectiveElement.style.border = '';
    addressElement.style.border = '';
    birthdateElement.style.border = '';
    phoneElement.style.border = '';
    emailElement.style.border = '';

    // Hide the camera container (similar to saveChanges function)
    if (pictureElement) {
        pictureElement.style.display = 'none'; // Hide the camera container when modal is closed
    }

    // Reset professional skills, certifications, skills, education, and work history sections
    const professionalSkillsContainer = document.querySelector('.professional_skills');
    const certificationsContainer = document.querySelector('.certifications');
    const skillsContainer = document.querySelector('.skills');
    const educationContainer = document.querySelector('.education');
    const workHistoryContainer = document.querySelector('.work_history');

    // Make professional skills non-editable and reset border styles
    const professionalSkillElements = professionalSkillsContainer.querySelectorAll('p');
    professionalSkillElements.forEach(professional_skill => {
        professional_skill.contentEditable = false;
        professional_skill.style.border = ''; // Reset the border style
    });

    // Make certifications non-editable and reset border styles
    const certificationElements = certificationsContainer.querySelectorAll('p');
    certificationElements.forEach(certification => {
        certification.contentEditable = false;
        certification.style.border = ''; // Reset the border style
    });

    // Make skills non-editable and reset border styles
    const skillElements = skillsContainer.querySelectorAll('p');
    skillElements.forEach(skill => {
        skill.contentEditable = false;
        skill.style.border = ''; // Reset the border style
    });

    // Make education non-editable and reset border styles
    const educationElements = educationContainer.querySelectorAll('p');
    educationElements.forEach(education => {
        education.contentEditable = false;
        education.style.border = ''; // Reset the border style
    });

    // Make work history non-editable and reset border styles
    const workElements = workHistoryContainer.querySelectorAll('p');
    workElements.forEach(work => {
        work.contentEditable = false;
        work.style.border = ''; // Reset the border style
    });

    // Disable save button and enable edit button
    const saveButton = document.getElementById('saveButton');
    saveButton.disabled = true;

    const editButton = document.querySelector('.edit-button');
    if (editButton) {
        editButton.disabled = false; // Enable the edit button again
    }

    // Remove the [+] and [-] buttons for both skills and certifications
    const addProfessionalSkillButton = document.querySelector('.add-professional-skill');
    const removeProfessionalSkillButton = document.querySelector('.remove-professional-skill');
    if (addProfessionalSkillButton) {
        addProfessionalSkillButton.remove(); // Remove the [+] button for skills
    }
    if (removeProfessionalSkillButton) {
        removeProfessionalSkillButton.remove(); // Remove the [-] button for skills
    }

    // For certifications: remove the [+] and [-] buttons
    const addCertificationButton = document.querySelector('.add-certification');
    const removeCertificationButton = document.querySelector('.remove-certification');
    if (addCertificationButton) {
        addCertificationButton.remove(); // Remove the [+] button for certifications
    }
    if (removeCertificationButton) {
        removeCertificationButton.remove(); // Remove the [-] button for certifications
    }

    // For skills: remove the [+] and [-] buttons
    const addSkillButton = document.querySelector('.add-skill');
    const removeSkillButton = document.querySelector('.remove-skill');
    if (addSkillButton) {
        addSkillButton.remove(); // Remove the [+] button for skills
    }
    if (removeSkillButton) {
        removeSkillButton.remove(); // Remove the [-] button for skills
    }

    // For education: remove the [+] and [-] buttons
    const addEducationButton = document.querySelector('.add-education');
    const removeEducationButton = document.querySelector('.remove-education');
    if (addEducationButton) {
        addEducationButton.remove(); // Remove the [+] button for education
    }
    if (removeEducationButton) {
        removeEducationButton.remove(); // Remove the [-] button for education
    }

    // For work history: remove the [+] and [-] buttons
    const addWorkButton = document.querySelector('.add-work');
    const removeWorkButton = document.querySelector('.remove-work');
    if (addWorkButton) {
        addWorkButton.remove(); // Remove the [+] button for work history
    }
    if (removeWorkButton) {
        removeWorkButton.remove(); // Remove the [-] button for work history
    }
}




// Close modal if clicked outside content
window.onclick = function (event) {
    const modal = document.getElementById('modal');
    if (event.target === modal) {
        closeModal();
    }
};
    </script>

<script>
// Attach an event listener to all status select elements
document.querySelectorAll('[id^="status_select_"]').forEach(selectElement => {
    selectElement.addEventListener('change', function() {
        const selectedStatus = this.value;  // Get the selected status
        const userId = this.id.replace('status_select_', '');  // Extract the user ID from the select ID

        // Log the selected status and user ID
        console.log(`User ID: ${userId}, Status: ${selectedStatus}`);

        // Show an alert with the user ID and selected status
        alert(`User ID: ${userId}\nStatus: ${selectedStatus}`);

        // Send the updated status to the server
        fetch('/update-status', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json', // Ensure the body is sent as JSON
            },
            body: JSON.stringify({
                userid: userId,
                status: selectedStatus,
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);  // Show success message
            } else {
                alert(data.message);  // Show error message
            }
        })
        .catch(error => {
            console.error('Error updating status:', error);
            alert('An error occurred. Please try again.');
        });
    });
});


</script>
</body>
</html>
