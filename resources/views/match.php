<?php
$conn = mysqli_connect("localhost", "root", "", "exam");
$username = $_GET['username'];
$answers = json_decode($_GET['answers'], true);

$query = "SELECT first_name, father_name, last_name, institution, department, level FROM approvecandidate WHERE username='$username'";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);

if ($row) {
    $first_name = $row['first_name'];
    $father_name = $row['father_name'];
    $last_name = $row['last_name'];
    $institution = $row['institution'];
    $department = $row['department'];
    $level = $row['level'];
}

// Fetch questions and correct answers from the table based on department and level
$query = "SELECT ques_no, ques, txtA, txtB, txtC, txtD, answer FROM question WHERE department='$department' AND level='$level'";
$result = mysqli_query($conn, $query);
$totalQuestions = mysqli_num_rows($result); // Get the total number of questions

// Create an array to store the correct answers
$correctAnswers = array();

while ($row = mysqli_fetch_assoc($result)) {
    $questionNumber = $row['ques_no'];
    $correctAnswer = $row['answer'];
    $correctAnswers[$questionNumber] = $correctAnswer;
}


// Initialize count of correct answers
$correctCount = 0;

// Assuming you have a total number of questions stored in a variable called $totalQuestions
for ($questionNumber = 1; $questionNumber <= $totalQuestions; $questionNumber++) {
    if (isset($answers[$questionNumber])) {
        $selectedAnswer = $answers[$questionNumber];
        $correctAnswer = $correctAnswers[$questionNumber];

        $status = ($selectedAnswer === $correctAnswer) ? "Correct" : "Incorrect";

        // Increment correctCount if the answer is correct
        if ($status === "Correct") {
            $correctCount++;
        }
    }
}

// Determine pass or fail based on the number of correct answers
$passThreshold = ceil($totalQuestions / 2); // Round up to the nearest whole number
$passStatus = ($correctCount >= $passThreshold) ? "Pass" : "Fail";


// Update the tables with the pass/fail status
$query = "UPDATE approvecandidate SET theory_exam='$passStatus' WHERE username='$username'";
mysqli_query($conn, $query);

$query = "UPDATE school_candidate SET theory_exam='$passStatus' WHERE username='$username'";
mysqli_query($conn, $query);

    if (mysqli_affected_rows($conn) > 0) {
		echo "please wait for a few minute ";
	echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11.1.2'></script>";
    echo "<script>
            Swal.fire({
                icon: 'success',
                title: 'Update Successful',
                text: 'You can view your result after a few minutes.'
            }).then(function() {
				window.location.href = 'candidate.php?username=" . $username . "';
            });
          </script>";
} else {
    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11.1.2'></script>";
    echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Error updating tables.'
            });
          </script>";
}
?>