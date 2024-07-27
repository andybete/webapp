<?php
$con = mysqli_connect("localhost", "root", "", "exam");

//$questionSql = "SELECT * FROM question WHERE comment IS NOT NULL";
$questionSql = "SELECT * FROM question WHERE comment <> ''";
$questionResult = mysqli_query($con, $questionSql);

// Fetch all the questions with comments from the database
$questions = [];
if (mysqli_num_rows($questionResult) > 0) {
    while ($questionRow = mysqli_fetch_assoc($questionResult)) {
        $questions[] = $questionRow;
    }
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Update the edited questions in the database
    foreach ($_POST['ques_id'] as $index => $ques_id) {
        $ques_no = $_POST['ques_no'][$index];
        $edited_ques = $_POST['edited_ques'][$index];
        $edited_txtA = $_POST['edited_txtA'][$index];
        $edited_txtB = $_POST['edited_txtB'][$index];
        $edited_txtC = $_POST['edited_txtC'][$index];
        $edited_txtD = $_POST['edited_txtD'][$index];
        $edited_answer = $_POST['edited_answer'][$index];

        // Update the question table with the edited values based on ques_id, ques_no, and comment not empty
        $updateSql = "UPDATE question SET ques = ?, txtA = ?, txtB = ?, txtC = ?, txtD = ?, answer = ?, status = 'edited'  WHERE ques_id = ? AND ques_no = ? AND comment <> ''";
        $stmt = mysqli_prepare($con, $updateSql);
        mysqli_stmt_bind_param($stmt, "ssssssii", $edited_ques, $edited_txtA, $edited_txtB, $edited_txtC, $edited_txtD, $edited_answer, $ques_id, $ques_no);
        mysqli_stmt_execute($stmt);
        // Check for errors
        if (mysqli_stmt_errno($stmt)) {
            $errorMessage = mysqli_stmt_error($stmt);
            // Handle the error (e.g., display an error message or log it)
            echo "Error: " . $errorMessage;
        }
        mysqli_stmt_close($stmt);
    }
}

mysqli_close($con);
?>

<head>
    <link rel="stylesheet" type="text/css" href="styles.css" />
    <link rel="stylesheet" type="text/css" href="registration.css" />
</head>

<body>
    <div id="header">
        <img src="images/sam1.png">
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
        <form method="POST">
            <?php //if ($currentQuestion) {
                foreach ($questions as $index => $question) { ?>
                    <table>
                        <tbody>
                            <tr>
                                level - <?php echo $question['level']; ?>
                                department - <?php echo $question['department']; ?>
                                ques_no - <?php echo $question['ques_no']; ?><br><br>
                                question - <input type="text" name="edited_ques[]" value="<?php echo $question['ques']; ?>"><br><br>
                                choice A - <input type="text" name="edited_txtA[]" value="<?php echo $question['txtA']; ?>"><br><br>
                                choice B - <input type="text" name="edited_txtB[]" value="<?php echo $question['txtB']; ?>"><br><br>
                                choice C - <input type="text" name="edited_txtC[]" value="<?php echo $question['txtC']; ?>"><br><br>
                                choice D - <input type="text" name="edited_txtD[]" value="<?php echo $question['txtD']; ?>"><br><br>
                                answer - <input type="text" name="edited_answer[]" value="<?php echo $question['answer']; ?>"><br><br>
                                comment - <?php echo $question['comment']; ?><br><br>
                            </tr>
                        </tbody>
                    </table>
					<input type="hidden" name="ques_id[]" value="<?php echo $question['ques_id']; ?>">
                    <input type="hidden" name="ques_no[]" value="<?php echo $question['ques_no']; ?>">
                
                <?php }?>
                  
                    <input type="submit" value="Save"><br>
             
            <?php //} ?>
        </form>
    </div>
</body>