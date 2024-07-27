<?php
session_start();

$con = mysqli_connect("localhost", "root", "", "exam");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle form submission
    if (isset($_POST['username'])) {
        $_SESSION['username'] = $_POST['username'];
    }
}

if (isset($_POST['check'])) {
    $username = $_SESSION['username'];

    // Fetch the relevant data from the database
    $query = "SELECT first_name, father_name, department, level FROM approvecandidate WHERE username = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($first_name, $father_name, $department, $level);
    $stmt->fetch();
    $stmt->close();
}
?>

<head>
    <link rel="stylesheet" type="text/css" href="styles.css" />
    <link rel="stylesheet" type="text/css" href="registeration.css" />
</head>

<body>
    <div id="header">
        <img src="images/sam1.png">
    </div>
    <div id="navigation">
        <ul>
            <li><a href="#">Home</a></li>
            <li><a href="logout.php">Log Out</a></li>
        </ul>
    </div>
    <div id="site_content">
        <div id="current_date">
            <script>
                date = new Date();
                year = date.getFullYear();
                month = date.getMonth() + 1;
                day = date.getDate();
                document.getElementById("current_date").innerHTML = "date:" + day + "/" + month + "/" + year;
            </script>
        </div>

        <div class="register_item">
            <form method="POST">
                Username:<input type="text" name="username" value="<?php echo isset($_SESSION['username']) ? $_SESSION['username'] : ''; ?>">
                <input type="submit" name="check" value="Check">
            </form>
            <?php if (isset($_POST['check']) && isset($first_name)) { ?>
                <p>First Name: <?php echo $first_name; ?></p>
                <p>Father Name: <?php echo $father_name; ?></p>
                <p>Department: <?php echo $department; ?></p>
                <p>Level: <?php echo $level; ?></p>
            <?php } elseif (isset($_POST['check'])) { ?>
                <p>No candidate found with the entered username.</p>
            <?php } ?>
            <div id="container">
                <fieldset>
                    <legend>Practical Exam Evaluation Form</legend>
                    <?php include("eesForm.php");?>
                </fieldset>
            </div>
        </div>
    </div>
</body>
<?php
include("footer.php");
?>