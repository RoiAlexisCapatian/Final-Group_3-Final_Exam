
// Check if the 'userid' is present in localStorage
const userid = localStorage.getItem('userid');
console.log("userid from localStorage:", userid); // Log for debugging

// If 'userid' is not found, redirect to the login page
if (!userid) {
    window.location.href = '/';
}

showresume(userid);
function showresume(userid) {
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
console.log(`showresume called with userid: ${userid}`);
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
