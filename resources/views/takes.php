<?php include("headOfCandidate.php"); 
session_start();


// Create a connection
$conn = mysqli_connect("localhost", "root", "", "exam");
// Retrieve the user information from the query string
$username = $_GET['username'];


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

// Fetch questions from the table based on institution and level
$query = "SELECT ques_no, ques, txtA, txtB, txtC, txtD FROM question WHERE department='$department' AND level='$level'";
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
    </div>
    <table id="questionTableBody">
    <?php foreach ($questions as $row) {
        $quesNo = $row['ques_no'];
        $ques = $row['ques'];
        $txtA = $row['txtA'];
        $txtB = $row['txtB'];
        $txtC = $row['txtC'];
        $txtD = $row['txtD'];
    ?>
        <tr>
            <td>
                <strong><?php echo $quesNo . ". " . $ques; ?></strong><br>
                <input type="radio" name="answer_<?php echo $quesNo; ?>" value="A" onchange="storeAnswer('<?php echo $quesNo; ?>', this.value)">
                <input type="radio" name="answer_<?php echo $quesNo; ?>" value="B" onchange="storeAnswer('<?php echo $quesNo; ?>', this.value)">
                <input type="radio" name="answer_<?php echo $quesNo; ?>" value="C" onchange="storeAnswer('<?php echo $quesNo; ?>', this.value)">
                <input type="radio" name="answer_<?php echo $quesNo; ?>" value="D" onchange="storeAnswer('<?php echo $quesNo; ?>', this.value)">
            </td>
        </tr>
    <?php } ?>
</table>
<div id="buttons">
    <button id="previousButton" onclick="previousQuestion()">Previous</button>
    <button id="nextButton" onclick="nextQuestion()">Next</button>
    <button id="submitButton" onclick="submitExam()" name="submit">Submit</button>
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
        localStorage.setItem('remainingTime', remainingTime);
        delete event['returnValue'];
    });

    window.addEventListener('load', function() {
        localStorage.removeItem('remainingTime');
    });
}

// Load the current question and update the buttons
function loadQuestion(questionIndex) {
    var question = questionRows[questionIndex];
    var questionTableBody = document.getElementById('questionTableBody');
    var html = "<tr>" +
        "<td><strong>" + question.ques_no + ". " + question.ques + "</strong><br>" +
        "<input type='radio' name='answer_" + question.ques_no + "' value='A' onchange='storeAnswer(" + question.ques_no + ", this.value)' " + (getSelectedAnswer(question.ques_no) === 'A' ? "checked" : "") + "> " + question.txtA + "<br>" +
        "<input type='radio' name='answer_" + question.ques_no + "' value='B' onchange='storeAnswer(" + question.ques_no + ", this.value)' " + (getSelectedAnswer(question.ques_no) === 'B' ? "checked" : "") + "> " + question.txtB + "<br>" +
        "<input type='radio' name='answer_" + question.ques_no + "' value='C' onchange='storeAnswer(" + question.ques_no + ", this.value)' " + (getSelectedAnswer(question.ques_no) === 'C' ? "checked" : "") + "> " + question.txtC + "<br>" +
        "<input type='radio' name='answer_" + question.ques_no + "' value='D' onchange='storeAnswer(" + question.ques_no + ", this.value)' " + (getSelectedAnswer(question.ques_no) === 'D' ? "checked" : "") + "> " + question.txtD +
        "</td>" +
        "</tr>";
    questionTableBody.innerHTML = html;

    // Disable or enable the previous/next buttons based on the question index
    var previousButton = document.getElementById('previousButton');
    var nextButton = document.getElementById('nextButton');
    previousButton.disabled = (questionIndex === 0);
    nextButton.disabled = (questionIndex === questionRows.length - 1);
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

function storeAnswer(questionNumber, answer) {
    // Send an AJAX request to the server to store the selected answer in a session
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'takes.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
            // Handle the response from the server if needed

            // Update the selectedAnswers variable with the new answer
            selectedAnswers[questionNumber] = answer;
        }
    };
    xhr.send('questionNumber=' + encodeURIComponent(questionNumber) + '&answer=' + encodeURIComponent(answer));
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
// Function to handle the submit button click
function submitExam() {
    // Display all the selected answers
    var selectedAnswers = [];
    for (var i = 0; i < questionRows.length; i++) {
        var questionNumber = questionRows[i].ques_no;
        var answer = getSelectedAnswer(questionNumber);
        selectedAnswers.push(questionNumber + ": " + (answer ? answer : "Not answered"));
    }
    var selectedAnswersString = selectedAnswers.join("\n");

    // Show the selected answers to the user
    alert("Selected Answers:\n" + selectedAnswersString);
	// Clear the answer session when logging in with another page
   
    // Clear the stored answers from localStorage
    localStorage.removeItem('selectedAnswers');
	// Unset the session variable for the countdown
unset($_SESSION['countdown']);

}
// Load the first question
loadQuestion(currentQuestionIndex);

// Retrieve the selected answers from localStorage, if available
var storedAnswers = localStorage.getItem('selectedAnswers');
if (storedAnswers !== null) {
    selectedAnswers = JSON.parse(storedAnswers);
}

// Start the countdown with the total seconds
startCountdown(<?php echo $totalSeconds; ?>);
</script>

<?php
// ...

// Match the selected answers with the database answers
$matchedAnswers = 0;
$correctAnswers = array(); // Array to store the correct answers indexed by question number

for ($i = 1; $i <= $totalQuestions; $i++) {
    // Retrieve the correct answer for each question from the database
    $query = "SELECT answer FROM question WHERE department = '$department' AND level = '$level' AND ques_no = '$i'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);

    if ($row) {
        $correctAnswers[$i] = $row['answer']; // Store the correct answer in the array
    }
}

$selectedAnswers = isset($_SESSION['answers']) ? $_SESSION['answers'] : array(); // Retrieve the selected answers from the session variable, or initialize an empty array if it's not set

// Compare the selected answers with the correct answers
foreach ($selectedAnswers as $ques_no => $selectedAnswer) {
    if (isset($correctAnswers[$ques_no]) && $selectedAnswer === $correctAnswers[$ques_no]) {
        $matchedAnswers++;
    }
}

// Display the number of matched answers
echo "Number of matched answers: " . $matchedAnswers;
echo "<script>alert('Number of matched answers: " . $matchedAnswers . "');</script>";

// ...

// Close the database connection
mysqli_close($conn);
?>

<?php include("footer.php"); ?>