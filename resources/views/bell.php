<head>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<style>
    .fa-bell {
        position: relative;
        color: black;
        font-size: 20px;
    }
    
    .notification-badge {
        position: absolute;
        top: -5px;
        right: -5px;
        background-color: red;
        color: white;
        font-size: 12px;
        padding: 2px 5px;
        border-radius: 50%;
    }
</style>
</head>
<?php
// Your existing PHP code...

// Check if there are new candidates
$con = mysqli_connect("localhost", "root", "", "exam");
$newCandidatesSql = "SELECT COUNT(*) AS count FROM candidate";
$newCandidatesResult = mysqli_query($con, $newCandidatesSql);
$newCandidatesRow = mysqli_fetch_assoc($newCandidatesResult);
$newCandidatesCount = $newCandidatesRow['count'];

// Check if there are new centers with approve = 0
$newCentersSql = "SELECT COUNT(*) AS count FROM centers WHERE approve = 0";
$newCentersResult = mysqli_query($con, $newCentersSql);
$newCentersRow = mysqli_fetch_assoc($newCentersResult);
$newCentersCount = $newCentersRow['count'];

// Determine the notification count
if ($newCandidatesCount > 0 && $newCentersCount > 0) {
    $notificationCount = 2; // Both new candidates and new centers
} elseif ($newCandidatesCount > 0 || $newCentersCount > 0) {
    $notificationCount = 1; // Either new candidates or new centers
} else {
    $notificationCount = 0; // No new candidates or new centers
}

// Display the notification icon
if ($notificationCount > 0) {
    echo '<i class="fa fa-bell">
              <span class="notification-badge">' . $notificationCount . '</span> </i>';
}

// Rest of your HTML code...
?>