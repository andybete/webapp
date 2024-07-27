<?php
$con = mysqli_connect("localhost", "root", "", "exam");
// SQL query to fetch and count the occurrences of each department
  $sql = "SELECT department, COUNT(*) AS count FROM notification GROUP BY department, level";
  $result = $con->query($sql);

if ($result->num_rows > 0) {
    // Initialize an associative array to store the department counts
    $commentCounts = array();

    // Loop through each row of the result and store the department counts
    while ($row = $result->fetch_assoc()) {
        $department = $row['department'];
        $count = $row['count'];
        $commentCounts[$department] = $count;
    }
    
    // Close the database connection
    $con->close();
} else {
    // No new candidates registered
    $commentCounts = array();
}
?>
<head>
  <link rel="stylesheet" type="text/css" href="styles.css" />
  
</head>
<div id="header">
     <img  src="images/sam1.png">
	</div>
    <div id="navigation">
	  <ul>
	  <li><a href="eps.php">Home</a></li>
	  <li><a href="create_exam.php">Create Exam</a></li>
	  <li><a href="eps_notification.php">notification</a><?php include("epsbell.php");?></li>
	  <li><a href="edit_question.php">Edit Exam</a></li>
	  <li><a href="logout.php">Log out</a></li>
	  </ul>
   </div>
<div id="site_content">
    <h2>Notifications</h2>
    <div class="read"><a href="">Mark all as read</a></div>
	
    <?php if (empty($commentCounts)) { ?>
    <div class="notification-item">
        <div class="notification-icon">
            <img src="notification-icon.png" alt="Notification Icon">
        </div>
        <div class="notification-content">
            <?php 
            $title = "No New Notification about exam";
            $description = "No any comment about exam.";
            ?>
            <h3><?php echo $title; ?></h3>
            <p><?php echo $description; ?></p>
        </div>
    </div>
    <?php } else { ?>
    <?php foreach ($commentCounts as $department => $count) { ?>
        <ul class="notification-list">
            <li>
                <div class="notification-item">
                    <div class="notification-icon">
                        <img src="notification-icon.png" alt="Notification Icon">
                    </div>
                    <div class="notification-content">
                        <?php 
                        $title = "New comment";
                        $description = "$count New $department exam question has a comment";
                        ?>
						<a href="edit_question.php?level=<?php echo $notificationRow['level']; ?>&department=<?php echo $notificationRow['department']; ?>&ques_no=<?php echo $notificationRow['ques_no']; ?>">View Details</a>
                        <h3><?php echo $title; ?></h3>
                        <p><a href="approveCandidates.php" class="no-decoration"><?php echo $description; ?></a></p>
                    </div>
                </div>
            </li>
        </ul>
    <?php } ?>
    <?php } ?>
</div>
<?php include("footer.php"); ?>