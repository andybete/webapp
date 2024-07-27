<?php
session_start();

// Perform logout actions
session_destroy();
$_SESSION = array(); // Clear all session variables

// Redirect the user to the login page
header('Location: home.php');
exit();
?>