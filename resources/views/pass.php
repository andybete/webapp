<?php
$con = mysqli_connect("localhost", "root", "", "exam");

// Function to send the verification email
function sendVerificationEmail($email, $username) {
  // Email sending code
  $to = $email;
  $subject = 'Verification From COC agency';

  // HTML message with styled buttons
  $message = '<html><body>';
  $message .= '<p>Please verify:</p>';
  $message .= '<a href="#" onclick="handleVerification(\'yes\')" style="background-color: #4CAF50; color: white; padding: 10px 15px; border: none; text-decoration: none; display: inline-block; cursor: pointer;">Yes, it\'s me</a>';
  $message .= '<a href="#" onclick="handleVerification(\'no\')" style="background-color: #f44336; color: white; padding: 10px 15px; border: none; text-decoration: none; display: inline-block; cursor: pointer;">No, don\'t allow</a>';
  $message .= '</body></html>';

  $headers = 'From: southethcoc@gmail.com' . "\r\n";
  $headers .= 'Content-Type: text/html; charset=UTF-8' . "\r\n";

  // Send the email
  if (mail($to, $subject, $message, $headers)) {
    echo 'Verification email sent!';
  } else {
    echo 'Error occurred while sending email!';
  }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = $_POST['username']; // Assuming the username is submitted through a form field

  $query = "SELECT email FROM staff WHERE username = '$username'";
  $result = mysqli_query($con, $query);

  if (!$result || mysqli_num_rows($result) === 0) {
    // Username does not exist in the approvedcandidate table
    $err = "Username not found in table.";
  } else {
    $row = mysqli_fetch_assoc($result);
    $email = $row['email'];

    // Send the verification email
    sendVerificationEmail($email, $username);
    echo 'Verification email sent!';
  }
}
?>
<head>
  <title>Forgot Password</title>
  <style>
    .active {
      font-weight: bold;
    }
  </style>
  
</head>
<body>
<form method="POST">
  <h1>Forgot Password</h1>
  <p>
    <a href="#" id="emailLink" class="active" onclick="showEmailInput(); return false;">Search by Email</a> |
    <a href="#" id="usernameLink" onclick="showUsernameInput(); return false;">Search by Username</a>
  </p>

  <div id="emailInput">
    <label>Email:</label>
    <input type="email" name="email">
  </div>

  <div id="usernameInput" style="display: none;">
    <label>Username:</label>
    <input type="text" name="username">
  </div>
  <button type="submit">Submit</button>
</form>
  <script>
    function toggleFields() {
      var emailLink = document.getElementById("emailLink");
      var usernameLink = document.getElementById("usernameLink");
      var emailInput = document.getElementById("emailInput");
      var usernameInput = document.getElementById("usernameInput");

      emailInput.style.display = "none";
      usernameInput.style.display = "none";
      emailLink.classList.remove("active");
      usernameLink.classList.remove("active");
    }

    function showEmailInput() {
      toggleFields();
      var emailLink = document.getElementById("emailLink");
      var emailInput = document.getElementById("emailInput");

      emailInput.style.display = "block";
      emailLink.classList.add("active");
      emailInput.focus();
    }

    function showUsernameInput() {
      toggleFields();
      var usernameLink = document.getElementById("usernameLink");
      var usernameInput = document.getElementById("usernameInput");

      usernameInput.style.display = "block";
      usernameLink.classList.add("active");
      usernameInput.focus();
    }
	
  </script>
</body>
</html>