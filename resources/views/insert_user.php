<?php
session_start(); // Start the session
@include("headOfAdmin");
$con = mysqli_connect("localhost", "root", "", "exam");
$err = $errf =""; 
$res = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") { 
    if (isset($_POST['insert'])){
    $username = $_SESSION['uname'] = $_POST["uname"];
    $pass = $_SESSION['pass'] = $_POST['pass'];
    $cpass = $_SESSION['cpass'] = $_POST['cpass'];
    $email = $_SESSION['email'] = $_POST['email'];
	$role = $_SESSION['role'] = $_POST['role'];
    
    if (empty($username) || empty($pass) || empty($cpass) || empty($email)) {
			echo "<script>alert('All data is required. please fill all required date'); </script>";
        } elseif ($pass !== $cpass) {
            $err = "Passwords do not match";
        } else {
		// Check if the username exists
          $query = "SELECT username FROM staff WHERE username = '$username'";
          $result = mysqli_query($con, $query);

           if ($result) {
                if (mysqli_num_rows($result) > 0) {
                     // Username exists in the staff table
                    $err = "Username exists in the staff table.";
           } else {
                 // Username doesn't exist in the staff table
          $query = "SELECT username FROM approvedcandidate WHERE username = '$username'";
          $result = mysqli_query($con, $query);

          if ($result && mysqli_num_rows($result) > 0) {
            // Username exists in the approvecandidate table
            $err = "Username exists in the approvedcandidate table.";
        } else {
			  // Username does not exist, perform the data insertion
               $sql = "INSERT INTO staff (username, password, email, role, activate) VALUES ('$username', '$pass', '$email', '$role', 0)";
       
		       $res = mysqli_query($con, $sql);
			  
	        }
		   }
			    if(!$res){
					 $insertSuccess = false;
					$errf = "Failed to insert data: " . mysqli_error($con);
			     
		        }
		        else{
				  $insertSuccess = true;
				  
				  // Send email with username and password
                $to = $email;
                $subject = 'Registration Details From COC agency';
                $message = " Welcome to south Ethipopia reginal state COC agency. This is your username and password please change your password after login to security issue\n Username: $username\nPassword: $pass";
                $headers = 'From: southethcoc@gmail.com' . "\r\n";

				if (mail($to, $subject, $message, $headers)) {
                    //echo "Email sent successfully!";
					echo "<script>alert('Your record has been successfully inserted and '); </script>";
				  
                } else {
                    $errf = "Failed to send email.";
                }
			      
	        }
		}
	}
	}
}

      
	  // to deactivate the user
			// Check if the form was submitted
        if (isset($_POST['deactivate']) && isset($_POST['username'])) {
            // Retrieve the selected name from the $_POST array
            $selectedName = $_POST['username'];
			$deactivateDate = date('Y-m-d');

            // Update the activate column in the staff table
            $updateQuery = "UPDATE staff SET activate = 'deactivate', deactivated_date = '$deactivateDate' WHERE CONCAT(first_name, ' ', father_name, ' ', last_name) = ?";
            $stmt = $con->prepare($updateQuery);
            $stmt->bind_param('s', $selectedName);
            $stmt->execute();

        // Check if the update was successful
        if ($stmt->affected_rows > 0) {
			echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11.1.2'></script>";
			echo "<script>
               Swal.fire({
               icon: 'success',
               title: 'User deactivated successfully.',
               showConfirmButton: true,
               timer: 3000
               });
            </script>";
        } else {
            $errf = "Failed to deactivate user.";
        }

        $stmt->close();
        }
		
		if (isset($_POST['reactivate']) && isset($_POST['username'])) {
            // Retrieve the selected name from the $_POST array
            $selectedName = $_POST['username'];
			
            // Update the activate column in the staff table
            $updateQuery = "UPDATE staff SET activate = 'activate' WHERE CONCAT(first_name, ' ', father_name, ' ', last_name) = ?";
            $stmt = $con->prepare($updateQuery);
            $stmt->bind_param('s', $selectedName);
            $stmt->execute();

        // Check if the update was successful
        if ($stmt->affected_rows > 0) {
			echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11.1.2'></script>";
			echo "<script>
               Swal.fire({
               icon: 'success',
               title: 'User reactivated successfully.',
               showConfirmButton: true,
               timer: 3000
               });
            </script>";
			
        } else {
            $errf = "Failed to reactivate user.";
        }

        $stmt->close();
        }
		
		
		
?>
<head>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
</head>
<body>
<div id="container">
	<div class="list">
	   <h3> Welcome To Admin Page  </h3>
	   <h2> SOUTH ETHIOPIA Occupational <br> Competency Accreditation Agency </h2>
	   <select class="but" onchange="toggleForm(this)">
	       <option disabled selected>Account Actions</option>
	       <option value="insert_user.php">Insert User Account</option>
		   <option value="view_user.php">View user</option>
	       <option value="deactivate_user.php">Deactivate User Account</option>
		   <option value="reactivate_user.php">Reactivate User Account</option>
	   </select>
	   </select><br>
	   <?php echo "<font color='red' font size = '6' style='margin-left: 20px;'>$errf </font>"; ?>
	</div>
  <form method="POST">
  <div id="insert-user-form" style="display: none;">
  <div class="form_container">
     <!--insert user account form-->
     <h4>Insert new user</h4>
      <div class="form">
        <form method="post">
		  User Name<input type="text" name="uname" value="<?php echo isset($_SESSION['uname']) ? $_SESSION['uname'] : ''; ?>"><br>
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
        <span id="cpassError" style="color: red;"></span>
		
		
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
     </script><br>
          <label for="email">Email:</label>
		  <input type="email" name="email" id="email" value="<?php echo isset($_SESSION['email']) ? $_SESSION['email'] : ''; ?>" placeholder="abc@gmail.com" oninput="validateEmail()">
            <span id="emailError" style="color: red;"></span><br><br>
			
		Role:<select class="select" name="role">
	       <option disabled selected>Role of the user</option>
	       <option value="EPS" name="role">Exam preparation staff</option>
	       <option value="EES" name="role">Exam evaluation staff</option>
	   </select><br><?php echo "<font color='red'>$errf </font>"; ?>
	   <?php echo "<font color='red'>$err </font>"; ?>
          <input type="submit" name="insert" value="Insert" class="submit-button1"/>
        </form>
      </div>
    </div>
  </div>
  <div id="deactivate_user" style="display: none;">
  <div class="form_container">
       <h4>Deactivate User</h4>
	    <form action="" method="POST">
        <div class="form">
		<h3> please select the name of the user to deactivate the user.<br>This page deactivate the user from the system this user can't use the system.</h3>
        Full name:<select name="username">
            <option value="">Select Name</option>
            <?php
               // Fetch staff names
            $staffQuery = "SELECT first_name, father_name, last_name FROM staff WHERE activate = 'activate'";
            $staffResult = $con->query($staffQuery);

            if ($staffResult->num_rows > 0) {
                while ($staffRow = $staffResult->fetch_assoc()) {
                $firstName = $staffRow['first_name'];
                $fatherName = $staffRow['father_name'];
                $lastName = $staffRow['last_name'];
                $selected = ($_SESSION["selected_city"] == $firstName . ' ' . $fatherName . ' ' . $lastName) ? "selected" : "";
                echo "<option value='" . $firstName . ' ' . $fatherName . ' ' . $lastName . "' " . $selected . ">" . $firstName . ' ' . $fatherName . ' ' . $lastName . "</option>";
                }
            }
            ?>
        </select><br>
		<input type="submit" name="deactivate" value="Deactivate" class="submit-button">
		</div>
		</form>
  </div>
  </div>
  <div id="reactivate_user" style="display: none;">
  <div class="form_container">
       <h4>Reactivate User</h4>
	    <form action="" method="POST">
        <div class="form">
		<h3> please select the name of the user to reactivate the user.<br>This page reactivate the user from the system this user can use the system.</h3>
        Full name:<select name="username">
            <option value="">Select Name</option>
            <?php
               // Fetch staff names
            $staffQuery = "SELECT first_name, father_name, last_name FROM staff WHERE activate = 'deactivate'";
            $staffResult = $con->query($staffQuery);

            if ($staffResult->num_rows > 0) {
                while ($staffRow = $staffResult->fetch_assoc()) {
                $firstName = $staffRow['first_name'];
                $fatherName = $staffRow['father_name'];
                $lastName = $staffRow['last_name'];
                $selected = ($_SESSION["selected_city"] == $firstName . ' ' . $fatherName . ' ' . $lastName) ? "selected" : "";
                echo "<option value='" . $firstName . ' ' . $fatherName . ' ' . $lastName . "' " . $selected . ">" . $firstName . ' ' . $fatherName . ' ' . $lastName . "</option>";
                }
            }
            ?>
        </select><br>
		<input type="submit" name="reactivate" value="reactivate" class="submit-button">
		</div>
		</form>
  </div>
  </div>
  <div id="view_user" style="display: none;">
  <div id="view_user1">
    <div class="form_container">
    <h4>View User Lists</h4>
	<h3>Please select the status of the staff to view the list.<br> This page is used to view the list of the staffs.</h3>
  Select staff status:<select id="user_status">
    <option disabled selected>User status</option>
    <option value="activated">Activate User</option>
    <option value="deactivate">Deactivate User</option>
    <option value="processed">Processed User</option>
  </select><br>
 
  </div>
  </div>

  <table id="user_table">
    <thead>
	<tr>
	 <th colspan='14'>
	 <?php
          $selectedStatus = ''; // Default to an empty string
          if (isset($_GET['status'])) {
            $selectedStatus = $_GET['status'];
          }
          echo "List of $selectedStatus users";
          ?>
	</th>
	</tr>
      <tr>
	    <th>no</th>
        <th>First Name</th>
        <th>Father Name</th>
        <th>Last Name</th>
		<th>Gender</th>
		<th>phone_no</th>
		<th>Email</th>
		<th>date of birth</th>
		<th>Age</th>
		<th>Address</th>
		<th>City</th>
		<th>Role</th>
		<th>Approved date</th>
		<th>Deactivate date</th>
      </tr>
    </thead>
    <tbody>
      <?php   
	     //to view user
	// Fetch staff names based on selected option
      $selectedStatus = ''; // Default to 'activated'
      if (isset($_GET['status'])) {
        $selectedStatus = $_GET['status'];
      }

      $staffQuery = "SELECT * FROM staff WHERE activate = '$selectedStatus'";
      $staffResult = $con->query($staffQuery);
	  

      if ($staffResult->num_rows > 0) {
		$count = 1;
        while ($staffRow = $staffResult->fetch_assoc()) {
          $firstName = $staffRow['first_name'];
          $fatherName = $staffRow['father_name'];
          $lastName = $staffRow['last_name'];
		  $gender = $staffRow['gender'];
		  $phone_no = $staffRow['phone_number'];
		  $email = $staffRow['email'];
		  $date_of_birth= $staffRow['date_of_birth'];
		  $age = $staffRow['age'];
		  $address = $staffRow['address'];
		  $city = $staffRow['city'];
		  $role = $staffRow['role'];
		  $approved_date = $staffRow['approved_date'];
		  $deactivated_date = $staffRow['deactivated_date'];
		 
          echo "<tr>";
		  echo "<td>" . $count++ . "</td>";
          echo "<td>$firstName</td>";
          echo "<td>$fatherName</td>";
          echo "<td>$lastName</td>";
		  echo "<td>$gender</td>";
		  echo "<td>$phone_no</td>";
		  echo "<td>$email</td>";
		  echo "<td>$date_of_birth</td>";
		  echo "<td>$age</td>";
		  echo "<td>$address</td>";
		  echo "<td>$city</td>"; 
		  echo "<td>$role</td>"; 
		  echo "<td>$approved_date</td>";
		  echo "<td>$deactivated_date</td>";
          echo "</tr>";
        }
      } else {
        echo "<tr><td colspan='14' >No users found</td></tr>";
      }
      ?>
    </tbody>
  </table>
</div>

<script>
  // Get the select element
  const selectElement = document.getElementById('user_status');

  // Add event listener to handle option selection change
  selectElement.addEventListener('change', function() {
    const selectedOption = this.value;

    // Build the URL with the selected option as a query parameter
    const url = window.location.href.split('?')[0] + '?status=' + selectedOption;

    // Redirect to the URL with the selected option
    window.location.href = url;
  });
</script>
  
  <div id="cont_list">
     <h2> SOUTH ETHIOPIA COC Agency </h2>
	 <p> This is admin home page. please check the notifications. Check and approve the candidates and approve the exams, add the new centers  </p>
  </div>
</div>
<div id="last"></div>

<script>
function toggleForm(selectElement) {
    var formInsertUser = document.getElementById("insert-user-form");
    var formDeactivateUser = document.getElementById("deactivate_user");
	var formReactivateUser = document.getElementById("reactivate_user");
	var formViewUser = document.getElementById("view_user");
    var contList = document.getElementById("cont_list");

    if (selectElement.value === "insert_user.php") {
        formInsertUser.style.display = "block";
        formDeactivateUser.style.display = "none";
		formReactivateUser.style.display = "none";
		formViewUser.style.display = "none";
        contList.style.display = "none";
    } else if (selectElement.value === "deactivate_user.php") {
        formInsertUser.style.display = "none";
        formDeactivateUser.style.display = "block";
		formReactivateUser.style.display = "none";
		formViewUser.style.display = "none";
        contList.style.display = "none";
    }else if (selectElement.value === "reactivate_user.php") {
        formInsertUser.style.display = "none";
		formDeactivateUser.style.display = "none";
        formReactivateUser.style.display = "block";
		formViewUser.style.display = "none";
        contList.style.display = "none";
    }else if (selectElement.value === "view_user.php") {
        formInsertUser.style.display = "none";
        formDeactivateUser.style.display = "none";
		formReactivateUser.style.display = "none";
		formViewUser.style.display = "block";
        contList.style.display = "none";
    } else {
        formInsertUser.style.display = "none";
        formDeactivateUser.style.display = "none";
		formReactivateUser.style.display = "none";
		formViewUser.style.display = "none";
        contList.style.display = "block";
    }
}

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
<?php include("footer.php");?>