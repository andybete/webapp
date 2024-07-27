<?php
// Establish database connection
$con = mysqli_connect("localhost", "root", "", "exam");


?>
<?php 
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
        'list_institution' => '',
        'list_department' => '',
        'list_level' => '',
        'gcyear' => '',
        'add' => ''
    );
}

if($_SERVER["REQUEST_METHOD"]=="POST"){ 
     // Handle form submission and store form data in session variables
	$username = $_SESSION['form_data']['username'] =  $_POST["username"];
    $first_name = $_SESSION['form_data']['uname'] = $_POST['uname'];
    $father_name = $_SESSION['form_data']['fname'] = $_POST['fname'];
    $last_name = $_SESSION['form_data']['lname'] = $_POST['lname'];
	$username = $_SESSION['form_data']['username'] = $_POST['username'];
	$pass = $_SESSION['form_data']['pass'] = $_POST['pass'];
	$cpass = $_SESSION['form_data']['cpass'] = $_POST['cpass'];
    $gender = $_SESSION['form_data']['gend'] = $_POST['gend'];
    $phone_number = $_SESSION['form_data']['pno'] = $_POST['pno'];
    $email = $_SESSION['form_data']['email'] = $_POST['email'];
    $date_of_birth = $_SESSION['form_data']['date'] = $_POST['date'];
    $age = $_SESSION['form_data']['age'] = $_POST['age'];
	$address = $_SESSION['form_data']['add'] = $_POST['add'];
	$city = $_SESSION['form_data']['list_city'] = $_POST['list_city'];
    $institution = $_SESSION['form_data']['list_institution'] = $_POST['list_institution'];
    $department = $_SESSION['form_data']['list_department'] = $_POST['list_department'];
    $level = $_SESSION['form_data']['list_level'] = $_POST['list_level'];
    $gc_year = $_SESSION['form_data']['gcyear'] = $_POST['gcyear'];
	
    $file = $_FILES['file'];
	
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
	
		
    if(empty($email)){
		$erremail="Email is required";
	}else if(!preg_match(   "/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/", $email)) {
        $emailError = "Invalid email format";
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
	
	// Check if file uploaded successfully
    if ($file['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/'; // Directory to store uploaded files
        $fileName = $_FILES['file']['name']; // Get the uploaded file name

        // Specify the relative file path
        $fileDirectory = 'images/'; // Replace with the actual directory path
        $uploadPath = $fileDirectory . $fileName;

        $fileType = strtolower(pathinfo($uploadPath, PATHINFO_EXTENSION));

        // Validate file type
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'pdf']; // Add additional file types if needed
        if (in_array($fileType, $allowedTypes)) {
            // Move uploaded file to the desired location
            if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
                $_SESSION['uploadedFilePath'] = $uploadPath; // Store the relative file path
            } else {
                $errfile = "Error moving uploaded file.";
            }
        } else {
            $errfile = "Only JPG, JPEG, PNG, GIF, and PDF files are allowed.";
        }
    } else {
        $errfile = "Error uploading file: " . $file['error'];
    }

	
		
	if ($username && !empty($pass) && !empty($cpass) && $pass === $cpass && $vfirst_name && $vfather_name && $vlast_name && !empty($gender) && $vphone_number && !empty($email) && !empty($date_of_birth) && !empty($age) && !empty($institution) && !empty($department) && !empty($level) && !empty($gc_year) && !empty($address)) {
		 // Check if the username exists
    $query = "SELECT * FROM candidate WHERE username = ?";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
	
    $res = false; // Initialize the $res variable
    if (mysqli_num_rows($result) > 0) {
   	
        $erruser = "The username already exists in the database";
    } else {
		
        $sql = "INSERT INTO school_candidate (username, password, first_name, father_name, last_name, gender, phone_number, email, date_of_birth, age, address, city, institution, department, level, gc_year, grade_report) VALUES ('$username', '$pass', '$vfirst_name', '$vfather_name', '$vlast_name', '$gender', '$phone_number', '$email', '$date_of_birth', '$age', '$address', '$city', '$institution', '$department', '$level', '$gc_year', '$uploadPath')";
		$res = mysqli_query($con, $sql);
	}
		 if(!$res){
			   echo"failed to insert data";
		   }
		 else {
        session_destroy();
        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11.0.18/dist/sweetalert2.min.js'></script>";
        echo "<script>
            Swal.fire({
                title: 'Success!',
                text: 'You are successfully registered',
                icon: 'success',
                confirmButtonText: 'OK'
            }).then(function() {
                window.location.href = 'home.php';
            });
        </script>";
    }
	}
	else {
	echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11.0.18/dist/sweetalert2.min.js'></script>";
    echo "<script>
        Swal.fire({
            title: 'Error!',
            text: 'Please fill all required data correctly',
            icon: 'error',
            confirmButtonText: 'OK'
        });
    </script>";
}
} 
?>

<script>
/* automatically validate email*/
function validateEmail() {
  var emailInput = document.getElementById("email");
  var emailError = document.getElementById("emailError");
  var email = emailInput.value.trim();

  // Validate email format
  var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  if (email === "") {
    emailError.textContent = "";
  } else if (!emailPattern.test(email)) {
    emailError.textContent = "Invalid email format";
  } else {
    emailError.textContent = "";
  }
}
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
