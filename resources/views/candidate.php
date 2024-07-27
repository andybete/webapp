<?php
// Retrieve the username from the query string
$username = $_GET['username'];
$err = ""; 

session_start();


// Retrieve the username from the query string

$con = mysqli_connect("localhost", "root", "", "exam");

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $newPassword = $_SESSION['pass'] = $_POST['pass'];
    $confirmPassword = $_SESSION['cpass'] = $_POST['cpass'];
    $currentPassword = $_SESSION['current_password'] = $_POST['current_password'];

    // Fetch the stored password from the database based on the username
    $query = "SELECT password FROM approvecandidate WHERE username='$username'";
    $result = mysqli_query($con, $query);

    if ($result) {
    $row = mysqli_fetch_assoc($result);

    if ($row) {
        $storedPassword = $row['password'];
		if($currentPassword !== $storedPassword){
			echo "<script>alert('Incorrect current password.');</script>";
		}
		else if($newPassword !== $confirmPassword){
			echo "<script>alert('Please enter the same password in both fields for confirmation.');</script>";
		}
		else if($newPassword === $storedPassword){
			 echo "<script>alert('The new password is similar with the stored password.');</script>";
		}
        else {
             // Update the "password" column with the new password
            $query = "UPDATE approvecandidate SET password = '$newPassword' WHERE username = '$username'";
             // Execute the query and handle any errors
            $result = mysqli_query($con, $query);
            if ($result) {
               echo "<script>alert('Password updated successfully.');</script>";
			   unset($_SESSION['pass']);
			   unset($_SESSION['cpass']);
			   unset($_SESSION['current_password']);
            } else {
                echo "<script>alert('Failed to update password.');</script>";
            }
        }   
    }
    }
}
?>


<head>
    <link rel="stylesheet" type="text/css" href="candidate.css" />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css"> 
	<style>
    .hidden {
        display: none;
    }
</style>
</head>

<body>
    <div id="header">
        <img src="images/sam1.png">
    </div>
    <div id="navigation">
        <ul>
            <li><a href="candidate.php?username=<?php echo urlencode($username); ?>">Home</a></li>
			<li>
              <a href="#">Take Exam</a>
              <ul class="sub-menus">
			   <li><a href="take_exam.php?username=<?php echo urlencode($username); ?>">Take Theory exam</a></li><br>
			   <li><a href="take_practical_exam.php?username=<?php echo urlencode($username); ?>">Take Practical exam</a></li><br>
              </ul>
            </li>
			<li><a href="takes.php?username=<?php echo urlencode($username); ?>">View Result</a></li>
			<li><a href="take_exam.php">Registeration</a></li>
            <li><a href="logout.php">Log Out</a></li>
        </ul>
    </div>
    <div id="site_content">
	<div class="container">
	   <h3> Welcome To SNNPRS COC Page  </h3>
	   <h2> From: SNNPRS Occupational <br> Competency Accreditation Agency </h2>
	   <h1> To change your password <span class="downward-icon"></span> </h1>
	   
	   <div class="list"><a href="#" id="changePasswordLink">Change password</a></div>
	</div>
    
  <div id="passwordFormContainer" class="hidden">
    <div class="form_container">
     <!--insert user account form-->
     <h4> Change password</h4>
      <div class="form">
    
	<form method="POST" action="">
    <label for="current_password">Current Password:</label>
	<i class="fa-solid fa-eye" id="eye3"></i>
    <input type="password" id="current_password" name="current_password" value="<?php echo isset($_SESSION['current_password']) ? $_SESSION['current_password'] : ''; ?>" required><br>
	<script>
    // Get reference to the toggle password button and the password input field
    const togglePassword3 = document.getElementById('eye3');
    const passwordInput3 = document.getElementById('current_password');

    // Add click event listener to toggle password visibility
    togglePassword3.addEventListener('click', function() {
        const type = passwordInput3.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput3.setAttribute('type', type);
        togglePassword3.classList.toggle('fa-eye-slash');
    });
     </script>
	 <?php echo "<font color='red'>$err </font>"; ?><br>

    <label for="password">Password:</label>
    <i class="fa-solid fa-eye" id="eye"></i>
    <input type="password" name="pass" id="pass" value="<?php echo isset($_SESSION['pass']) ? $_SESSION['pass'] : ''; ?>" required><br>
    <span id="passError" style="color: red;"></span><br>
	<script>
    // Get reference to the toggle password button and the password input field
    const togglePassword = document.getElementById('eye');
    const passwordInput = document.getElementById('pass');

    // Add click event listener to toggle password visibility
    togglePassword.addEventListener('click', function() {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        togglePassword.classList.toggle('fa-eye-slash');
    });
     </script>

    <label for="cpassword">Confirm Password:</label>
    <i class="fa-solid fa-eye" id="eye2"></i>
    <input type="password" name="cpass" id="cpass" value="<?php echo isset($_SESSION['cpass']) ? $_SESSION['cpass'] : ''; ?>" required>
    <span id="cpassError" style="color: red;"></span><br>
	<script>
    // Get reference to the toggle password button and the password input field
    const togglePassword2 = document.getElementById('eye2');
    const passwordInput2 = document.getElementById('cpass');

    // Add click event listener to toggle password visibility
    togglePassword2.addEventListener('click', function() {
        const type = passwordInput2.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput2.setAttribute('type', type);
        togglePassword2.classList.toggle('fa-eye-slash');
    });
     </script>

    <button type="submit" name="submit" class="submit-button">Change Password</button>
</form>
</div>
</div>
</div>
<div id="cont_list">
     <h2> SNNPRS COC Agency </h2>
	 <h3> Please read these instructions carefully </h3>
	 <p>I</P>
	 <p>II</P>
	 <p>III</P>
  </div>
  </div>

<script>
	
	// Get reference to the password field and the error message container
  const passwordField = document.getElementById('pass');
  const passwordError = document.getElementById('passError');

  // Add an input event listener to the password field
  passwordField.addEventListener('input', function() {
    const password = passwordField.value;

    if (password.length < 8) {
      // Password is less than 8 characters
      passwordError.textContent = 'The password must be at least 8 characters long';
    } else if (!/(?=.*[a-zA-Z])(?=.*\d)/.test(password)) {
      // Password does not contain both letters and numbers
      passwordError.textContent = 'The password must contain a combination of letters and numbers';
    } else {
      // Password is valid
      passwordError.textContent = '';
    }
  });
  
  // Get references to the password and confirm password fields
  const confirmPasswordField = document.getElementById('cpass');
  const confirmPasswordError = document.getElementById('cpassError');

  // Add an input event listener to the confirm password field
  confirmPasswordField.addEventListener('input', function() {
    const password = passwordField.value;
    const confirmPassword = confirmPasswordField.value;

    if (password !== confirmPassword) {
      // Password and confirm password do not match
      confirmPasswordError.textContent = 'The password and confirm password do not match';
    } else {
      // Passwords match or confirm password field is empty
      confirmPasswordError.textContent = '';
    }
  });

    
    // Get reference to the "Change password" link
    const changePasswordLink = document.querySelector("#changePasswordLink");

    // Get reference to the form container and cont_list
    const passwordFormContainer = document.getElementById("passwordFormContainer");
    const contList = document.getElementById("cont_list");

    // Add click event listener to toggle form visibility
    changePasswordLink.addEventListener("click", function(event) {
        event.preventDefault(); // Prevent the default link behavior

        contList.classList.add("hidden");
        passwordFormContainer.classList.remove("hidden");
    });

</script>


    
 

  <?php
  include("footer.php");
  ?>
</body>