<?php
@include("header");
@include("formvalid");
?>

<head>
   <link rel="stylesheet" type="text/css" href="{{ asset('registeration.css') }}" />
   <link rel="stylesheet" href="{{ asset('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css') }}">
   <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js') }}"></script>
   <!-- Include SweetAlert CSS -->
    <link rel="stylesheet" href="{{ asset('https://cdn.jsdelivr.net/npm/sweetalert2@11.0.18/dist/sweetalert2.min.css') }}">
</head>
<body>
<form method="POST" enctype="multipart/form-data">
    <div id="register">
	<h3>Registeration for new student</h3>
   <fieldset><legend>personal detail</legend>
    <div id="current_date">
     <script>
        date = new Date();
        year = date.getFullYear();
        month = date.getMonth() + 1;
        day = date.getDate();
        document.getElementById("current_date").innerHTML ="date:"+ day + "/" + month + "/" + year;
     </script></div>
    <div class="register_item">
	
	<label for="username">Username:</label>
        <input type="text" name="username" id="username" placeholder="Username" oninput="checkUsername()" value="<?php echo isset($_SESSION['form_data']['username']) ? $_SESSION['form_data']['username'] : ''; ?>">
        <span id="usernameError" style="color: red;"></span>
		<?php echo "<font color='red'>$erruser </font>"; ?><br>
   
	<label for="password">Password:</label>
	    <i class="fa-solid fa-eye" id="eye"></i>
        <input type="password" name="pass" id="pass" value="<?php echo $_SESSION['form_data']['pass']; ?>">
		
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
        <input type="password" name="cpass" id="cpass" value="<?php echo isset($_SESSION['form_data']['cpass']) ? $_SESSION['form_data']['cpass'] : ''; ?>">
        <span id="cpassError" style="color: red;"></span>
		<?php echo "<font color='red'>$cpassError </font>"; ?><br>
		
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
	<label for="name">Full name:</label><br>
        <input type="text" name="uname" placeholder="first name" value="<?php echo $_SESSION['form_data']['uname']; ?>">
        <input type="text" name="fname" placeholder="father name" value="<?php echo $_SESSION['form_data']['fname']; ?>">
        <input type="text" name="lname" placeholder="grandfather name" value="<?php echo $_SESSION['form_data']['lname']; ?>">
            <?php echo "<font color='red'>$errn </font>"; ?><?php echo "<font color='red'>$nameError </font>"; ?><br>
	
	<label for="gend">Gender:</label>
        <input type="radio" name="gend" value="male" <?php if ($_SESSION['form_data']['gend']=== 'male') echo 'checked'; ?>>Male
        <input type="radio" name="gend" value="female" <?php if ($_SESSION['form_data']['gend']=== 'female') echo 'checked'; ?>>female 
		    <?php echo "<font color='red'>$errgend </font>"; ?><br>
	 
	 
	 
	<label for="pno">phone number:</label>
        <input type="tel" name="pno" value="<?php echo $_SESSION['form_data']['pno']; ?>"placeholder="0912345678">
		    <?php echo "<font color='red'>$errpno </font>"; ?>
            <?php echo "<font color='red'>$pnoError </font>"; ?><br>
                
	<label for="email">Email:</label>
        <input type="email" name="email" id="email" value="<?php echo $_SESSION['form_data']['email']; ?>" placeholder="abc@gmail.com" oninput="validateEmail()">
            <span id="emailError" style="color: red;"></span>
            <?php echo "<font color='red'>$erremail </font>"; ?><br>             
			 
	<label for="date">Date of Birth:</label>
        <input type="date" name="date" id="birthdate" value="<?php echo $_SESSION['form_data']['date']; ?>" onchange="calculateAge()">
		<?php echo "<font color='red'>$errdate </font>"; ?><br>

    <label for="age">Age:</label>
        <input type="number" name="age" id="age" placeholder="Enter Age" min="18" max="90" readonly value="<?php echo calculateAgeFromDOB($_SESSION['form_data']['date']); ?>"><br>
			<?php
			// calculate age from date of birth
    // function calculateAgeFromDOB($dob) {
    //     if (!empty($dob)) {
    //     $today = new DateTime();
    //     $birthdate = new DateTime($dob);
    //     $age = $birthdate->diff($today)->y;
    //  return $age;
    // }
    //  return '';
    // }
           ?>
	<label for="add">Address:</label>
        <textarea name="add" cols="30" rows="1" placeholder="zone, kefle ketema, kebele"><?php echo $_SESSION['form_data']['add']; ?></textarea>
		<?php echo "<font color='red'>$erradd </font>"; ?><br><br>
                
		<?php include("fetch.php");?>
			<?php echo "<font color='red'>$errinst </font>"; ?><br>
			
    <label for="gcyear">Graduation Year:</label>
        <input type="date" name="gcyear" value="<?php echo $_SESSION['form_data']['gcyear']; ?>"><?php echo "<font color='red'>$errgcyear </font>"; ?><br><br>
		
	<label for="file">Grade Report: </label>
            <input type="file" name="file"><br>
           
			
    <input type="submit" name="submit" value="submit">
    <input type="reset" value="clear"><br>
	  
	</div>
	</fieldset>
  </div>
</form>
<script>
       function checkUsername() {
           var username = document.getElementById('username').value;
           var usernameError = document.getElementById('usernameError');

       // Reset the error message
       usernameError.textContent = '';

    if (username.trim() !== '') {
        $.ajax({
            url: 'formvalid.php',
            type: 'POST',
            data: {username: username},
            success: function(response){
                if (response === 'Available') {
                    // Username is available
                    usernameError.textContent = '';
                } else {
                    // Username is not available
                    usernameError.textContent = 'The username already exists in the database';
                }
            }
        });
    }
        }
		
	
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
</body>
@include("footer");


