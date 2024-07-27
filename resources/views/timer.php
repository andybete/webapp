<?php include("headOfCandidate.php"); ?>
<!-- to display given time that fetch from timer table-->
<?php
// Start the session
session_start();

// Create a connection
$conn = mysqli_connect("localhost", "root", "", "exam");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch the timer data from the table
$sql = "SELECT hr, min FROM timer";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $hours = $row["hr"];
    $minutes = $row["min"];

    // Format the timer
    $formattedTimer = sprintf("%02d:%02d", $hours, $minutes);

    // Calculate the total seconds for the timer
    $totalSeconds = ($hours * 3600) + ($minutes * 60);
} else {
    echo "No timer data found.\n";
}

// Fetch user information from the database
$username = $_GET['username'];

$sql = "SELECT first_name, father_name, last_name, institution, sector, level FROM approvecandidate WHERE username='$username'";
$res = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($res);

if ($row) {
    $first_name = $row['first_name'];
    $father_name = $row['father_name'];
    $last_name = $row['last_name'];
    $institution = $row['institution'];
    $sector = $row['sector'];
    $level = $row['level'];
} else {
    // Handle the case when user information is not found
    $first_name = "N/A";
    $father_name = "N/A";
    $last_name = "N/A";
}

// Fetch questions from the database
$questionIndex = isset($_GET['index']) ? intval($_GET['index']) : 0;

$query = "SELECT * FROM question WHERE sector = '$sector' AND level = '$level'";
$result = mysqli_query($conn, $query);

$questions = array(); // Array to store the fetched questions

while ($row = mysqli_fetch_assoc($result)) {
    $questions[] = $row; // Add the fetched question to the array
}

?>

<div id="site_content">
    <div id="ques-count">
        <div class="display-info">
            <h4>Your Information<hr></h4>
            <p>username: <?php echo $username; ?></p>
            <p>Full name: <?php echo $first_name . ' ' . $father_name . ' ' . $last_name; ?></p>
            <p>Institution: <?php echo $institution; ?></p>
            <p>Sector: <?php echo $sector . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Level: ' . $level; ?></p>
        </div>
        <?php
        $totalQuestions = 20; // Replace 20 with the actual total number of questions
        $breakPoints = [5, 10, 15, 20]; // Define the break points

        echo '<div class="question-counter">';
        for ($i = 1; $i <= $totalQuestions; $i++) {
            echo '<div class="circle">' . $i . '</div>';
            if (in_array($i, $breakPoints)) {
                echo '<div class="break"></div>';
            }
        }
        echo '</div>';
        ?>
    </div>
    <div id="timer">
        <?php
        echo "Given Time: $formattedTimer<br>";
        echo "Remaining Time: <span id='countdown'></span><br>";
        ?>
    </div>

    <div>
        <?php
// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['answer'])) {
        $_SESSION['selected_answer'][$questionIndex] = $_POST['answer']; // Update the session variable with the selected answer
    }
}

// Rest of your code...

// Display the question form
if ($questionIndex >= 0 && $questionIndex < count($questions)) {
    // Retrieve the selected answer from the session
    $selectedAnswer = isset($_SESSION['selected_answer'][$questionIndex]) ? $_SESSION['selected_answer'][$questionIndex] : '';

    // Display the question and answer options
    ?>
      <form method="post">
        <p>Question: <?php echo $questions[$questionIndex]['ques']; ?></p>
        <div>
            <?php if (isset($questions[$questionIndex]['txtA'])) : ?>
                <input type="radio" name="answer" value="A" <?php echo ($selectedAnswer === 'A') ? 'checked' : ''; ?>>
                <label for="optionA">A:&nbsp <?php echo $questions[$questionIndex]['txtA']; ?></label><br>
            <?php endif; ?>
            <?php if (isset($questions[$questionIndex]['txtB'])) : ?>
                <input type="radio" name="answer" value="B" <?php echo ($selectedAnswer === 'B') ? 'checked' : ''; ?>>
                <label for="optionB">B:&nbsp <?php echo $questions[$questionIndex]['txtB']; ?></label><br>
            <?php endif; ?>
            <?php if (isset($questions[$questionIndex]['txtC'])) : ?>
                <input type="radio" name="answer" value="C" <?php echo ($selectedAnswer === 'C') ? 'checked' : ''; ?>>
                <label for="optionC">C:&nbsp <?php echo $questions[$questionIndex]['txtC']; ?></label><br>
            <?php endif; ?>
            <?php if (isset($questions[$questionIndex]['txtD'])) : ?>
                <input type="radio" name="answer" value="D" <?php echo ($selectedAnswer === 'D') ? 'checked' : ''; ?>>
                <label for="optionD">D:&nbsp <?php echo $questions[$questionIndex]['txtD']; ?></label><br>
            <?php endif; ?>
        </div>
        <br>

        <?php if ($questionIndex === (count($questions) - 1)) : ?>
            <button type="submit">Submit</button>
        <?php endif; ?>
    </form>
    
    <?php if ($questionIndex > 0) : ?>
        <a href="take_exam.php?username=<?php echo $username; ?>&index=<?php echo ($questionIndex - 1); ?>">Previous</a>
    <?php endif; ?>

    <?php if ($questionIndex < (count($questions) - 1)) : ?>
        <a href="take_exam.php?username=<?php echo $username; ?>&index=<?php echo ($questionIndex + 1); ?>">Next</a>
    <?php endif;
}
?>
    </div>
</div>

<script>
    // JavaScript code for the countdown timer
    var seconds = <?php echo $totalSeconds; ?>;
    var countdownElement = document.getElementById('countdown');

    function countdown() {
        var minutes = Math.floor(seconds / 60);
        var remainingSeconds = seconds % 60;

        countdownElement.innerHTML = minutes + ":" + (remainingSeconds < 10 ? "0" : "") + remainingSeconds;

        if (seconds <= 0) {
            // Time is up, submit the form
            document.getElementById('question-form').submit();
        } else {
            seconds--;
            setTimeout(countdown, 1000);
        }
    }

    countdown();
</script>











































<?php include("headOfCandidate.php"); ?>
  <!-- to display given time that fitch from timer table-->
<?php
// Create a connection
$conn = mysqli_connect("localhost", "root", "", "exam");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch the timer data from the table
$sql = "SELECT hr, min FROM timer";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $hours = $row["hr"];
    $minutes = $row["min"];

    // Format the timer
    $formattedTimer = sprintf("%02d:%02d", $hours, $minutes);

    // Calculate the total seconds for the timer
    $totalSeconds = ($hours * 3600) + ($minutes * 60);
} else {
    echo "No timer data found.\n";
}

// Close the database connection
$conn->close();

?>
<?php
// Retrieve the user information from the query string
$username = $_GET['username'];
$con = mysqli_connect("localhost", "root", "", "exam");
$sql = "SELECT first_name, father_name, last_name, institution, sector, level FROM approvecandidate WHERE username='$username'";
$res = mysqli_query($con, $sql);
$row = mysqli_fetch_assoc($res);
mysqli_close($con);

if ($row) {
    $first_name = $row['first_name'];
    $father_name = $row['father_name'];
    $last_name = $row['last_name'];
	$institution = $row['institution'];
    $sector = $row['sector'];
    $level = $row['level'];
} else {
    // Handle the case when user information is not found
    $first_name = "N/A";
    $father_name = "N/A";
    $last_name = "N/A";
}
?>
<?php
// Assuming you have a database connection established
$con = mysqli_connect("localhost", "root", "", "exam");
$sector = $row['sector']; // Retrieve the sector from the user information
$level = $row['level']; // Retrieve the level from the user information

// Retrieve the current question index from the query string
$questionIndex = isset($_GET['index']) ? intval($_GET['index']) : 0;

// Fetch questions from the database
$query = "SELECT * FROM question WHERE sector = '$sector' AND level = '$level'";
$result = mysqli_query($con, $query);

$questions = array(); // Array to store the fetched questions

while ($row = mysqli_fetch_assoc($result)) {
    $questions[] = $row; // Add the fetched question to the array
}

mysqli_close($con);
?>

<div id="site_content">
    <div id="ques-count">
	     <div class="display-info"
	      <h4>Your Information<hr></h4>
		  <p>username: <?php echo $username; ?></p>
          <p>Full name: <?php echo $first_name . ' ' . $father_name . ' ' . $last_name; ?></p>
		  <p>Institution: <?php echo $institution; ?></p>
          <p>Sector: <?php echo $sector . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Level: ' . $level; ?></p>
		 </div>
	    <?php
             $totalQuestions = 20; // Replace 20 with the actual total number of questions
             $breakPoints = [5, 10, 15, 20]; // Define the break points

             echo '<div class="question-counter">';
             for ($i = 1; $i <= $totalQuestions; $i++) {
               echo '<div class="circle">' . $i . '</div>';
             if (in_array($i, $breakPoints)) {
               echo '<div class="break"></div>';
             }
             }
               echo '</div>';
        ?>
	</div>
    <div id="timer">
        <?php
        echo "Given Time: $formattedTimer<br>";
        echo "Remaining Time: <span id='countdown'></span><br>";
        ?>
    </div>
	
	<div>
	<?php
	// Retrieve the $selectedAnswers array from the hidden input field
if (isset($_POST['selectedAnswers'])) {
    $selectedAnswers = unserialize(base64_decode($_POST['selectedAnswers']));
} else {
// Initialize the $selectedAnswers array
$selectedAnswers = [];
}
// Retrieve the selected answer from the form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $selectedAnswer = isset($_POST['answer']) ? $_POST['answer'] : '';

    // Store the selected answer in the $selectedAnswers array using the question index as the key
    $selectedAnswers[$questionIndex] = $selectedAnswer;
}
?>
<?php
    // Retrieve the selected answer from the form submission
    $selectedAnswer = isset($_POST['answer']) ? $_POST['answer'] : '';

    // Store the selected answer in the $selectedAnswers array using the question index as the key
    $selectedAnswers[$questionIndex] = $selectedAnswer;
    ?>
    <?php if ($questionIndex >= 0 && $questionIndex < count($questions)) : ?>
        <form method="post">
            <p>Question: <?php echo $questions[$questionIndex]['ques']; ?></p>
            <div>
			    <?php if (isset($questions[$questionIndex]['txtA'])) : ?>
                    <input type="radio" name="answer" value="A" <?php echo ($selectedAnswer === 'A') ? 'checked' : ''; ?>>
                    <label for="optionA"><?php echo $questions[$questionIndex]['txtA']; ?></label><br>
                <?php endif; ?>
				<?php if (isset($questions[$questionIndex]['txtB'])) : ?>
                    <input type="radio" name="answer" value="B" <?php echo ($selectedAnswer === 'B') ? 'checked' : ''; ?>>
                    <label for="optionB"><?php echo $questions[$questionIndex]['txtB']; ?></label><br>
                <?php endif; ?>
				<?php if (isset($questions[$questionIndex]['txtC'])) : ?>
                    <input type="radio" name="answer" value="C" <?php echo ($selectedAnswer === 'C') ? 'checked' : ''; ?>>
                    <label for="optionC"><?php echo $questions[$questionIndex]['txtC']; ?></label><br>
                <?php endif; ?>
				<?php if (isset($questions[$questionIndex]['txtD'])) : ?>
                    <input type="radio" name="answer" value="D" <?php echo ($selectedAnswer === 'D') ? 'checked' : ''; ?>>
                    <label for="optionD"><?php echo $questions[$questionIndex]['txtD']; ?></label><br>
                <?php endif; ?> 
				<!-- Add a hidden input field to preserve the $selectedAnswers array -->
    <input type="hidden" name="selectedAnswers" value="<?php echo base64_encode(serialize($selectedAnswers)); ?>">
            </div>
            <br>
           <?php if ($questionIndex === (count($questions) - 1)) : ?>
            <button type="submit">Submit</button>
          <?php endif; ?>
        </form>

        <?php if ($questionIndex > 0) : ?>
        <a href="take_exam.php?username=<?php echo $username; ?>&index=<?php echo ($questionIndex - 1); ?>">Previous</a>
    <?php endif; ?>

    <?php if ($questionIndex < (count($questions) - 1)) : ?>
        <a href="take_exam.php?username=<?php echo $username; ?>&index=<?php echo ($questionIndex + 1); ?>">Next</a>
    <?php endif; ?>
    <?php else : ?>
        <p>No questions found.</p>
    <?php endif; ?>
</div>
</div>

<script>
 // count down and caluculate the remaining time
    var countdownTimer = <?php echo $totalSeconds; ?>;

    function startCountdown() {
        var timerDisplay = document.getElementById("countdown");

        var minutes, seconds;
        var interval = setInterval(function() {
            minutes = Math.floor(countdownTimer / 60);
            seconds = countdownTimer % 60;

            // Format the countdown timer
            var formattedTimer = ("0" + minutes).slice(-2) + ":" + ("0" + seconds).slice(-2);

            // Display the countdown timer
            timerDisplay.textContent = formattedTimer;

            // Decrement the countdown timer
            countdownTimer--;

            // Check if the countdown timer has reached zero
            if (countdownTimer < 0) {
                clearInterval(interval);
                timerDisplay.textContent = "Time's up!";
            }
        }, 1000);
    }

    // Start the countdown timer
    startCountdown();
</script>

<?php include("footer.php"); ?>

<?php include("footer.php"); ?>
























<?php include("headOfCandidate.php"); ?>
<!-- to display given time that fitch from timer table-->
<?php
// Create a connection
$conn = mysqli_connect("localhost", "root", "", "exam");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch the timer data from the table
$sql = "SELECT hr, min FROM timer";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $hours = $row["hr"];
    $minutes = $row["min"];

    // Format the timer
    $formattedTimer = sprintf("%02d:%02d", $hours, $minutes);

    // Calculate the total seconds for the timer
    $totalSeconds = ($hours * 3600) + ($minutes * 60);
} else {
    echo "No timer data found.\n";
}

// Close the database connection
$conn->close();

?>

<?php
// Retrieve the user information from the query string
$username = $_GET['username'];
$con = mysqli_connect("localhost", "root", "", "exam");
$sql = "SELECT first_name, father_name, last_name, institution, sector, level FROM approvecandidate WHERE username='$username'";
$res = mysqli_query($con, $sql);
$row = mysqli_fetch_assoc($res);
mysqli_close($con);

if ($row) {
    $first_name = $row['first_name'];
    $father_name = $row['father_name'];
    $last_name = $row['last_name'];
    $institution = $row['institution'];
    $sector = $row['sector'];
    $level = $row['level'];
} else {
    // Handle the case when user information is not found
    $first_name = "N/A";
    $father_name = "N/A";
    $last_name = "N/A";
}
?>

<?php
// Assuming you have a database connection established
$con = mysqli_connect("localhost", "root", "", "exam");
$sector = $row['sector']; // Retrieve the sector from the user information
$level = $row['level']; // Retrieve the level from the user information

// Retrieve the current question index from the query string
$questionIndex = isset($_GET['index']) ? intval($_GET['index']) : 0;

// Fetch questions from the database
$query = "SELECT * FROM question WHERE sector = '$sector' AND level = '$level'";
$result = mysqli_query($con, $query);

$currentQuestionIndex = 0;
$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
if ($row) {
    $ques = $row['ques'];
    $txA = $row['txtA'];
    $txB = $row['txtB'];
    $txC = $row['txtC'];
    $txD = $row['txtD'];
    $ques_no = $row['ques_no'];
} else {
    echo "No questions found.";
}
?>

<div id="site_content">
    <div id="ques-count">
        <div class="display-info">
            <h4>Your Information<hr></h4>
            <p>username: <?php echo $username; ?></p>
            <p>Full name: <?php echo $first_name . ' ' . $father_name . ' ' . $last_name; ?></p>
            <p>Institution: <?php echo $institution; ?></p>
            <p>Sector: <?php echo $sector . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Level: ' . $level; ?></p>
        </div>
        <?php
        $totalQuestions = 20; // Replace 20 with the actual total number of questions
        $breakPoints = [5, 10, 15, 20]; // Define the break points

        echo '<div class="question-counter">';
        for ($i = 1; $i <= $totalQuestions; $i++) {
            echo '<div class="circle">' . $i . '</div>';
            if (in_array($i, $breakPoints)) {
                echo '<div class="break"></div>';
            }
        }
        echo '</div>';
        ?>
    </div>
    <div id="timer">
        <?php
        echo "Given Time: $formattedTimer<br>";
        echo "Remaining Time: <span id='countdown'></span><br>";
        ?>
    </div>

    <div class="question">
        <table>
            <hr>
            <form id="questionForm" method="get">
                <tr>
                    <td>
                        <strong><?php echo $ques_no; ?>:</strong>
                        <strong><?php echo $ques; ?></strong>
                    </td>
                </tr>
                <tr>
                    <td>
                        <strong>(A)<input type="radio" name="<?php echo $ques_no; ?>" value="A" /><?php echo $txA; ?></strong>
                    </td>
                    <td>
                        <strong>(B)<input type="radio" name="<?php echo $ques_no; ?>" value="B" /><?php echo $txB; ?></strong>
                    </td>
                </tr>
                <tr>
                    <td>
                        <strong>(C)<input type="radio" name="<?php echo $ques_no; ?>" value="C" /><?php echo $txC; ?></strong>
                    </td>
                    <td>
                        <strong>(D)<input type="radio" name="<?php echo $ques_no; ?>" value="D" /><?php echo $txD; ?></strong>
                    </td>
                </tr>
            </table>
        </form>
    </div>

    <div class="navigation-buttons">
    <?php if ($questionIndex > 0) : ?>
        <a href="take_exam.php?username=<?php echo $username; ?>&index=<?php echo ($questionIndex - 1); ?>">Previous</a>
    <?php endif; ?>

    <?php if ($questionIndex < ($totalQuestions - 1)) : ?>
        <a href="take_exam.php?username=<?php echo $username; ?>&index=<?php echo ($questionIndex + 1); ?>">Next</a>
    <?php endif; ?>
</div>
</div>

<script>
    function showQuestion(index, username) {
    var form = document.getElementById('questionForm');
    form.action = 'take_exam.php?username=' + username + '&index=' + index;
    form.submit();
    }
</script>

<script>
    // Set the countdown timer
    var countdownElement = document.getElementById('countdown');
    var totalSecondsRemaining = <?php echo $totalSeconds; ?>; // Total seconds for the timer

    function updateCountdown() {
        var minutes = Math.floor(totalSecondsRemaining / 60);
        var seconds = totalSecondsRemainingcontinued from the previous response:

```html
 % 60;

        // Display the remaining time
        countdownElement.innerHTML = minutes + ":" + (seconds < 10 ? "0" + seconds : seconds);

        // Update the totalSecondsRemaining
        totalSecondsRemaining--;

        // Check if time is up
        if (totalSecondsRemaining < 0) {
            // Handle the time up event
            alert("Time is up!");

            // Submit the form to end the exam
            var form = document.getElementById('questionForm');
            form.action = 'endExam.php?username=<?php echo $username; ?>';
            form.submit();
        }
    }

    // Call the updateCountdown function every second
    setInterval(updateCountdown, 1000);
</script>

</body>

</html>


























<?php include("headOfCandidate.php"); ?>
<!-- to display given time that fetched from timer table-->
<?php
session_start();
// Create a connection
$conn = mysqli_connect("localhost", "root", "", "exam");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch the timer data from the table
$sql = "SELECT hr, min FROM timer";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $hours = $row["hr"];
    $minutes = $row["min"];

    // Format the timer
    $formattedTimer = sprintf("%02d:%02d", $hours, $minutes);

    // Calculate the total seconds for the timer
    $totalSeconds = ($hours * 3600) + ($minutes * 60);
} else {
    echo "No timer data found.\n";
}

// Retrieve the user information from the query string
$username = $_GET['username'];

// Fetch user information from the database
$query = "SELECT first_name, father_name, last_name, institution, sector, level FROM approvecandidate WHERE username='$username'";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);

if ($row) {
    $first_name = $row['first_name'];
    $father_name = $row['father_name'];
    $last_name = $row['last_name'];
    $institution = $row['institution'];
    $sector = $row['sector'];
    $level = $row['level'];
} else {
    // Handle the case when user information is not found
    $first_name = "N/A";
    $father_name = "N/A";
    $last_name = "N/A";
}

// Fetch questions from the database
$query = "SELECT * FROM question WHERE sector = '$sector' AND level = '$level'";
$result = mysqli_query($conn, $query);

$totalQuestions = mysqli_num_rows($result); // Get the total number of questions

// Retrieve the current question index from the query string
$questionIndex = isset($_POST['index']) ? intval($_POST['index']) : 0;

// Retrieve the selected answer from the query string or form submission
$selectedAnswer = isset($_POST['answer']) ? $_POST['answer'] : '';

// Store the selected answer in the session variable
if (!isset($_SESSION['answers'])) {
    $_SESSION['answers'] = array();
}

if ($selectedAnswer !== '') {
    $_SESSION['answers'][$questionIndex] = $selectedAnswer;
}

// Retrieve the previously selected answer for the current question, if available
$previouslySelectedAnswer = isset($_SESSION['answers'][$questionIndex]) ? $_SESSION['answers'][$questionIndex] : '';

// Fetch the current question data
for ($i = 0; $i < $questionIndex; $i++) {
    $row = mysqli_fetch_assoc($result);
}

$row = mysqli_fetch_assoc($result);
if ($row) {
    $ques = $row['ques'];
    $txA = $row['txtA'];
    $txB = $row['txtB'];
    $txC = $row['txtC'];
    $txD = $row['txtD'];
    $ques_no = $row['ques_no'];
} else {
    // Handle the case when the current question data is not found
    $ques = "N/A";
    $txA = "N/A";
    $txB = "N/A";
    $txC = "N/A";
    $txD = "N/A";
    $ques_no = "N/A";
}
// Close the database connection
mysqli_close($conn);
?>

<div id="site_content">
    <div id="ques-count">
        <div class="display-info">
            <h4>Your Information<hr></h4>
            <p>username: <?php echo $username; ?></p>
            <p>Full name: <?php echo $first_name . ' ' . $father_name . ' ' . $last_name; ?></p>
            <p>Institution: <?php echo $institution; ?></p>
            <p>Sector: <?php echo $sector . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Level: ' . $level; ?></p>
        </div>
        <?php
        $breakPoints = [5, 10, 15, 20]; // Define the break points

        echo '<div class="question-counter">';
        for ($i = 1; $i <= $totalQuestions; $i++) {
            echo '<div class="circle">' . $i . '</div>';
            if (in_array($i, $breakPoints)) {
                echo '<div class="break"></div>';
            }
        }
        echo '</div>';
        ?>
    </div>
    <div id="timer">
        <?php
        echo "Given Time: $formattedTimer<br>";
        echo "Remaining Time: <span id='countdown'></span><br>";
        ?>
    </div>
	<?php
   
     // Retrieve the remaining time from the session variable or initialize it to the total seconds
     $secondsRemaining = isset($_SESSION['remainingTime']) ? $_SESSION['remainingTime'] : $totalSeconds;

// Rest of your existing code...

?>

    <div class="question">
    <hr>
    <form id="questionForm" method="POST">
        <table>
            <tr>
                <td>
                    <strong><?php echo $ques_no . ". " . $ques; ?></strong><br>
                    <input type="radio" name="answer_<?php echo $ques_no; ?>" value="A" <?php if ($previouslySelectedAnswer === 'A') echo 'checked'; ?>> <?php echo $txA; ?><br>
                    <input type="radio" name="answer_<?php echo $ques_no; ?>" value="B" <?php if ($previouslySelectedAnswer === 'B') echo 'checked'; ?>> <?php echo $txB; ?><br>
                    <input type="radio" name="answer_<?php echo $ques_no; ?>" value="C" <?php if ($previouslySelectedAnswer === 'C') echo 'checked'; ?>> <?php echo $txC; ?><br>
                    <input type="radio" name="answer_<?php echo $ques_no; ?>" value="D" <?php if ($previouslySelectedAnswer === 'D') echo 'checked'; ?>> <?php echo $txD; ?><br>
                </td>
            </tr>
            <tr>
                <td>

                    <?php if ($questionIndex > 0): ?>
                        <input type="button" value="Previous" onclick="goToPreviousQuestion()">
                    <?php endif; ?>

                    <?php if ($questionIndex < $totalQuestions - 1): ?>
                        <input type="button" id="next-button" value="Next" onclick="goToNextQuestion()">
                    <?php else: ?>
                        <input type="submit" value="Submit">
                    <?php endif; ?>
                </td>
            </tr>
        </table>
        <input type="hidden" name="index" value="<?php echo $questionIndex; ?>">
        <input type="hidden" name="username" value="<?php echo $username; ?>">
    </form>
</div>
</div>

<script>
    // JavaScript code to handle the countdown timer

    // Retrieve the remaining time from the session storage
    var remainingSeconds = sessionStorage.getItem('remainingSeconds');
    if (remainingSeconds === null) {
        // If remaining time is not stored, set it to the total seconds
        remainingSeconds = <?php echo $totalSeconds; ?>;
    } else {
        remainingSeconds = parseInt(remainingSeconds); // Parse the remaining seconds to an integer
    }

    // Set the countdown element
    var countdownElement = document.getElementById('countdown');

    // Function to update the countdown element
    function updateCountdown() {
        var hours = Math.floor(remainingSeconds / 3600);
        var minutes = Math.floor((remainingSeconds % 3600) / 60);
        var seconds = remainingSeconds % 60;

        // Format the time and update the countdown element
        var formattedTime = ('0' + hours).slice(-2) + ':' + ('0' + minutes).slice(-2) + ':' + ('0' + seconds).slice(-2);
        countdownElement.textContent = formattedTime;

        if (remainingSeconds <= 0) {
            // Redirect to the next question when the timer reaches zero
            window.location.href = "next_question.php?index=" + <?php echo $questionIndex + 1; ?> + "&username=" + "<?php echo $username; ?>";
        } else {
            // Decrease the remaining time by 1 second
            remainingSeconds--;
            // Store the updated remaining time in the session storage
            sessionStorage.setItem('remainingSeconds', remainingSeconds);
            // Call the updateCountdown function after 1 second
            setTimeout(updateCountdown, 1000);
        }
    }

    // Call the updateCountdown function to start the countdown
    updateCountdown();

    // Function to handle the "Next Question" button click
function goToNextQuestion(ques_no) {
    // Get the selected answer
    var selectedAnswer = document.querySelector('input[name="answer_' + ques_no + '"]:checked').value;

    // Store the selected answer in the session storage
    sessionStorage.setItem('answer_' + ques_no, selectedAnswer);

    // Submit the form to the next question
    document.getElementById('questionForm').submit();
}

function goToPreviousQuestion(ques_no) {
    // Get the selected answer
    var selectedAnswer = document.querySelector('input[name="answer_' + ques_no + '"]:checked').value;

    // Store the selected answer in the session storage
    sessionStorage.setItem('answer_' + ques_no, selectedAnswer);

    // Submit the form to the previous question
    document.getElementById('questionForm').submit();
}

</script>





















<?php include("headOfCandidate.php"); ?>
<!-- to display given time that fetched from timer table-->
<?php
session_start();
// Create a connection
$conn = mysqli_connect("localhost", "root", "", "exam");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch the timer data from the table
$sql = "SELECT hr, min FROM timer";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $hours = $row["hr"];
    $minutes = $row["min"];

    // Format the timer
    $formattedTimer = sprintf("%02d:%02d", $hours, $minutes);

    // Calculate the total seconds for the timer
    $totalSeconds = ($hours * 3600) + ($minutes * 60);
} else {
    echo "No timer data found.\n";
}

// Retrieve the user information from the query string
$username = $_GET['username'];

// Fetch user information from the database
$query = "SELECT first_name, father_name, last_name, institution, sector, level FROM approvecandidate WHERE username='$username'";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);

if ($row) {
    $first_name = $row['first_name'];
    $father_name = $row['father_name'];
    $last_name = $row['last_name'];
    $institution = $row['institution'];
    $sector = $row['sector'];
    $level = $row['level'];
} else {
    // Handle the case when user information is not found
    $first_name = "N/A";
    $father_name = "N/A";
    $last_name = "N/A";
}

// Fetch questions from the database
$query = "SELECT * FROM question WHERE sector = '$sector' AND level = '$level'";
$result = mysqli_query($conn, $query);

$totalQuestions = mysqli_num_rows($result); // Get the total number of questions

// Retrieve the current question index from the query string
$questionIndex = isset($_GET['index']) ? intval($_GET['index']) : 0;

// Retrieve the selected answer from the query string or form submission
$selectedAnswer = isset($_GET['answer']) ? $_GET['answer'] : '';

// Store the selected answer in the session variable
if (!isset($_SESSION['answers'])) {
    $_SESSION['answers'] = array();
}

if ($selectedAnswer !== '') {
    $_SESSION['answers'][$questionIndex] = $selectedAnswer;
}

// Retrieve the previously selected answer for the current question, if available
$previouslySelectedAnswer = isset($_SESSION['answers'][$questionIndex]) ? $_SESSION['answers'][$questionIndex] : '';

// Fetch the current question data
for ($i = 0; $i < $questionIndex; $i++) {
    $row = mysqli_fetch_assoc($result);
}

$row = mysqli_fetch_assoc($result);
if ($row) {
    $ques = $row['ques'];
    $txA = $row['txtA'];
    $txB = $row['txtB'];
    $txC = $row['txtC'];
    $txD = $row['txtD'];
    $ques_no = $row['ques_no'];
} else {
    // Handle the case when the current question data is not found
    $ques = "N/A";
    $txA = "N/A";
    $txB = "N/A";
    $txC = "N/A";
    $txD = "N/A";
    $ques_no = "N/A";
}
// Close the database connection
mysqli_close($conn);
?>

<div id="site_content">
    <div id="ques-count">
        <div class="display-info">
            <h4>Your Information<hr></h4>
            <p>username: <?php echo $username; ?></p>
            <p>Full name: <?php echo $first_name . ' ' . $father_name . ' ' . $last_name; ?></p>
            <p>Institution: <?php echo $institution; ?></p>
            <p>Sector: <?php echo $sector . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Level: ' . $level; ?></p>
        </div>
        <?php
        $breakPoints = [5, 10, 15, 20]; // Define the break points

        echo '<div class="question-counter">';
        for ($i = 1; $i <= $totalQuestions; $i++) {
            echo '<div class="circle">' . $i . '</div>';
            if (in_array($i, $breakPoints)) {
                echo '<div class="break"></div>';
            }
        }
        echo '</div>';
        ?>
    </div>
    <div id="timer">
        <?php
        echo "Given Time: $formattedTimer<br>";
        echo "Remaining Time: <span id='countdown'></span><br>";
        ?>
    </div>
	<?php
   
     // Retrieve the remaining time from the session variable or initialize it to the total seconds
     $secondsRemaining = isset($_SESSION['remainingTime']) ? $_SESSION['remainingTime'] : $totalSeconds;

// Rest of your existing code...

?>

    <div class="question">
    <hr>
    <form id="questionForm" method="get">
        <table>
            <tr>
                <td>
                    <strong><?php echo $ques_no . ". " . $ques; ?></strong><br>
                    <input type="radio" name="answer_<?php echo $ques_no; ?>" value="A" <?php if ($previouslySelectedAnswer === 'A') echo 'checked'; ?>> <?php echo $txA; ?><br>
                    <input type="radio" name="answer_<?php echo $ques_no; ?>" value="B" <?php if ($previouslySelectedAnswer === 'B') echo 'checked'; ?>> <?php echo $txB; ?><br>
                    <input type="radio" name="answer_<?php echo $ques_no; ?>" value="C" <?php if ($previouslySelectedAnswer === 'C') echo 'checked'; ?>> <?php echo $txC; ?><br>
                    <input type="radio" name="answer_<?php echo $ques_no; ?>" value="D" <?php if ($previouslySelectedAnswer === 'D') echo 'checked'; ?>> <?php echo $txD; ?><br>
                </td>
            </tr>
            <tr>
                <td>
                    <?php if ($questionIndex > 0): ?>
                        <input type="button" value="Previous" onclick="goToPreviousQuestion()">
                    <?php endif; ?>

                    <?php if ($questionIndex < $totalQuestions - 1): ?>
                        <input type="button" id="next-button" value="Next" onclick="goToNextQuestion()">
                    <?php else: ?>
                        <input type="submit" value="Submit">
                    <?php endif; ?>
                </td>
            </tr>
        </table>
        <input type="hidden" name="index" value="<?php echo $questionIndex; ?>">
        <input type="hidden" name="username" value="<?php echo $username; ?>">
    </form>
</div>
</div>

<script>
    // JavaScript code to handle the countdown timer

    // Retrieve the remaining time from the session storage
    var remainingSeconds = sessionStorage.getItem('remainingSeconds');
    if (remainingSeconds === null) {
        // If remaining time is not stored, set it to the total seconds
        remainingSeconds = <?php echo $totalSeconds; ?>;
    } else {
        remainingSeconds = parseInt(remainingSeconds); // Parse the remaining seconds to an integer
    }

    // Set the countdown element
    var countdownElement = document.getElementById('countdown');

    // Function to update the countdown element
    function updateCountdown() {
        var hours = Math.floor(remainingSeconds / 3600);
        var minutes = Math.floor((remainingSeconds % 3600) / 60);
        var seconds = remainingSeconds % 60;

        // Format the time and update the countdown element
        var formattedTime = ('0' + hours).slice(-2) + ':' + ('0' + minutes).slice(-2) + ':' + ('0' + seconds).slice(-2);
        countdownElement.textContent = formattedTime;

        if (remainingSeconds <= 0) {
            // Redirect to the next question when the timer reaches zero
            window.location.href = "next_question.php?index=<?php echo $questionIndex + 1; ?>&username=<?php echo $username; ?>";
        } else {
            // Decrease the remaining time by 1 second
            remainingSeconds--;
            // Store the updated remaining time in the session storage
            sessionStorage.setItem('remainingSeconds', remainingSeconds);
            // Call the updateCountdown function after 1 second
            setTimeout(updateCountdown, 1000);
        }
    }

    // Call the updateCountdown function to start the countdown
    updateCountdown();

    // Function to handle the "Next Question" button click
    function goToNextQuestion() {
        // Get the selected answer
        var selectedAnswer = document.querySelector('input[name="answer_<?php echo $ques_no; ?>"]:checked').value;

        // Store the selected answer in the session storage
        sessionStorage.setItem('answer_<?php echo $ques_no; ?>', selectedAnswer);

        // Redirect to the next question
        window.location.href = "take_exam.php?index=<?php echo $questionIndex + 1; ?>&username=<?php echo $username; ?>";
    }

    // Function to handle the "Previous Question" button click
    function goToPreviousQuestion() {
        // Get the selected answer
        var selectedAnswer = document.querySelector('input[name="answer_<?php echo $ques_no; ?>"]:checked').value;

        // Store the selected answer in the session storage
        sessionStorage.setItem('answer_<?php echo $ques_no; ?>', selectedAnswer);

        // Redirect to the previous question
        window.location.href = "take_exam.php?index=<?php echo max($questionIndex - 1, 0); ?>&username=<?php echo $username; ?>";
    }
</script>


























<?php include("headOfCandidate.php"); ?>
<!-- to display given time that fetched from timer table-->
<?php
session_start();
// Create a connection
$conn = mysqli_connect("localhost", "root", "", "exam");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch the timer data from the table
$sql = "SELECT hr, min FROM timer";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $hours = $row["hr"];
    $minutes = $row["min"];

    // Format the timer
    $formattedTimer = sprintf("%02d:%02d", $hours, $minutes);

    // Calculate the total seconds for the timer
    $totalSeconds = ($hours * 3600) + ($minutes * 60);
} else {
    echo "No timer data found.\n";
}

// Retrieve the user information from the query string
$username = $_GET['username'];

// Fetch user information from the database
$query = "SELECT first_name, father_name, last_name, institution, sector, level FROM approvecandidate WHERE username='$username'";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);

if ($row) {
    $first_name = $row['first_name'];
    $father_name = $row['father_name'];
    $last_name = $row['last_name'];
    $institution = $row['institution'];
    $sector = $row['sector'];
    $level = $row['level'];
} else {
    // Handle the case when user information is not found
    $first_name = "N/A";
    $father_name = "N/A";
    $last_name = "N/A";
}

// Fetch questions from the database
$query = "SELECT * FROM question WHERE sector = '$sector' AND level = '$level'";
$result = mysqli_query($conn, $query);

$totalQuestions = mysqli_num_rows($result); // Get the total number of questions

// Retrieve the current question index from the query string
$questionIndex = isset($_GET['index']) ? intval($_GET['index']) : 0;

// Retrieve the selected answer from the query string or form submission
$selectedAnswer = isset($_GET['answer']) ? $_GET['answer'] : '';

// Store the selected answer in the session variable
if (!isset($_SESSION['answers'])) {
    $_SESSION['answers'] = array();
}

if ($selectedAnswer !== '') {
    $_SESSION['answers'][$questionIndex] = $selectedAnswer;
}

// Retrieve the previously selected answer for the current question, if available
$previouslySelectedAnswer = isset($_SESSION['answers'][$questionIndex]) ? $_SESSION['answers'][$questionIndex] : '';

// Fetch the current question data
for ($i = 0; $i < $questionIndex; $i++) {
    $row = mysqli_fetch_assoc($result);
}

$row = mysqli_fetch_assoc($result);
if ($row) {
    $ques = $row['ques'];
    $txA = $row['txtA'];
    $txB = $row['txtB'];
    $txC = $row['txtC'];
    $txD = $row['txtD'];
    $ques_no = $row['ques_no'];
} else {
    // Handle the case when the current question data is not found
    $ques = "N/A";
    $txA = "N/A";
    $txB = "N/A";
    $txC = "N/A";
    $txD = "N/A";
    $ques_no = "N/A";
}
// Close the database connection
mysqli_close($conn);
?>

<div id="site_content">
    <div id="ques-count">
        <div class="display-info">
            <h4>Your Information<hr></h4>
            <p>username: <?php echo $username; ?></p>
            <p>Full name: <?php echo $first_name . ' ' . $father_name . ' ' . $last_name; ?></p>
            <p>Institution: <?php echo $institution; ?></p>
            <p>Sector: <?php echo $sector . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Level: ' . $level; ?></p>
        </div>
        <?php
        $breakPoints = [5, 10, 15, 20]; // Define the break points

        echo '<div class="question-counter">';
        for ($i = 1; $i <= $totalQuestions; $i++) {
            echo '<div class="circle">' . $i . '</div>';
            if (in_array($i, $breakPoints)) {
                echo '<div class="break"></div>';
            }
        }
        echo '</div>';
        ?>
    </div>
    <div id="timer">
        <?php
        echo "Given Time: $formattedTimer<br>";
        echo "Remaining Time: <span id='countdown'></span><br>";
        ?>
    </div>
	<?php
   
     // Retrieve the remaining time from the session variable or initialize it to the total seconds
     $secondsRemaining = isset($_SESSION['remainingTime']) ? $_SESSION['remainingTime'] : $totalSeconds;

// Rest of your existing code...

?>

    <div class="question">
        <hr>
        <form id="questionForm" method="get">
            <table>
                <tr>
                    <td>
                       <strong><?php echo $ques_no . ". " . $ques; ?></strong><br>
                    <input type="radio" name="answer_<?php echo $ques_no; ?>" value="A" <?php if ($previouslySelectedAnswer === 'A') echo 'checked'; ?>> <?php echo $txA; ?><br>
                    <input type="radio" name="answer_<?php echo $ques_no; ?>" value="B" <?php if ($previouslySelectedAnswer === 'B') echo 'checked'; ?>> <?php echo $txB; ?><br>
                    <input type="radio" name="answer_<?php echo $ques_no; ?>" value="C" <?php if ($previouslySelectedAnswer === 'C') echo 'checked'; ?>> <?php echo $txC; ?><br>
                    <input type="radio" name="answer_<?php echo $ques_no; ?>" value="D" <?php if ($previouslySelectedAnswer === 'D') echo 'checked'; ?>> <?php echo $txD; ?><br>
                    </td>
                </tr>
                <tr>
                    <td>
						<?php if ($questionIndex > 0): ?>
                            <form method="get" action="take_exam.php" style="display: inline;">
                            <input type="hidden" name="index" value="<?php echo max($questionIndex - 1, 0); ?>">
                            <input type="hidden" name="username" value="<?php echo $username; ?>">
                            <input type="submit" value="Previous">
                             </form>
                        <?php endif; ?>

                        <?php if ($questionIndex < $totalQuestions - 1): ?>
                            <form method="get" action="take_exam.php" style="display: inline;">
                            <input type="hidden" name="index" value="<?php echo $questionIndex + 1; ?>">
                            <input type="hidden" name="username" value="<?php echo $username; ?>">
                            <input type="submit" value="Next">
                            </form>
                        <?php else: ?>
                            <form method="post" action="" style="display: inline;">
                             <input type="hidden" name="username" value="<?php echo $username; ?>">
                            <input type="submit" value="Submit">
                            </form>
                        <?php endif; ?>
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>

<script>
   // JavaScript code to handle the countdown timer

// Retrieve the remaining time from the session storage
var remainingSeconds = sessionStorage.getItem('remainingSeconds');
if (remainingSeconds === null) {
  // If remaining time is not stored, set it to the total seconds
  remainingSeconds = <?php echo $totalSeconds; ?>;
} else {
  remainingSeconds = parseInt(remainingSeconds); // Parse the remaining seconds to an integer
}

// Set the countdown element
var countdownElement = document.getElementById('countdown');

// Function to update the countdown element
function updateCountdown() {
  var hours = Math.floor(remainingSeconds / 3600);
  var minutes = Math.floor((remainingSeconds % 3600) / 60);
  var seconds = remainingSeconds % 60;

  // Format the time and update the countdown element
  var formattedTime = ('0' + hours).slice(-2) + ':' + ('0' + minutes).slice(-2) + ':' + ('0' + seconds).slice(-2);
  countdownElement.textContent = formattedTime;

  if (remainingSeconds <= 0) {
    // Redirect to the next question when the timer reaches zero
    window.location.href = "next_question.php?index=<?php echo $questionIndex + 1; ?>&username=<?php echo $username; ?>";
  } else {
    // Decrease the remaining time by 1 second
    remainingSeconds--;
    // Store the updated remaining time in the session storage
    sessionStorage.setItem('remainingSeconds', remainingSeconds);
    // Call the updateCountdown function after 1 second
    setTimeout(updateCountdown, 1000);
  }
}

// Call the updateCountdown function to start the countdown
updateCountdown();

// Event listener for the "Next Question" button
var nextButton = document.getElementById('next-button');
nextButton.addEventListener('click', function() {
  // Get the selected answer
  var selectedAnswer = document.querySelector('input[name="answer_<?php echo $ques_no; ?>"]:checked').value;

  // Store the selected answer in the session storage
  sessionStorage.setItem('answer_<?php echo $ques_no; ?>', selectedAnswer);

  // Remove the remaining time from the session storage
  sessionStorage.removeItem('remainingSeconds');
});
</script>















































<?php include("headOfCandidate.php"); ?>
<!-- to display given time that fetched from timer table-->
<?php
session_start();
// Create a connection
$conn = mysqli_connect("localhost", "root", "", "exam");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch the timer data from the table
$sql = "SELECT hr, min FROM timer";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $hours = $row["hr"];
    $minutes = $row["min"];

    // Format the timer
    $formattedTimer = sprintf("%02d:%02d", $hours, $minutes);

    // Calculate the total seconds for the timer
    $totalSeconds = ($hours * 3600) + ($minutes * 60);
} else {
    echo "No timer data found.\n";
}

// Retrieve the user information from the query string
$username = $_GET['username'];

// Fetch user information from the database
$query = "SELECT first_name, father_name, last_name, institution, sector, level FROM approvecandidate WHERE username='$username'";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);

if ($row) {
    $first_name = $row['first_name'];
    $father_name = $row['father_name'];
    $last_name = $row['last_name'];
    $institution = $row['institution'];
    $sector = $row['sector'];
    $level = $row['level'];
} else {
    // Handle the case when user information is not found
    $first_name = "N/A";
    $father_name = "N/A";
    $last_name = "N/A";
}

// Fetch questions from the database
$query = "SELECT * FROM question WHERE sector = '$sector' AND level = '$level'";
$result = mysqli_query($conn, $query);

$totalQuestions = mysqli_num_rows($result); // Get the total number of questions

// Retrieve the current question index from the query string
$questionIndex = isset($_GET['index']) ? intval($_GET['index']) : 0;

// Retrieve the selected answer from the query string or form submission
$selectedAnswer = isset($_GET['answer']) ? $_GET['answer'] : '';

// Store the selected answer in the session variable
if (!isset($_SESSION['answers'])) {
    $_SESSION['answers'] = array();
}

if ($selectedAnswer !== '') {
    $_SESSION['answers'][$questionIndex] = $selectedAnswer;
}

// Retrieve the previously selected answer for the current question, if available
$previouslySelectedAnswer = isset($_SESSION['answers'][$questionIndex]) ? $_SESSION['answers'][$questionIndex] : '';

// Fetch the current question data
for ($i = 0; $i < $questionIndex; $i++) {
    $row = mysqli_fetch_assoc($result);
}

$row = mysqli_fetch_assoc($result);
if ($row) {
    $ques = $row['ques'];
    $txA = $row['txtA'];
    $txB = $row['txtB'];
    $txC = $row['txtC'];
    $txD = $row['txtD'];
    $ques_no = $row['ques_no'];
} else {
    // Handle the case when the current question data is not found
    $ques = "N/A";
    $txA = "N/A";
    $txB = "N/A";
    $txC = "N/A";
    $txD = "N/A";
    $ques_no = "N/A";
}
// Close the database connection
mysqli_close($conn);
?>

<div id="site_content">
    <div id="ques-count">
        <div class="display-info">
            <h4>Your Information<hr></h4>
            <p>username: <?php echo $username; ?></p>
            <p>Full name: <?php echo $first_name . ' ' . $father_name . ' ' . $last_name; ?></p>
            <p>Institution: <?php echo $institution; ?></p>
            <p>Sector: <?php echo $sector . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Level: ' . $level; ?></p>
        </div>
        <?php
        $breakPoints = [5, 10, 15, 20]; // Define the break points

        echo '<div class="question-counter">';
        for ($i = 1; $i <= $totalQuestions; $i++) {
            echo '<div class="circle">' . $i . '</div>';
            if (in_array($i, $breakPoints)) {
                echo '<div class="break"></div>';
            }
        }
        echo '</div>';
        ?>
    </div>
    <div id="timer">
        <?php
        echo "Given Time: $formattedTimer<br>";
        echo "Remaining Time: <span id='countdown'></span><br>";
        ?>
    </div>
	<?php
   
     // Retrieve the remaining time from the session variable or initialize it to the total seconds
     $secondsRemaining = isset($_SESSION['remainingTime']) ? $_SESSION['remainingTime'] : $totalSeconds;

// Rest of your existing code...

?>

 <div class="question">
    <hr>
    <form id="questionForm" method="get">
        <table>
            <tr>
                <td>
                   <strong><?php echo $ques_no . ". " . $ques; ?></strong><br>
                <input type="radio" name="answer_<?php echo $ques_no; ?>" value="A" <?php if ($previouslySelectedAnswer === 'A') echo 'checked'; ?>> <?php echo $txA; ?><br>
                <input type="radio" name="answer_<?php echo $ques_no; ?>" value="B" <?php if ($previouslySelectedAnswer === 'B') echo 'checked'; ?>> <?php echo $txB; ?><br>
                <input type="radio" name="answer_<?php echo $ques_no; ?>" value="C" <?php if ($previouslySelectedAnswer === 'C') echo 'checked'; ?>> <?php echo $txC; ?><br>
                <input type="radio" name="answer_<?php echo $ques_no; ?>" value="D" <?php if ($previouslySelectedAnswer === 'D') echo 'checked'; ?>> <?php echo $txD; ?><br>
                </td>
            </tr>
            <tr>
                <td>
                    <?php if ($questionIndex > 0): ?>
                        <input type="hidden" name="index" value="<?php echo max($questionIndex - 1, 0); ?>">
                        <input type="hidden" name="username" value="<?php echo $username; ?>">
                        <input type="button" value="Previous" id="previous-button">
                    <?php endif; ?>

                    <?php if ($questionIndex < $totalQuestions - 1): ?>
                        <input type="hidden" name="index" value="<?php echo $questionIndex + 1; ?>">
                        <input type="hidden" name="username" value="<?php echo $username; ?>">
                        <input type="button" value="Next" id="next-button" name="submit">
                    <?php else: ?>
                        <input type="hidden" name="username" value="<?php echo $username; ?>">
                        <input type="submit" value="Submit">
                    <?php endif; ?>
                </td>
            </tr>
        </table>
    </form>
</div>

<script>
   // JavaScript code to handle the countdown timer and checking of previously selected answers

// Retrieve the remaining time from the session storage
var remainingSeconds = sessionStorage.getItem('remainingSeconds');
if (remainingSeconds === null) {
    // If remaining time is not stored, set it to the total seconds
    remainingSeconds = <?php echo $totalSeconds; ?>;
} else {
    remainingSeconds = parseInt(remainingSeconds); // Parse the remaining seconds to an integer
}

// Set the countdown element
var countdownElement = document.getElementById('countdown');

// Function to update the countdown element
function updateCountdown() {
    var hours = Math.floor(remainingSeconds / 3600);
    var minutes = Math.floor((remainingSeconds % 3600) / 60);
    var seconds = remainingSeconds % 60;

    // Format the time and update the countdown element
    var formattedTime = ('0' + hours).slice(-2) + ':' + ('0' + minutes).slice(-2) + ':' + ('0' + seconds).slice(-2);
    countdownElement.textContent = formattedTime;

    if (remainingSeconds <= 0) {
        // Redirect to the next question when the timer reaches zero
        window.location.href = "take_exam.php?index=<?php echo $questionIndex + 1; ?>&username=<?php echo $username; ?>";
    } else {
        // Decrease the remaining time by 1 second
        remainingSeconds--;
        // Store the updated remaining time in the session storage
        sessionStorage.setItem('remainingSeconds', remainingSeconds);
        // Call the updateCountdown function after 1 second
        setTimeout(updateCountdown, 1000);
    }
}

// Retrieve the previously selected answer for the current question, if available
var previouslySelectedAnswer = sessionStorage.getItem('answer_<?php echo $ques_no; ?>');

// Set the radio button's checked property to true for the previously selected answer
var radioButton = document.querySelector('input[name="answer_<?php echo $ques_no; ?>"][value="' + previouslySelectedAnswer + '"]');
if (radioButton) {
    radioButton.checked = true;
}

// Call the updateCountdown function to start the countdown
if (remainingSeconds > 0) {
    updateCountdown();
}

// Event listener for the "Next Question" button
var nextButton = document.getElementById('next-button');
nextButton.addEventListener('click', function(event) {
    // Prevent the default form submission behavior
    event.preventDefault();

    // Get the selected answer
    var selectedAnswer = document.querySelector('input[name="answer_<?php echo $ques_no; ?>"]:checked').value;

    // Store the selected answer in the session storage
    sessionStorage.setItem('answer_<?php echo $ques_no; ?>', selectedAnswer);

    // Redirect to the next question
    window.location.href = "take_exam.php?index=<?php echo $questionIndex + 1; ?>&username=<?php echo $username; ?>";
});

// Event listener for the "Previous Question" button
var previousButton = document.getElementById('previous-button');
previousButton.addEventListener('click', function(event) {
    // Prevent the default form submission behavior
    event.preventDefault();

    // Get the selected answer for the previous question
    var previousSelectedAnswer = sessionStorage.getItem('answer_<?php echo $ques_no; ?>');

    // Set the radio button's checked property to true for the previously selected answer
    var radioButton = document.querySelector('input[name="answer_<?php echo $ques_no; ?>"][value="' + previousSelectedAnswer + '"]');
    if (radioButton) {
        radioButton.checked = true;
    }

    // Update the remaining time in the session storage
    sessionStorage.setItem('remainingSeconds', remainingSeconds);

    // Redirect to the previous question
    window.location.href = "take_exam.php?index=<?php echo max($questionIndex - 1, 0); ?>&username=<?php echo $username; ?>";
});
</script>































<?php include("headOfCandidate.php"); ?>
<!-- to display given time that fetched from timer table-->
<?php
session_start();
// Create a connection
$conn = mysqli_connect("localhost", "root", "", "exam");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch the timer data from the table
$sql = "SELECT hr, min FROM timer";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $hours = $row["hr"];
    $minutes = $row["min"];

    // Format the timer
    $formattedTimer = sprintf("%02d:%02d", $hours, $minutes);

    // Calculate the total seconds for the timer
    $totalSeconds = ($hours * 3600) + ($minutes * 60);
} else {
    echo "No timer data found.\n";
}

// Retrieve the user information from the query string
$username = $_GET['username'];

// Fetch user information from the database
$query = "SELECT first_name, father_name, last_name, institution, sector, level FROM approvecandidate WHERE username='$username'";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);

if ($row) {
    $first_name = $row['first_name'];
    $father_name = $row['father_name'];
    $last_name = $row['last_name'];
    $institution = $row['institution'];
    $sector = $row['sector'];
    $level = $row['level'];
} else {
    // Handle the case when user information is not found
    $first_name = "N/A";
    $father_name = "N/A";
    $last_name = "N/A";
}

// Fetch questions from the database
$query = "SELECT * FROM question WHERE sector = '$sector' AND level = '$level'";
$result = mysqli_query($conn, $query);

$totalQuestions = mysqli_num_rows($result); // Get the total number of questions

// Retrieve the current question index from the query string
$questionIndex = isset($_GET['index']) ? intval($_GET['index']) : 0;

// Retrieve the selected answer from the query string or form submission
$selectedAnswer = isset($_GET['answer']) ? $_GET['answer'] : '';

// Store the selected answer in the session variable
if (!isset($_SESSION['answers'])) {
    $_SESSION['answers'] = array();
}

if ($selectedAnswer !== '') {
    $_SESSION['answers'][$questionIndex] = $selectedAnswer;
}

// Retrieve the previously selected answer for the current question, if available
$previouslySelectedAnswer = isset($_SESSION['answers'][$questionIndex]) ? $_SESSION['answers'][$questionIndex] : '';

// Fetch the current question data
for ($i = 0; $i < $questionIndex; $i++) {
    $row = mysqli_fetch_assoc($result);
}

$row = mysqli_fetch_assoc($result);
if ($row) {
    $ques = $row['ques'];
    $txA = $row['txtA'];
    $txB = $row['txtB'];
    $txC = $row['txtC'];
    $txD = $row['txtD'];
    $ques_no = $row['ques_no'];
} else {
    // Handle the case when the current question data is not found
    $ques = "N/A";
    $txA = "N/A";
    $txB = "N/A";
    $txC = "N/A";
    $txD = "N/A";
    $ques_no = "N/A";
}
// Close the database connection
mysqli_close($conn);
?>

<div id="site_content">
    <div id="ques-count">
        <div class="display-info">
            <h4>Your Information<hr></h4>
            <p>username: <?php echo $username; ?></p>
            <p>Full name: <?php echo $first_name . ' ' . $father_name . ' ' . $last_name; ?></p>
            <p>Institution: <?php echo $institution; ?></p>
            <p>Sector: <?php echo $sector . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Level: ' . $level; ?></p>
        </div>
        <?php
        $breakPoints = [5, 10, 15, 20]; // Define the break points

        echo '<div class="question-counter">';
        for ($i = 1; $i <= $totalQuestions; $i++) {
            echo '<div class="circle">' . $i . '</div>';
            if (in_array($i, $breakPoints)) {
                echo '<div class="break"></div>';
            }
        }
        echo '</div>';
        ?>
    </div>
    <div id="timer">
        <?php
        echo "Given Time: $formattedTimer<br>";
        echo "Remaining Time: <span id='countdown'></span><br>";
        ?>
    </div>
	<?php
   
     // Retrieve the remaining time from the session variable or initialize it to the total seconds
     $secondsRemaining = isset($_SESSION['remainingTime']) ? $_SESSION['remainingTime'] : $totalSeconds;

// Rest of your existing code...

?>

 <div class="question">
    <hr>
    <form id="questionForm" method="get">
        <table>
            <tr>
                <td>
                   <strong><?php echo $ques_no . ". " . $ques; ?></strong><br>
                <input type="radio" name="answer_<?php echo $ques_no; ?>" value="A" <?php if ($previouslySelectedAnswer === 'A') echo 'checked'; ?>> <?php echo $txA; ?><br>
                <input type="radio" name="answer_<?php echo $ques_no; ?>" value="B" <?php if ($previouslySelectedAnswer === 'B') echo 'checked'; ?>> <?php echo $txB; ?><br>
                <input type="radio" name="answer_<?php echo $ques_no; ?>" value="C" <?php if ($previouslySelectedAnswer === 'C') echo 'checked'; ?>> <?php echo $txC; ?><br>
                <input type="radio" name="answer_<?php echo $ques_no; ?>" value="D" <?php if ($previouslySelectedAnswer === 'D') echo 'checked'; ?>> <?php echo $txD; ?><br>
                </td>
            </tr>
            <tr>
                <td>
                    <?php if ($questionIndex > 0): ?>
                        <input type="hidden" name="index" value="<?php echo max($questionIndex - 1, 0); ?>">
                        <input type="hidden" name="username" value="<?php echo $username; ?>">
                        <input type="button" value="Previous" id="previous-button">
                    <?php endif; ?>

                    <?php if ($questionIndex < $totalQuestions - 1): ?>
                        <input type="hidden" name="index" value="<?php echo $questionIndex + 1; ?>">
                        <input type="hidden" name="username" value="<?php echo $username; ?>">
                        <input type="button" value="Next" id="next-button" name="submit">
                    <?php else: ?>
                        <input type="hidden" name="username" value="<?php echo $username; ?>">
                        <input type="submit" value="Submit" name="submit">
                    <?php endif; ?>
                </td>
            </tr>
        </table>
    </form>
</div>




<script>
   // JavaScript code to handle the countdown timer and checking of previously selected answers

// Retrieve the remaining time from the session storage
var remainingSeconds = sessionStorage.getItem('remainingSeconds');
if (remainingSeconds === null) {
    // If remaining time is not stored, set it to the total seconds
    remainingSeconds = <?php echo $totalSeconds; ?>;
} else {
    remainingSeconds = parseInt(remainingSeconds); // Parse the remaining seconds to an integer
}

// Set the countdown element
var countdownElement = document.getElementById('countdown');

// Function to update the countdown element
function updateCountdown() {
    var hours = Math.floor(remainingSeconds / 3600);
    var minutes = Math.floor((remainingSeconds % 3600) / 60);
    var seconds = remainingSeconds % 60;

    // Format the time and update the countdown element
    var formattedTime = ('0' + hours).slice(-2) + ':' + ('0' + minutes).slice(-2) + ':' + ('0' + seconds).slice(-2);
    countdownElement.textContent = formattedTime;

    if (remainingSeconds <= 0) {
        // Redirect to the next question when the timer reaches zero
        window.location.href = "take_exam.php?index=<?php echo $questionIndex + 1; ?>&username=<?php echo $username; ?>";
    } else {
        // Decrease the remaining time by 1 second
        remainingSeconds--;
        // Store the updated remaining time in the session storage
        sessionStorage.setItem('remainingSeconds', remainingSeconds);
        // Call the updateCountdown function after 1 second
        setTimeout(updateCountdown, 1000);
    }
}

// Retrieve the previously selected answer for the current question, if available
var previouslySelectedAnswer = sessionStorage.getItem('answer_<?php echo $ques_no; ?>');

// Set the radio button's checked property to true for the previously selected answer
var radioButton = document.querySelector('input[name="answer_<?php echo $ques_no; ?>"][value="' + previouslySelectedAnswer + '"]');
if (radioButton) {
    radioButton.checked = true;
}

// Call the updateCountdown function to start the countdown
if (remainingSeconds > 0) {
    updateCountdown();
}

// Event listener for the "Next Question" button
var nextButton = document.getElementById('next-button');
nextButton.addEventListener('click', function(event) {
    // Prevent the default form submission behavior
    event.preventDefault();

    // Get the selected answer
    var selectedAnswer = document.querySelector('input[name="answer_<?php echo $ques_no; ?>"]:checked').value;

    // Store the selected answer in the session storage
    sessionStorage.setItem('answer_<?php echo $ques_no; ?>', selectedAnswer);

    // Check if the clicked button is the "Submit" button
    if (event.target.id === 'submit-button') {
        // Submit the form
        document.getElementById('exam-form').submit();
    } else {
        // Redirect to the next question
        window.location.href = "take_exam.php?index=<?php echo $questionIndex + 1; ?>&username=<?php echo $username; ?>";
    }
});

// Event listener for the "Previous Question" button
var previousButton = document.getElementById('previous-button');
previousButton.addEventListener('click', function(event) {
    // Prevent the default form submission behavior
    event.preventDefault();

    // Get the selected answer for the previous question
    var previousSelectedAnswer = sessionStorage.getItem('answer_<?php echo $ques_no; ?>');

    // Set the radio button's checked property to true for the previously selected answer
    var radioButton = document.querySelector('input[name="answer_<?php echo $ques_no; ?>"][value="' + previousSelectedAnswer + '"]');
    if (radioButton) {
        radioButton.checked = true;
    }

    // Update the remaining time in the session storage
    sessionStorage.setItem('remainingSeconds', remainingSeconds);

    // Redirect to the previous question
    window.location.href = "take_exam.php?index=<?php echo max($questionIndex - 1, 0); ?>&username=<?php echo $username; ?>";
});
</script>

<?php
// Retrieve the selected answers from the session storage when the form is submitted
if (isset($_POST['submit'])) {
    $selectedAnswers = array();
    $totalQuestions;/* total number of questions */; // Replace with the actual total number of questions

    for ($i = 1; $i <= $totalQuestions; $i++) {
        // Retrieve the selected answer for each question
        $selectedAnswer = $_POST['answer_' . $i];

        // Store the selected answer in an array
        $selectedAnswers[$i] = $selectedAnswer;
    }

    // Display the selected answers
    for ($i = 1; $i <= $totalQuestions; $i++) {
        echo "Question " . $i . " - Selected Answer: " . $selectedAnswers[$i] . "<br>";
    }
}
?>