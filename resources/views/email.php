<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$conn = mysqli_connect("localhost", "root", "", "exam");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch the image data from the database
$username = 'aaa'; // ID or unique identifier of the candidate
$sql = "SELECT grade_report FROM school_candidate WHERE username = '$username'";
$result = $conn->query($sql);
if (!$result) {
    die("SQL error: " . $conn->error);
}

if ($result && $result->num_rows > 0) {
    // Retrieve the image data
    $row = $result->fetch_assoc();
    $imageData = $row['grade_report'];

   
    // Output the image data
	// Display the image as a link
    echo "<a href='$imageData'>View Grade Report</a>";
    echo $imageData;
} else {
    echo "Image not found.";
}

$conn->close();
?>
