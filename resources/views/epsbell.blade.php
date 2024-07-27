<head>
<link rel="stylesheet" href="{{ asset('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css') }}">
<style>
    .fa-bell {
        position: relative;
		color: black;
		font-size:20px;
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
$newNotifySql = "SELECT COUNT(*) AS count FROM notification";
$newNotifyResult = mysqli_query($con, $newNotifySql);
$newNotifyRow = mysqli_fetch_assoc($newNotifyResult);
$newNotifyCount = $newNotifyRow['count'];

// Display the notification icon if there are new candidates
   if ($newNotifyCount > 0) {
    echo '<i class="fa fa-bell">
              <span class="notification-badge">' . $newNotifyCount . '</span> </i>';
}
