<?php

$con = mysqli_connect("localhost", "root", "", "exam");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle form submission
    if (isset($_POST['submit'])) {
        // Calculate average score as a percentage
        $totalScore = 0;
        $numQuestions = 10; // Assuming 10 questions
        $maxScorePerQuestion = 5; // Assuming maximum score of 5 per question
        for ($i = 1; $i <= $numQuestions; $i++) {
            if (isset($_POST['rank' . $i])) {
                $totalScore += intval($_POST['rank' . $i]);
            }
        }
        $averageScore = ($totalScore / ($numQuestions * $maxScorePerQuestion)) * 100;

        $username = $_SESSION['username'];

        // Update the remark in the database based on the average score
        $remark = ($averageScore >= 50) ? 'pass' : 'fail';
        $query = "UPDATE approvecandidate SET remark = ? WHERE username = ?";
        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, "ss", $remark, $username);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        // Declare and set the pass or fail message
        $msg = ($remark === 'pass') ? "Congratulations! You have passed the exam." : "Sorry, you failed the exam.";
        $avemsg = "You have Scored Average Mark: " . $averageScore . "%";
        
    }
}
?>

<form method="POST">
    <table>
        <thead>
            <tr>
                <th>Index</th>
                <th>Excellent</th>
                <th>Very Good</th>
                <th>Good</th>
                <th>Satisfactory</th>
                <th>Fair</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $index = 1; // Initial index value
            for ($i = 1; $i <= 10; $i++) {
                ?>
                <tr>
                    <td><?php echo $index++; ?></td>
                    <td><?php echo '<input type="radio" name="rank' . $i . '" value="5">'; ?></td>
                    <td><?php echo '<input type="radio" name="rank' . $i . '" value="4">'; ?></td>
                    <td><?php echo '<input type="radio" name="rank' . $i . '" value="3">'; ?></td>
                    <td><?php echo '<input type="radio" name="rank' . $i . '" value="2">'; ?></td>
                    <td><?php echo '<input type="radio" name="rank' . $i . '" value="1">'; ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
    <input type="submit" name="submit" value="Submit">
</form>

<?php
// Display average mark and pass/fail message if they exist
if (isset($avemsg) && isset($msg)) {
    echo $avemsg . "<br>";
    echo $msg;
}
?>