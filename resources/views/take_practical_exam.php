<?php include("headOfCandidate.php"); 
session_start();

// Create a connection
$conn = mysqli_connect("localhost", "root", "", "exam");

// Retrieve the user information from the query string
$username = $_GET['username'];



// Fetch user information from the database
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
} else {
    // Handle the case when user information is not found
    $first_name = "N/A";
    $father_name = "N/A";
    $last_name = "N/A";
}

if ($level <= 2) {
echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11.1.2'></script>";
    echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'You only take theory exam please select theory exam option.'
            }).then(function() {
				window.location.href = 'candidate.php?username=" . $username . "';
            });
          </script>";
} else {

// Fetch the desired date and time from the timer table
$sql = "SELECT desired_date, desired_time FROM timer WHERE list_department='$department' AND level='$level' AND exam_type = 'practical'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $examDate = $row["desired_date"];
    $examTime = $row["desired_time"];
} else {
    echo "No timer data found.\n";
}

// Get the current date and time
    $currentDateTime = date("Y-m-d H:i:s");

    // Compare the current date and time with the desired date and time
		if ($currentDateTime >= $examDate . ' ' . $examTime) {

// Fetch the timer data from the table
$sql = "SELECT hr, min FROM timer WHERE list_department='$department' AND level='$level' AND exam_type = 'practical'";
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



// Fetch questions from the table based on institution and level
$query = "SELECT ques_no, ques FROM question WHERE department='$department' AND level='$level' AND exam_type = 'practical'";
$result = mysqli_query($conn, $query);
$totalQuestions = mysqli_num_rows($result); // Get the total number of questions

$questions = array(); // Array to store the questions

if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $questions[] = $row; // Add each question to the array
    }
}
?>
<head>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.19/dist/sweetalert2.min.css">

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.19/dist/sweetalert2.all.min.js"></script>
<style>
#questionTableBody td {
    display: block;
    margin-bottom: 10px;
}
</style>
</head>

<div id="site_content">
    <div id="ques-count">
        <div class="display-info">
            <h4>Your Information<hr></h4>
            <p>username: <?php echo $username; ?></p>
            <p>Full name: <?php echo $first_name . ' ' . $father_name . ' ' . $last_name; ?></p>
            <p>Institution: <?php echo $institution; ?></p>
            <p>Department: <?php echo $department . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Level: ' . $level; ?></p>
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
        echo "<span id='countdown'></span><br>";
        ?>
    </div><hr>
	<div class ="question">
    <table id="questionTableBody">
    <?php foreach ($questions as $row) {
        $quesNo = $row['ques_no'];
        $ques = $row['ques'];
    ?>
        <tr>
            <td>
                <strong><?php echo $quesNo . ". " . $ques; ?></strong><br>
            </td>
        </tr>
    <?php } ?>
</table>
<div id="buttons">
    <button id="previousButton" class="button" onclick="previousQuestion()">Previous</button>
    <button id="nextButton" class="button" onclick="nextQuestion()">Next</button>
	<button id="submitButton" class="button" onclick="submitExam()" name="submit" style="display: none;">Submit</button>
</div>
</div>
<?php
} else {
echo "<div id='site_content'>";
echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11.1.2'></script>";
echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
        var examStartDate = new Date('$examDate $examTime');
        var currentDate = new Date();
        var timeRemaining = examStartDate - currentDate;
if (timeRemaining > 0) {
            var days = Math.floor(timeRemaining / (1000 * 60 * 60 * 24));
            var hours = Math.floor((timeRemaining % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            var minutes = Math.floor((timeRemaining % (1000 * 60 * 60)) / (1000 * 60));
            var seconds = Math.floor((timeRemaining % (1000 * 60)) / 1000);

            var countdownElement = document.createElement('div');
            countdownElement.id = 'countdown';
            document.getElementById('site_content').appendChild(countdownElement);

            var countdownText = 'The exam is not available yet. Please wait for the scheduled time. The exam will start in: ';
            countdownText += days + ' days, ' + hours + ' hours, ' + minutes + ' minutes, <span id=\"countdown-seconds\">' + seconds + '</span> seconds. Please wait...';

            Swal.fire({
                title: 'Exam Not Available',
                html: countdownText,
                icon: 'info',
                confirmButtonText: 'OK',
                allowOutsideClick: false // Prevents closing the dialog by clicking outside
            }).then(function() {
                window.location.href = 'candidate.php?username=$username';
            });
        } else {
            window.location.href = 'candidate.php?username=$username';
        }
    });
</script>";
echo "</div>";
}
}
?>

</div>

<script>
	var selectedAnswers = <?php echo isset($_SESSION['answers']) ? json_encode($_SESSION['answers']) : '{}'; ?>;
    var currentQuestionIndex = 0; // Variable to keep track of the current question index
    var questionRows = <?php echo json_encode($questions); ?>; // Retrieve the questions array

// Function to start the countdown
function startCountdown(totalSeconds) {
    var countdownElement = document.getElementById('countdown');
    var remainingTime = localStorage.getItem('remainingTime');

    if (remainingTime !== null) {
        remainingTime = parseInt(remainingTime);
    } else {
        remainingTime = totalSeconds;
        localStorage.setItem('remainingTime', remainingTime); // Set initial remaining time
    }

    var countdownInterval = setInterval(function() {
        var hours = Math.floor(remainingTime / 3600);
        var minutes = Math.floor((remainingTime % 3600) / 60);
        var seconds = remainingTime % 60;

        var formattedHours = String(hours).padStart(2, '0');
        var formattedMinutes = String(minutes).padStart(2, '0');
        var formattedSeconds = String(seconds).padStart(2, '0');

        countdownElement.textContent = 'Remaining Time: ' + formattedHours + ':' + formattedMinutes + ':' + formattedSeconds;

        remainingTime--;

        if (remainingTime < 0) {
            clearInterval(countdownInterval);
            countdownElement.textContent = 'Time Up!';
            submitExam();
        }
    }, 1000);

    window.addEventListener('beforeunload', function(event) {
        localStorage.setItem('remainingTime', remainingTime); // Update the remaining time when leaving the page
        delete event['returnValue'];
    });

    window.addEventListener('load', function() {
        localStorage.removeItem('remainingTime'); // Clear the remaining time when returning to the exam page
    });
}

// Load the current question and update the buttons
function loadQuestion(questionIndex) {
    var question = questionRows[questionIndex];
    var questionTableBody = document.getElementById('questionTableBody');
    var html = "<tr>" +
        "<td><strong>" + question.ques_no + ". " + question.ques + "</strong><br>" +
        "</td>" +
        "</tr>";
    questionTableBody.innerHTML = html;

    // Disable or enable the previous/next buttons based on the question index
    var previousButton = document.getElementById('previousButton');
    var nextButton = document.getElementById('nextButton');
    previousButton.disabled = (questionIndex === 0);
    nextButton.disabled = (questionIndex === questionRows.length - 1);
	
	

	// Check if it's the last question
    if (questionIndex === questionRows.length - 1) {
        // Show the submit button
        var submitButton = document.getElementById('submitButton');
        submitButton.style.display = 'inline';
    } else {
        // Hide the submit button for other questions
        var submitButton = document.getElementById('submitButton');
        submitButton.style.display = 'none';
}}
	// Add onchange event listener to the last question's radio buttons
    var lastQuestionIndex = questionRows.length - 1;
    var lastQuestionRadioButtons = document.getElementsByName('answer_' + questionRows[lastQuestionIndex].ques_no);

    for (var i = 0; i < lastQuestionRadioButtons.length; i++) {
        lastQuestionRadioButtons[i].onchange = function() {
            storeAnswer(questionRows[lastQuestionIndex].ques_no, this.value);
        };
      
 }
function getSelectedAnswer(questionNumber) {
    // Retrieve the selected answer from the session variable for the given question number
    if (typeof selectedAnswers !== 'undefined' && selectedAnswers[questionNumber]) {
        return selectedAnswers[questionNumber];
    }
    return null;
}
// Function to handle the previous button click
function previousQuestion() {
    if (currentQuestionIndex > 0) {
        currentQuestionIndex--;
        console.log('Previous Question Index:', currentQuestionIndex);
        loadQuestion(currentQuestionIndex);
    }
}

function nextQuestion() {
    if (currentQuestionIndex < questionRows.length - 1) {
        currentQuestionIndex++;
        console.log('Next Question Index:', currentQuestionIndex);
        loadQuestion(currentQuestionIndex);
    }
}


// Function to store the selected answer in localStorage
function storeAnswer(questionNumber, answer) {
	
    selectedAnswers[questionNumber] = answer;
    localStorage.setItem('selectedAnswers', JSON.stringify(selectedAnswers));
}

// Function to retrieve the selected answer from localStorage
function getSelectedAnswer(questionNumber) {
    var storedAnswers = localStorage.getItem('selectedAnswers');
    if (storedAnswers !== null) {
        selectedAnswers = JSON.parse(storedAnswers);
        if (selectedAnswers[questionNumber]) {
            return selectedAnswers[questionNumber];
        }
    }
    return null;
}

function submitExam() {
    var selectedAnswersString = [];
    for (var i = 0; i < questionRows.length; i++) {
        var questionNumber = questionRows[i].ques_no;
        var answer = getSelectedAnswer(questionNumber);
        
        if (answer) {
            selectedAnswersString.push(questionNumber + ": Answered");

            // Store the selected answer in the selectedAnswers array
            var selectedOption = String.fromCharCode(65 + parseInt(answer));
            selectedAnswers[questionNumber] = selectedOption;
        } else {
            selectedAnswersString.push(questionNumber + ": <span style='color: red;'>Not answered</span>");

            // Store a placeholder value for unanswered questions in the selectedAnswers array
            selectedAnswers[questionNumber] = "Not answered";
        }
    }
 
    var selectedAnswersStringHTML = selectedAnswersString.join("<br>");

    // Show the selected answers to the user using SweetAlert
    Swal.fire({
        title: 'Are you sure you want to submit?',
        html: selectedAnswersStringHTML,
        icon: 'info',
        showCancelButton: true,
        confirmButtonText: 'OK',
        cancelButtonText: 'Cancel',
        allowOutsideClick: false // Prevents closing the dialog by clicking outside
    }).then((result) => {
        if (result.isConfirmed) {
            // User clicked "OK", redirect to match.php with username and selected answers parameters
            const username = "<?php echo urlencode($username); ?>";
            const selectedAnswersParam = encodeURIComponent(JSON.stringify(selectedAnswers));
            window.location.href = `match.php?username=${username}&answers=${selectedAnswersParam}`;
        } else if (result.dismiss === Swal.DismissReason.cancel) {
            // User clicked the Cancel button or closed the dialog
            Swal.fire('Submission Canceled', 'You have canceled the exam submission.', 'info');
        }
    });

    // Clear the stored answers from localStorage
    localStorage.removeItem('selectedAnswers');
}
// Load the first question
loadQuestion(currentQuestionIndex);

// Retrieve the selected answers from localStorage, if available
var storedAnswers = localStorage.getItem('selectedAnswers');
if (storedAnswers !== null) {
    selectedAnswers = JSON.parse(storedAnswers);
}
// Send the stored answers to match.php
var xhr = new XMLHttpRequest();
xhr.open('GET', 'match.php?answers=' + encodeURIComponent(JSON.stringify(selectedAnswers)), true);
xhr.onreadystatechange = function() {
    if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
        // Handle the response from the server if needed
    }
};
xhr.send();
// Start the countdown with the total seconds
startCountdown(<?php echo $totalSeconds; ?>);
</script>



<?php include("footer.php"); ?>