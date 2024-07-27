<?php
session_start();

// Retrieve the username from the query string
$username = $_GET['username'];
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

            // Check if the current password matches the stored password
            if ($currentPassword === $storedPassword) {
                echo "Correct current password.";
            } else {
                echo "Incorrect current password.";
            }
        } else {
            echo "Username not found.";
        }
    } else {
        echo "Error executing query: " . mysqli_error($con);
    }
}
?>
<head>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css"> 
</head>

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

    <label for="password">Password:</label>
    <i class="fa-solid fa-eye" id="eye"></i>
    <input type="password" name="pass" id="pass" value="<?php echo isset($_SESSION['pass']) ? $_SESSION['pass'] : ''; ?>">
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
    <input type="password" name="cpass" id="cpass" value="<?php echo isset($_SESSION['cpass']) ? $_SESSION['cpass'] : ''; ?>">
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

    <button type="submit" name="submit">Change Password</button>
</form>

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
</script>