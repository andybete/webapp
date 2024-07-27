<?php
@include("headOfAdmin");
$con = mysqli_connect("localhost", "root", "", "exam");

$err=$msg="";
if (isset($_POST['submit'])) {
    // Check if all fields are filled
    if (
        !empty($_POST['list_department']) &&
        !empty($_POST['level']) &&
        !empty($_POST['desired_date']) &&
        !empty($_POST['desired_time'])
    ) {
        // Form fields are not empty

        // Retrieve form data
        $department = $_POST['list_department'];
        $level = $_POST['level'];
		$exam_type = $_POST['exam_type'];
        $desiredDate = $_POST['desired_date'];
        $desiredTime = $_POST['desired_time'];

        // Check if the record already exists in the timer table
$checkQuery = "SELECT * FROM timer WHERE list_department = '$department' AND level = '$level' AND exam_type = '$exam_type'";
$checkResult = mysqli_query($con, $checkQuery);

if ($checkResult) {
    if (mysqli_num_rows($checkResult) > 0) {
        // Record already exists, check if desired_date and desired_time are null
        $record = mysqli_fetch_assoc($checkResult);
        if ($record['desired_date'] === null && $record['desired_time'] === null) {
            // Update the record with desired_date and desired_time
            $updateQuery = "UPDATE timer SET desired_date = '$desiredDate', desired_time = '$desiredTime' WHERE list_department = '$department' AND level = '$level' AND exam_type = '$exam_type'";
            $updateResult = mysqli_query($con, $updateQuery);

            if ($updateResult) {
                $msg = "Record updated successfully.";
            } else {
                echo "Error updating record: " . mysqli_error($con);
            }
        } else {
            $msg = "A record already exists for the selected department and level.";
        }
    } else {
          // Update the record in the timer table
          $updateQuery = "UPDATE timer SET desired_date = '$desiredDate', desired_time = '$desiredTime' WHERE list_department = '$department' AND level = '$level'";
          $updateResult = mysqli_query($con, $updateQuery);

            if ($updateResult) {
               $msg = "Record updated successfully.";
            } else {
                echo "Error updating record: " . mysqli_error($con);
            }
		}
            } else {
                $err = "Please fill in all the fields.";
            }
}
}
?>
<body>
<div id="container">
	<div class="list">
	    <div class="list_for">
    
		<form method="POST">
            <label>Department:</label>
            <select name="list_department" id="list_department">
                <option value="">Select department</option>
                <?php
                // Fetch departments from the centers table
                $depQuery = "SELECT DISTINCT list_department FROM centers";
                $depResult = mysqli_query($con, $depQuery);

                if ($depResult) {
                    while ($depRow = mysqli_fetch_assoc($depResult)) {
                        $depName = $depRow['list_department'];
                        echo "<option value='$depName'>$depName</option>";
                    }
                } else {
                    echo "Error fetching departments: " . mysqli_error($con);
                }
                ?>
            </select><br><br>

            <label>Level:</label>
            <select name="level" id="level">
                <option value="">Select level</option>
                <?php
                // Fetch levels from the centers table
                $levelQuery = "SELECT DISTINCT level FROM centers";
                $levelResult = mysqli_query($con, $levelQuery);

                if ($levelResult) {
                    while ($levelRow = mysqli_fetch_assoc($levelResult)) {
                        $levelName = $levelRow['level'];
                        echo "<option value='$levelName'>$levelName</option>";
                    }
                } else {
                    echo "Error fetching levels: " . mysqli_error($con);
                }
                ?>
            </select><br><br>
			<label for="exam_type">Exam Type:</label>
            <select name="exam_type" required>
                <option value="">Select exam type</option>
                <option value="theory">Theory</option>
                <option value="practical">Practical</option>
            </select><br><br>
			<label>Date:</label>
            <input type="date" name="desired_date" id="desired_date" min="<?php echo date('Y-m-d'); ?>" required><br><br>

            <label>Time:</label>
            <input type="text" name="desired_time" id="desired_time" placeholder="HH:MM:SS" pattern="([01]?[0-9]|2[0-3]):[0-5][0-9]:[0-5][0-9]" required><br><br>
			<input type="submit" name="submit" value="submit" class="submit-button"><br>
			<?php echo "<font color='red'>$err</font>"; ?>
			<?php echo "<font weight='bold'>$msg</font>"; ?>
	    </form>
		</div>
    </div>

<div id="cont_list">
     <h2> SNNPRS COC Agency </h2>
	 <p> This page is created for insert the desired exam date. please select the date and time for the department and level  </p>
  </div>
</div>
