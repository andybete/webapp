<?php
// Establish database connection
$con = mysqli_connect("localhost", "root", "", "exam"); 
$username = $_GET['username'] ?? '';
session_start();
   $erruser=$errpass=$errn=$errgend=$errn=$errpno=$errdate=$erremail=$errinst=$errsect=$errlev=$errgcyear=$errfile=$erradd=" ";
   $passError=$cpassError=$nameError=$pnoError=$emailError="";
   $vfirst_name=$vfather_name=$vlast_name=$vphone_number="";
   
   
   
   // Initialize session variables if they don't exist
if (!isset($_SESSION['form_data'])) {
    $_SESSION['form_data'] = array(
        'uname' => '',
        'fname' => '',
        'lname' => '',
		'username' => '',
        'pass' => '',
        'gend' => '',
        'pno' => '',
        'email' => '',
        'date' => '',
        'age' => '',
		'list_city' => '',
        'gcyear' => '',
        'add' => ''
    );
}

if($_SERVER["REQUEST_METHOD"]=="POST"){ 
     // Handle form submission and store form data in session variables
	$pass = $_SESSION['form_data']['pass'] = $_POST['pass'];
	$cpass = $_SESSION['form_data']['cpass'] = $_POST['cpass'];
    $first_name = $_SESSION['form_data']['uname'] = $_POST['uname'];
    $father_name = $_SESSION['form_data']['fname'] = $_POST['fname'];
    $last_name = $_SESSION['form_data']['lname'] = $_POST['lname'];
    $gender = $_SESSION['form_data']['gend'] = $_POST['gend'];
    $phone_number = $_SESSION['form_data']['pno'] = $_POST['pno'];
    $date_of_birth = $_SESSION['form_data']['date'] = $_POST['date'];
    $age = $_SESSION['form_data']['age'] = $_POST['age'];
	$city = $_SESSION['form_data']['list_city'] = $_POST['list_city'];
	$address = $_SESSION['form_data']['add'] = $_POST['add'];
	 

	
	if(empty($pass)){
		$errpass="password is required";
	}else if(strlen($pass) < 8) {
    // Password is less than 8 characters
        $passError="The password must be at least 8 characters long";
    } else if (!preg_match("/^(?=.*[a-zA-Z])(?=.*\d).+$/", $pass)) {
    // Password does not contain both letters and numbers
        $passError="The password must contain a combination of letters and numbers";
    }

	if($pass !== $cpass){
      $cpassError = "Passwords do not match";
	}
	if (empty($first_name) || empty($father_name) || empty($last_name)){
        $errn = "Full name is required";
    } else if (!preg_match("/^[a-zA-Z ]*$/", $first_name) || !preg_match("/^[a-zA-Z ]*$/", $father_name) || !preg_match("/^[a-zA-Z ]*$/", $last_name)) {
        $nameError = "Only alphabets are allowed";
    }
	else{
		$vfirst_name=$first_name;
		$vfather_name=$father_name;
		$vlast_name=$last_name;
	}
	
	if(empty($gender)){
		$errgend="gender is required";
	}	
	 
	if(empty($phone_number)){
		$errpno="phone_number is required";
	}else if(!preg_match ("/^[0-9]*$/", $phone_number)){
		$pnoError="only numeric value is allowed";
	}else if (!preg_match("/^09\d{8}$/", $phone_number)) {
        $pnoError = "Please enter a 10-digit phone number starting with '09'";
    }
	else{
		$vphone_number=$phone_number;
	}
	
		
			
	if(empty($date_of_birth)){
		$errdate="Date of birth is required";
	}
	
	if ($age < 18) {
          $errdate = "Your age is incorrect. Please fill in the date of birth correctly.";
    }
	if(empty($address)){
		$erradd="Address is required";
	}
	 
	if(empty($institution)&& empty($department)&& empty($level)){
		$errinst=" please select the data correctly";
	}
	 
	if(empty($gc_year)){
		$errgcyear="Graduation Year is required";
	}
	if(empty($file)){
		$errfile="File is required";
	}
		
	if (!empty($pass) && !empty($cpass) && $pass === $cpass && $vfirst_name && $vfather_name && $vlast_name && !empty($gender) && $vphone_number && !empty($date_of_birth) && !empty($age) && !empty($city) && !empty($address)) {
		
        // Username does not exist, perform the data insertion
      
		$sql = "UPDATE staff SET password = '$pass', first_name = '$vfirst_name', father_name = '$vfather_name', last_name = '$vlast_name', gender = '$gender', phone_number = '$phone_number', date_of_birth = '$date_of_birth', age = '$age', address = '$address', city = '$city', activate = 'processed'
		WHERE username = '$username'";
       
		$res = mysqli_query($con, $sql);

		 if(!$res){
			   echo"failed to insert data";
		   }
		   else{
			   session_destroy();
			   echo "<script>alert('Your data has been successfully inserted.Please wait for admin to approve'); window.location.href = 'home.php';</script>";
	        }
	
	}
	else{
		echo "<script>alert('please fill all required data correctly');</script>";
	    }
}
  
   
?>
<head>
   <link rel="stylesheet" type="text/css" href="registeration.css" />
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>
<form method="POST">
    <div id="register">
	<h3>Fill your data</h3>
   <fieldset><legend>personal detail</legend>
    <div class="register_item">
	
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
			 
	<label for="date">Date of Birth:</label>
        <input type="date" name="date" id="birthdate" value="<?php echo $_SESSION['form_data']['date']; ?>" onchange="calculateAge()">
		<?php echo "<font color='red'>$errdate </font>"; ?><br>

    <label for="age">Age:</label>
        <input type="number" name="age" id="age" placeholder="Enter Age" min="18" max="90" readonly value="<?php echo calculateAgeFromDOB($_SESSION['form_data']['date']); ?>"><br>
			<?php
			// calculate age from date of birth
    function calculateAgeFromDOB($dob) {
        if (!empty($dob)) {
        $today = new DateTime();
        $birthdate = new DateTime($dob);
        $age = $birthdate->diff($today)->y;
     return $age;
    }
     return '';
    }
           ?>
	<label for="add">Address:</label>
        <textarea name="add" cols="30" rows="1" placeholder="zone, kefle ketema, kebele"><?php echo $_SESSION['form_data']['add']; ?></textarea>
		<?php echo "<font color='red'>$erradd </font>"; ?><br><br>
                
		City:
           <select name="list_city">
              <option value="">Select city</option>
                <?php
                  // Fetch city
                    $cityQuery = "SELECT DISTINCT list_city FROM city_list";
                    $cityResult = $con->query($cityQuery);

                    if ($cityResult->num_rows > 0) {
                        while ($cityRow = $cityResult->fetch_assoc()) {
                        $cityName = $cityRow['list_city'];
                        $selected = ($_SESSION["selected_city"] == $cityName) ? "selected" : "";
                        echo "<option value='" . $cityName . "' " . $selected . ">" . $cityName . "</option>";
                    }
                    }
                ?>
          </select>
			<?php echo "<font color='red'>$errinst </font>"; ?><br>
			
    <input type="submit" name="submit" value="submit">
    <input type="reset" value="clear"><br>
	  
	</div>
	</fieldset>
  </div>
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
/* calculate age from date of birth*/
function calculateAge() {
  var birthdate = new Date(document.getElementById("birthdate").value);
  var today = new Date();
  var age = today.getFullYear() - birthdate.getFullYear();
  if (today.getMonth() < birthdate.getMonth() || (today.getMonth() === birthdate.getMonth() && today.getDate() < birthdate.getDate())) {
    age--;
  }
  document.getElementById("age").value = age;
}
</script>
