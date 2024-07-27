<?php include("headOfAdmin.php"); ?>
<?php
$con = mysqli_connect("localhost", "root", "", "exam");

// Fetch and count the occurrences of each department
$sql = "SELECT department, COUNT(*) AS count FROM candidate GROUP BY department";
$result = $con->query($sql);

if ($result->num_rows > 0) {
    // Initialize an associative array to store the department counts
    $departmentCounts = array();

    // Loop through each row of the result and store the department counts
    while ($row = $result->fetch_assoc()) {
        $department = $row['department'];
        $count = $row['count'];
        $departmentCounts[$department] = $count;
    }
} else {
    // No new candidates registered
    $departmentCounts = array();
}

// Fetch centers with approve=0
$sqlCenters = "SELECT * FROM centers WHERE approve = 0";
$resultCenters = $con->query($sqlCenters);

if ($resultCenters->num_rows > 0) {
    // Initialize an empty array to store the center data
    $centers = array();

    // Loop through each row of the result and store the center data
    while ($row = $resultCenters->fetch_assoc()) {
        $centerName = $row['list_institution'];
        $centerLocation = $row['list_city'];
        // Add the center data to the array
        $centers[] = array('name' => $centerName, 'location' => $centerLocation);
    }
}

// Close the database connection
$con->close();
?>

<head>
  <link rel="stylesheet" type="text/css" href="notification.css" />
</head>
<div id="container">
    <h2>Notifications</h2>
    <div class="read"><a href="">Mark all as read</a></div>
	
    <?php if (empty($departmentCounts) && empty($centers)) { ?>
        <div class="notification-item">
            <div class="notification-icon">
                <img src="notification-icon.png" alt="Notification Icon">
            </div>
            <div class="notification-content">
                <h3>No New Notification</h3>
                <p>No new candidates have been registered or centers require approval.</p>
            </div>
        </div>
    <?php } ?>
    
    <?php foreach ($departmentCounts as $department => $count) { ?>
        <ul class="notification-list">
            <li>
                <div class="notification-item">
                    <div class="notification-icon">
                        <img src="notification-icon.png" alt="Notification Icon">
                    </div>
                    <div class="notification-content">
                        <?php 
                        $title = "New user is registered";
                        $description = "$count New $department candidates are registered";
                        ?>
                        <h3><?php echo $title; ?></h3>
                        <p><a href="approveCandidates.php" class="no-decoration"><?php echo $description; ?></a></p>
                    </div>
                </div>
            </li>
        </ul>
    <?php } ?>
    
    <?php if (!empty($centers)) { ?>
        <ul class="notification-list">
            <li>
                <div class="notification-item">
                    <div class="notification-icon">
                        <img src="notification-icon.png" alt="Notification Icon">
                    </div>
                    <div class="notification-content">
                        <?php 
                        $title = "Center Approval Required";
                        $count = count($centers);
                        $description = "$count centers require approval.";
                        ?>
                        <h3><?php echo $title; ?></h3>
                        <p><a href="approve-center.php" class="no-decoration"><?php echo $description; ?></a></p>
                    </div>
                </div>
            </li>
        </ul>
    <?php } ?>

</div>
<?php include("footer.php"); ?>