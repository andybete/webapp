<?php
session_start(); // Start the session

@include("headOfSchool");

$con = mysqli_connect("localhost", "root", "", "exam");
$msg = $msg1 = $msg2 = $msg3 = $msg4 = $msg5 = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	
    $city = $_SESSION["selected_city"] = $_POST['list_city'];
    $institution = $_SESSION["selected_institution"] = $_POST['list_institution'];
    $department = $_SESSION["selected_department"] = $_POST['list_department'];
    $level = $_SESSION["selected_level"] = $_POST['level'];

    if (empty($city)) {
        $msg1 = "City is required";
    } 
    if (empty($institution)) {
        $msg2 = "Institution is required";
    } else if (!preg_match("/^[a-zA-Z][a-zA-Z ]*$/", $institution)) {
        $msg3 = "Only alphabets are allowed for the institution";
    } 
    if (empty($department)) {
        $msg4 = "Department is required";
    } 
    if (empty($level)) {
        $msg5 = "Level is required";
    } 
	
	
    if (!empty($city) && !empty($institution) && !empty($department) && !empty($level)) {
        // Check if the input exists in the table
        $query = "SELECT * FROM centers WHERE list_city = '$city' AND list_institution = '$institution' AND list_department = '$department' AND level = '$level' ORDER BY list_city ASC, list_institution ASC";
        $result = mysqli_query($con, $query);
        $rowCount = mysqli_num_rows($result);

        if ($rowCount > 0) {
            $msg = "The data already exists in the table.";
        } else {
			// Fetch the values from the new_center table
            $selectQuery = "SELECT phone_number, email, address FROM new_center WHERE city = '$city' AND institution = '$institution'";
            $selectResult = mysqli_query($con, $selectQuery);
			
			
			// Check if the query was successful and retrieve the values
          if ($selectResult) {
          $row = mysqli_fetch_assoc($selectResult);

            // Retrieve the values for phone_number, email, and address
          $phone_number = $row['phone_number'];
          $email = $row['email'];
          $address = $row['address'];
			
			
            // Insert the data into the table
            $insertQuery = "INSERT INTO centers (list_city, list_institution, list_department, level, phone_number, email, address) VALUES ('$city', '$institution', '$department', '$level', '$phone_number', '$email', '$address')";
            $insertResult = mysqli_query($con, $insertQuery);

            if ($insertResult) {
                // Store the form data in the session
               echo "<script>alert('Your record has been successfully inserted'); window.location.href = 'school.php';</script>";
                exit;
            } else {
                $msg = "Failed to insert the data.";
            }
        }}
    }
}
?>
<head>
   <link rel="stylesheet" type="text/css" href="registeration.css" />
</head>
<body>
<form method="POST">
    <div id="register">
	<h3>Registration for new centers</h3>
   <fieldset><legend>Your center data</legend>
   <div class="register_item">
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
          </select><?php echo "<font color='red'>$msg1 </font>"; ?><br><br>
	       Institution:<input type="text" name="list_institution" value="<?php echo isset($_SESSION['selected_institution']) ? $_SESSION['selected_institution'] : ''; ?>">
		   <?php echo "<font color='red'>$msg2 </font>"; ?>
		   <?php echo "<font color='red'>$msg3 </font>"; ?><br><br>
	       Department:<select name="list_department">
               <option value="">Select department</option>
                <?php
                   // Fetch department
                    $depQuery = "SELECT DISTINCT list_department FROM department_list";
                    $depResult = $con->query($depQuery);

                    if ($depResult->num_rows > 0) {
                       while ($depRow = $depResult->fetch_assoc()) {
                       $depName = $depRow['list_department'];
					   $selected = ($_SESSION["selected_department"] == $depName) ? "selected" : "";
                       echo "<option value='" . $depName . "' " . $selected . ">" . $depName . "</option>";
                       }
                    }
                ?>
          </select><?php echo "<font color='red'>$msg4 </font>"; ?><br><br>
	       Level: <input type="number" name="level" min="1" max="5">
		   <?php echo "<font color='red'>$msg5 </font>"; ?><br><br>
		   
	       <input type="submit" name="submit" value="Submit" class="submit-button"><br>
		   <?php echo "<font color='red'>$msg </font>"; ?>
   </div>
   </fieldset>
   </div>
   </form>
   <?php include("footer.php");?>