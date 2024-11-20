<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    

    <link rel="icon" href="/bag.ico" type="image/x-icon">
    <link rel="stylesheet" href="/css/signin.css">
    <title>Sign In</title>
</head>
<body>
  <div class="wrapper">
    <form id="loginForm" method="POST" action="/login">
      @csrf
      <h2>Login</h2>

      <!-- Display the error message if it exists -->
      @if(session('error'))
        <div class="error-message" style="color: red; margin-bottom: 10px;">
          {{ session('error') }}
        </div>
      @endif

      <!-- Error message container for dynamic display -->
      <div id="errorMessage" class="error-message" style="color: red; display: none; margin-bottom: 10px;">
        <!-- Error message will be dynamically inserted here -->
      </div>

      <div class="input-field">
        <input type="text" id="username" name="username" required>
        <label>Enter your username</label>
      </div>
      <div class="input-field">
        <input type="password" id="password" name="password" required>
        <label>Enter your password</label>
      </div>
      <button type="submit">Log In</button>
    </form>
  </div>

  <script>
    document.getElementById('loginForm').addEventListener('submit', function(event) {
      event.preventDefault(); // Prevent the default form submission

      // Get the form data
      let username = document.getElementById('username').value;
      let password = document.getElementById('password').value;

      // Send data using Fetch API (AJAX)
      fetch('/login', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    },
    body: JSON.stringify({
        username: username,
        password: password
    })
})
.then(response => response.json())
.then(data => {
    if (data.success) {
      localStorage.setItem('userid', data.userid);  // Store the user ID in localStorage

// Check the usertype and display an alert before redirecting
if (data.usertype === 'Admin') {
    alert('Username: ' + data.username + ' - You will be redirected to the dashboard.');
    window.location.href = '/dashboard';  // Redirect to the dashboard for Admin users
} else if (data.usertype === 'Standard') {
    alert('Username: ' + data.username + ' - You will be redirected to the resume page.');
    window.location.href = '/resume/' + data.username;  // Redirect to the resume page for Standard users
}



    } else {
        document.getElementById('errorMessage').textContent = data.error || 'Login failed';
        document.getElementById('errorMessage').style.display = 'block';
    }
})
.catch(error => {
    console.error('Error:', error);
    alert('There was an error with the login process.');
});


    });
  </script>
</body>
</html>
