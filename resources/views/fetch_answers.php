<?php
// Establish a database connection
$host = 'your_host';
$dbName = 'your_database_name';
$username = 'your_username';
$password = 'your_password';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbName;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Database connection failed: ' . $e->getMessage());
}

// Fetch the correct answers from the "question" table
try {
    $query = "SELECT questionNumber, correctAnswer FROM question";
    $statement = $pdo->prepare($query);
    $statement->execute();

    $answers = $statement->fetchAll(PDO::FETCH_ASSOC);

    // Return the answers as JSON
    header('Content-Type: application/json');
    echo json_encode($answers);
} catch (PDOException $e) {
    die('Error fetching answers: ' . $e->getMessage());
}
?>