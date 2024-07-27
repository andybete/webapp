<?php
@include("header");
$con = mysqli_connect("localhost", "root", "", "exam");

// Function to send the verification email
function sendVerificationEmail($email, $username) {
  // Email sending code
  $to = $email;
  $subject = 'Verification From COC agency';

  // HTML message with styled buttons
  $message = '<html><body>';
  $message .= '<p>Please verify:</p>';
  $message .= '<button style="background-color: #4CAF50; color: white; padding: 10px 15px; border: none; text-decoration: none; cursor: pointer;">';
  $message .= '<a href="http://localhost:8080/news/get-password.php?username='.$username.'" style="text-decoration: none; color: white;">';
  $message .= 'Yes, it\'s me';
  $message .= '</a>';
  $message .= '</button>';

  $message .= '<a href="#" onclick="handleVerification(\'no\')" style="background-color: #f44336; color: white; padding: 10px 15px; border: none; text-decoration: none; display: inline-block; cursor: pointer;">';
  $message .= 'No, don\'t allow';
  $message .= '</a>';
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
	 if (isset($_POST['username'])) {
  $username = $_POST['username']; // Assuming the username is submitted through a form field

  $query = "SELECT email FROM staff WHERE username = '$username'";
  $result = mysqli_query($con, $query);
  
  $query = "SELECT email FROM approvecandidate WHERE username = '$username'";
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
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	 if (isset($_POST['email'])) {
		$email = $_POST['email']; 
		$first_name = $_POST['first_name']; 
		$father_name = $_POST['father_name']; 
		$last_name = $_POST['last_name']; 
		
		$query = "SELECT username FROM staff WHERE email = '$email' AND first_name = '$first_name' AND father_name = '$father_name' AND last_name = '$last_name'";
        $result = mysqli_query($con, $query);
		
		$query = "SELECT username FROM approvecandidate WHERE email = '$email' AND first_name = '$first_name' AND father_name = '$father_name' AND last_name = '$last_name'";
        $result = mysqli_query($con, $query);
		
		if (!$result || mysqli_num_rows($result) === 0) {
         // User not found in the staff table with the provided details
         $err = "User not found.";
        } else {
          $row = mysqli_fetch_assoc($result);
          $username = $row['username'];

      // Send the verification email
      sendVerificationEmail($email, $username);
      echo 'Verification email sent!';
    }
  }
}
?>
<head>
  <title>Forgot Password</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <style>
    .active {
      font-weight: bold;
    }
	.pass{
	    border: 1px solid f4f5ff;
		border-radius:10px;
		background-color:#f4f5ff;#123456;
		height: 80%;
		width:60%;
		margin:60px 10px 20px 150px;
		padding-left:20px;
		text-align:center;
	  }
	  h1{
		  font-size:50px;
	  }
	  .icon-circle {
    display: inline-block;
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background-color: #ccc;
    text-align: center;
    line-height: 65px;
  }

  .icon-circle i {
    color: #fff;
    font-size: 50px;
	margin-top:10px;
  }
  p{
	  font-size:20px;
  }
  .field input[type=text]{
  width:25%;
  border-radius:10px;
  padding:6px;
  border: 1px solid #E7DFD7;
  box-shadow: 0px 0px 1px 1px;
  margin:5px;
  }
  .field input[type=email]{
  width:20%;
  border-radius:10px;
  padding:6px;
  border: 1px solid #E7DFD7;
  box-shadow: 0px 0px 1px 1px;
  margin:5px;
  }
  .sub{
		background-color: #4CAF50;
	    color: white;
		margin:20px 170px;
        padding: 10px 20px;
        border: none;
        border-radius: 10px;
        cursor: pointer;
        font-size: 16px;
		}
	.sub:hover {
        background-color: red;
    }
  </style>
  
</head>
<body>
<div id="site_content">	
<div class="pass">
<form method="POST">
  <h1>Forgot Password<hr></h1>
  <div class="icon-circle">
  <i class="fas fa-user"></i>
</div>
  
  <p>
    <a href="#" id="usernameLink" onclick="showUsernameInput(); return false;">Search by Username</a> |
    <a href="#" id="emailLink" class="active" onclick="showEmailInput(); return false;">Search by Email</a> 
  </p>
<div class="field">
  <div id="emailInput">
  <label>Email:</label>
  <input type="email" name="email"><br>
  <!-- Additional fields for email option -->
  <label>First Name:</label>
  <input type="text" name="first_name"><br>
  <label>Father's Name:</label>
  <input type="text" name="father_name"><br>
  <label>Last Name:</label>
  <input type="text" name="last_name"><br>
</div>

  <div id="usernameInput" style="display: none;">
    <label>Username:</label>
    <input type="text" name="username">
  </div>
  <button type="submit" class="sub">Submit</button>
  </div>
</form>
</div>
</div>
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

  // Initially show the username option as selected by default
  showUsernameInput();
</script>
</body>
</html>
<?php include("footer.php");?>