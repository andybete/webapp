
<?php
@include("header");
session_start();
@include("headOfSchool");

// $con = mysqli_connect("localhost", "root", "", "exam");
// $msg = $erruser = $errcity= $errpass = $cpasserr = $errinst = $errpno = $pnoError = $erremail = $erradd = "";
// if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//     $username = $_POST['username'];
//     $password = $_POST['pass'];
//     $cpassword = $_POST['cpass'];
//     $city = $_POST['list_city'];
//     $institution = $_POST['list_institution'];
// 	$phone_number = $_POST['pno'];
// 	$email = $_POST['email'];
// 	$address = $_POST['add'];

//     if (empty($username)) {
//         $erruser = "Username is required";
//     } else if (strlen($username) < 8) {
//         $erruser = "The username must be at least 8 characters long";
//     }

//     if (empty($password)) {
//         $errpass = "Password is required";
//     } else if (strlen($password) < 8) {
//         $errpass = "The password must be at least 8 characters long";
//     } else if (!preg_match("/^(?=.*[a-zA-Z])(?=.*\d).+$/", $password)) {
//         $errpass = "The password must contain a combination of letters and numbers";
//     }

//     if ($password !== $cpassword) {
//         $cpasserr = "Passwords do not match";
//     }

//     if (empty($city)) {
//         $errcity = "city is required";
//     }
	
//     if (empty($institution)) {
//         $errinst = "Institution is required";
//     }
     
// 	if(empty($phone_number)){
// 		$errpno="Center phone_number is required";
// 	}else if(!preg_match ("/^[0-9]*$/", $phone_number)){
// 		$pnoError="only numeric value is allowed";
// 	}else if (!preg_match("/^09\d{8}$/", $phone_number)) {
//         $pnoError = "Please enter a 10-digit phone number starting with '09'";
//     }
// 	else{
// 		$vphone_number=$phone_number;
// 	}
	
//     if(empty($email)){
// 		$erremail="center Email is required";
// 	}else if(!preg_match(   "/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/", $email)) {
//         $emailError = "Invalid email format";
//     }

//     if(empty($address)){
// 		$erradd="Address is required";
// 	}
	
// 	if (!empty($username) && !empty($password) && !empty($cpassword) && !empty($city) && !empty($institution) && !empty($phone_number) && !empty($email) && !empty($address)) {
//     // Check if the input exists in the table
//     $query = "SELECT * FROM new_center WHERE city = '$city' AND institution = '$institution'  ORDER BY city ASC, institution ASC";
//     $result = mysqli_query($con, $query);
//     $rowCount = mysqli_num_rows($result);

//     if ($rowCount > 0) {
//         $msg = "This center is already registered.";
//     } else {

//         // Set the desired timezone
//         $timezone = new DateTimeZone('UTC'); // Replace 'UTC' with the desired timezone

//         // Create a new DateTime object with the current date and the desired timezone
//         $currentDateTime = new DateTime('now', $timezone);

//         // Get the current year, month, and day
//         $currentYear = $currentDateTime->format('Y');
//         $currentMonth = $currentDateTime->format('m');
//         $currentDay = $currentDateTime->format('d');

//         // Insert the data into the table
//         $insertQuery = "INSERT INTO new_center (username, password, city, institution, phone_number, email, address, reg_date) VALUES ('$username', '$password', '$city', '$institution', '$phone_number', '$email', '$address', '$currentYear-$currentMonth-$currentDay')";
//         $insertResult = mysqli_query($con, $insertQuery);

//         if ($insertResult) {
// 			echo "<script>alert('Your request succesfully sent. it has in processed. '); window.location.href = 'home.php';</script>";
//                 exit;
//             $msg = "Your request has been sent.";
//         } else {
//             $msg = "Failed to insert the data.";
//         }
//     }
// 	}
// }

// // Store all form data in the session variables
// if (isset($_POST['list_city'])) {
//     $_SESSION["selected_city"] = $_POST['list_city'];
//     $_SESSION["username"] = $_POST['username'];
//     $_SESSION["password"] = $_POST['pass'];
//     $_SESSION["cpassword"] = $_POST['cpass'];
//     $_SESSION["institution"] = $_POST['list_institution'];
// 	$_SESSION["email"] = $_POST['email'];
// 	$_SESSION["pno"] = $_POST['pno'];
// 	$_SESSION["add"] = $_POST['add'];
// }

// ?>

<head>
    <link rel="stylesheet" type="text/css" href="{{ asset('registeration.css') }}" />
</head>

<body>
    <form method="POST">
        <div id="register">
            <h3>Registration for new centers</h3>
            <div class="register_item">
                Username: <input type="text" name="username" value="<?php echo isset($_SESSION['username']) ? $_SESSION['username'] : ''; ?>">
                <?php echo "<font color='red'>$erruser</font>"; ?><br>
                Password: <input type="password" name="pass" value="<?php echo isset($_SESSION['password']) ? $_SESSION['password'] : ''; ?>">
                <?php echo "<font color='red'>$errpass</font>"; ?><br>
                Confirm Password: <input type="password" name="cpass" value="<?php echo isset($_SESSION['cpassword']) ? $_SESSION['cpassword'] : ''; ?>">
                <?php echo "<font color='red'>$cpasserr</font>"; ?><br>
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
          </select> <?php echo "<font color='red'>$errcity</font>"; ?><br>
                Institution: <input type="text" name="list_institution" value="<?php echo isset($_SESSION['institution']) ? $_SESSION['institution'] : ''; ?>">
                <?php echo "<font color='red'>$errinst</font>"; ?><br><br>
                
				<label for="pno">phone number:</label>
                <input type="tel" name="pno" value="<?php echo isset($_SESSION['pno']) ? $_SESSION['pno'] : ''; ?>" placeholder="0912345678">
		          <?php echo "<font color='red'>$errpno </font>"; ?>
                  <?php echo "<font color='red'>$pnoError </font>"; ?><br>
				
				<label for="email">Email:</label>
               <input type="email" name="email" id="email" value="<?php echo isset($_SESSION['email']) ? $_SESSION['email'] : ''; ?>" placeholder="abc@gmail.com">
               <?php echo "<font color='red'>$erremail </font>"; ?><br>
           	
            <label for="add">Address:</label>
            <textarea name="add" cols="30" rows="1" placeholder="zone, kefle ketema, kebele"><?php echo isset($_SESSION['add']) ? $_SESSION['add'] : ''; ?></textarea>
		    <?php echo "<font color='red'>$erradd </font>"; ?><br><br>
			
			<input type="submit" name="submit" value="Send Request" class="submit-button">
                <br>
				<?php echo "<font color='red'>$msg</font>"; ?>
            </div>
        </div>
		
    </form>
    
</body>
<?php include("footer.php"); ?>