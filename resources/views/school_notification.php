<?php
@include("headOfSchool.php");
$con = mysqli_connect("localhost", "root", "", "exam");

$institution = $_GET['institution'] ?? '';
$city = $_GET['city'] ?? '';

// Fetch and count the occurrences of each department for the specified institution, city, and approval status
$sql = "SELECT department, COUNT(*) AS count FROM school_candidate WHERE institution = '$institution' AND city = '$city' AND approve = 0 GROUP BY department";
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
    // No new candidates registered for the specified institution, city, and approval status
    $departmentCounts = array();
}

// Close the database connection
$con->close();
?>
<head>
  <link rel="stylesheet" type="text/css" href="styles.css" />
</head>

<div id="site_content">
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
    <?php } else { ?>
        <div class="notification-item">
            <div class="notification-icon">
                <img src="notification-icon.png" alt="Notification Icon">
            </div>
            <div class="notification-content">
                <h3>New Candidates</h3>
                <?php foreach ($departmentCounts as $department => $count) { ?>
                    <p><a href="school_approve_candidate.php?institution=<?php echo urlencode($institution); ?>&city=<?php echo urlencode($city); ?>" class="no-decoration"><?php echo "$count new $department candidate are registered "; ?></a></p>
                <?php } ?>
            </div>
        </div>
    <?php } ?>
</div>
<?php include("footer.php"); ?>