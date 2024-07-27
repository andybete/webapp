<head>
<link rel="stylesheet" href="{{ asset('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css') }}">
<style>
    .notification-icon {
        position: relative;
    }
    
    .notification-icon .fa-bell {
        color: white;
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
$newCandidatesSql = "SELECT COUNT(*) AS count FROM school_candidate WHERE approve=0";
$newCandidatesResult = mysqli_query($con, $newCandidatesSql);
$newCandidatesRow = mysqli_fetch_assoc($newCandidatesResult);
$newCandidatesCount = $newCandidatesRow['count'];


// Determine the notification count
if ($newCandidatesCount > 0) {
    $notificationCount = 1; // Both new candidates and new centers
} else {
    $notificationCount = 0; // No new candidates or new centers
}

// Display the notification icon
if ($notificationCount > 0) {
    echo '<i class="fa fa-bell" style="color: orange;">
              <span class="notification-badge">' . $notificationCount . '</span> </i>';
}

// Rest of your HTML code...
?>