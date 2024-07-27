<?php
// if (!isset($_SESSION)) {
//   session_start();
// }
?>
<head>
    <link rel="stylesheet" type="text/css" href="{{ asset('styles.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('registeration.css') }}" />
    <style>
        .hidden {
            display: none;
        }
    </style>
    <script>
        var currentQuestion = 1;
        var totalQuestions = 5; // Set the total number of questions here

        function showNextQuestion() {
            var currentForm = document.getElementById("register_form_" + currentQuestion);
            currentForm.classList.add("hidden");

            currentQuestion++;

            if (currentQuestion <= totalQuestions) {
                var nextForm = document.getElementById("register_form_" + currentQuestion);
                nextForm.classList.remove("hidden");
            }
        }

        function showPreviousQuestion() {
            var currentForm = document.getElementById("register_form_" + currentQuestion);
            currentForm.classList.add("hidden");

            currentQuestion--;

            if (currentQuestion >= 1) {
                var previousForm = document.getElementById("register_form_" + currentQuestion);
                previousForm.classList.remove("hidden");
            }
        }
    </script>
</head>
<body>
    <div id="header">
        <img src="images/sam1.png">
    </div>
     <div id="navigation">
	  <ul>
	     <li><a href="eps.php">Home</a></li>
		 <li>
        <a href="#">Exam</a>
        <ul class="sub-menus">
		  <li><a href="create_exam.php">Create theory exam</a></li><br>
          <li><a href="create_practical_exam.php">Create Practical exam</a></li><br>
		  <li><a href="edit_question.php">Edit Exam</a></li><br>
        </ul>
      </li>
	     <li><a href="eps_notification.php">notification</a><?php include("epsbell.php");?></li>
	     <li><a href="logout.php">Log out</a></li>
	  </ul>
    </div>
    <div id="site_content">
        <?php
		$con = mysqli_connect("localhost", "root", "", "exam");
		$query = "SELECT DISTINCT list_department FROM centers";
        $result = $con->query($query);

        $options = array();
        if ($result->num_rows > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
           $options[] = $row['list_department'];
        }
        }
		
		
        $totalQuestions = 5; // Default value

        if (isset($_POST['total_questions'])) {
            $totalQuestions = $_POST['total_questions'];
        }

        if (isset($_POST['submit'])) {
			    $level = $_POST['level'];
				$department =$_POST['list_department'];
				$hr = $_POST['hr'];
                $min = $_POST['min'];
			    $quetionNo = $_POST['ques_no'];
                $question = $_POST['ques'];
				
			$timerQuery = "INSERT INTO timer (level, list_department, hr, min) VALUES ('$level', '$department', '$hr', '$min')";
            $timerResult = mysqli_query($con, $timerQuery);

            if ($timerResult && mysqli_affected_rows($con) > 0) {
                 echo "Timer data inserted successfully.<br>";
            } else {
                 echo "Error: Failed to insert timer data.<br>";
            }
                
			$count = count($quetionNo); // Get the count of elements
			$successCount = 0; // Initialize the variable before the loop

              // Generate the same ID for each question
               $quesId = substr($department, 0, 4) . '-' . $level . '-prac';
			   $insertResult = false; // Default value
	
    for ($i = 0; $i < $count; $i++) {
        if (isset($quetionNo[$i], $question[$i])) {
            $questionNo = $quetionNo[$i];
            $questionText = $question[$i];
		
		     // Append the question number to the ID
            $quesIdWithNumber = $quesId . '-' . $i;

           
			$Query = "INSERT INTO question (ques_id, level, department, ques_no, ques, txtA, txtB, txtC, txtD, answer, exam_type) VALUES ('$quesId', '$level', '$department', '$questionNo', '$questionText', '', '', '', '', '', 'practical')";
			
            $insertResult = mysqli_query($con, $Query);
        } 
		if ($insertResult && mysqli_affected_rows($con) > 0) {
            $successCount++;
        }
    }

        if ($successCount > 0) {
             echo "Questions are inserted successful. $successCount row(s) inserted.";
        } else {
             echo "Error: Failed to insert data.";
		}
	}
?>

        <?php if (!isset($_POST['total_questions'])): ?>
            <form method="POST" action="">
                <label for="total_questions">Total Questions:</label>
                <input type="number" name="total_questions" id="total_questions" value="<?php echo $totalQuestions; ?>">
                <input type="submit" value="Start">
            </form>
        <?php else: ?>
            <form method="POST" action="">
                <?php for ($i = 1; $i <= $totalQuestions; $i++): ?>
                    <div id="register_form_<?php echo $i; ?>" <?php if ($i !== 1) echo 'class="hidden"'; ?>>
                        <fieldset>
                            <legend>Question Page</legend>
                            <div id="current_date">
                                <script>
                                    date = new Date();
                                    year = date.getFullYear();
                                    month = date.getMonth() + 1;
                                    day = date.getDate();
                                    document.getElementById("current_date").innerHTML = "date:" + day + "/" + month + "/" + year;
                                </script>
                            </div>
                            <?php if ($i === 1): ?>
                                <div class="register_item">
                                    Level: <input type="number" name="level" min="1" max="5" required><br><br>
                                    Department:
                                    <select name="list_department" required>
                                        <option value="">Select department</option>
                                        <?php foreach ($options as $option): ?>
                                            <option value="<?php echo $option; ?>"><?php echo $option; ?></option>
                                        <?php endforeach; ?>
                                    </select><br>
									<label>Given Time:</label>
                                       hr:<input type="number" name="hr" min="1" max="24" required>
                                       min:<input type="number" name="min" min="0" max="60" required>
                                </div>
                            <?php endif; ?>
                            <div class="register_item">
                                <div class="ques">
                                    Question number: <input type="text" name="ques_no[]" value="<?php echo $i; ?>" readonly><br>
                                    Question: <textarea name="ques[]" cols="50" rows="5" required></textarea><br>
                                </div>
                                <?php if ($i !== 1): ?>
                                    <a href="#" onclick="showPreviousQuestion()">Previous</a>
                                <?php endif; ?>
                                
								<?php if ($i < $totalQuestions): ?>
                                    <a href="#" onclick="showNextQuestion()">Next</a>
                                <?php endif; ?>
								
								<?php if ($i == $totalQuestions): ?>
                                    <input type="submit" name="submit" value="Submit">
                                <?php endif; ?>
                                
                            </div>
                        </fieldset>
                    </div>
                <?php endfor; ?>
            </form>
        <?php endif; ?>
    </div>
</body>


























