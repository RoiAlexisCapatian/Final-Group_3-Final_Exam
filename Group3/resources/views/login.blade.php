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
        // Check if the login is successful
        if (data.success) {
          // Store the user ID in localStorage (if successful)
          localStorage.setItem('userid', data.userid);
          console.log("User ID stored in localStorage:", localStorage.getItem('userid'));

          // Redirect to the dashboard
          window.location.href = '/dashboard';
        } else {
          // Display the error message in the error message container
          let errorMessageContainer = document.getElementById('errorMessage');
          errorMessageContainer.textContent = data.error || 'Login failed';
          errorMessageContainer.style.display = 'block'; // Show the error container
        }
      })
      .catch(error => {
        console.error('Error:', error);
        alert('There was error with the login process.');
      });
    });
  </script>
</body>
</html>
