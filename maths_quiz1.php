<?php
session_start();
include 'connect.php';

// Check if the user is logged in as a student
if (!isset($_SESSION['email']) || $_SESSION['user_type'] != 'student') {
    header("Location: login.php");
    exit;
}

// Initialize quiz session variables
if (!isset($_SESSION['quiz'])) {
    $_SESSION['quiz'] = [
        'questions' => [],
        'current' => 0,
        'score' => 0
    ];
}

// Handle form submission and question generation
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['answer'])) {
    $current = $_SESSION['quiz']['current'];
    $userAnswer = $_POST['answer'] ?? null;

    // Check the user's answer
    if (is_numeric($userAnswer) && $userAnswer == $_SESSION['quiz']['questions'][$current]['answer']) {
        $_SESSION['quiz']['score']++;
    }

    // Move to the next question
    $_SESSION['quiz']['current']++;
}

// Generate a new question if necessary
if ($_SESSION['quiz']['current'] < 10) {
    $num1 = rand(1, 20);
    $num2 = rand(1, 20);
    $operations = ['-'];
    $operation = $operations[array_rand($operations)];

    // Safely calculate the correct answer
    switch ($operation) {
        case '-':
            $answer = $num1 - $num2;
            break;
        default:
            $answer = 0; // Fallback for safety
    }

    // Store the question and correct answer in the session
    $_SESSION['quiz']['questions'][$_SESSION['quiz']['current']] = [
        'num1' => $num1,
        'num2' => $num2,
        'operation' => $operation,
        'answer' => $answer
    ];
}

// Check if the quiz has ended
if ($_SESSION['quiz']['current'] >= 10) {
    $finalScore = $_SESSION['quiz']['score'];

    try {
        $email = $_SESSION['email'];
        $stmt = $conn->prepare("SELECT uid, name, phone, email FROM user WHERE email = :email");
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        $userDetails = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($userDetails) {
            $stmt = $conn->prepare("INSERT INTO mathquizresult (uid, name, email, phone, score, created_at) VALUES (:uid, :name, :email, :phone, :score, NOW())");
            $stmt->bindParam(':uid', $userDetails['uid'], PDO::PARAM_INT);
            $stmt->bindParam(':name', $userDetails['name'], PDO::PARAM_STR);
            $stmt->bindParam(':email', $userDetails['email'], PDO::PARAM_STR);
            $stmt->bindParam(':phone', $userDetails['phone'], PDO::PARAM_STR);
            $stmt->bindParam(':score', $finalScore, PDO::PARAM_INT);
            $stmt->execute();
        }

        echo "<h1>Your Score: $finalScore / 10</h1>";
        echo '<a href="maths_quiz.php" style="font: size 20px;">Retry Quiz</a>';
        unset($_SESSION['quiz']);
        exit;
    } catch (PDOException $e) {
        echo "<p>Error: Unable to save results. Please try again later.</p>";
        exit;
    }
}

// Fetch the current question
$currentQuestion = $_SESSION['quiz']['questions'][$_SESSION['quiz']['current']] ?? null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maths Quiz</title>
    <link rel="stylesheet" href="css/mathsquiz.css">
    <link rel="stylesheet" href="css/dashboard.css">
 
</head>
<body>
    <header class="header">
        <a href="#" class="logo"> <i class="fas fa-school"></i> EDU-FUN</a>
        <nav class="navbar">
            <a href="m_quiz.php">Dashboard</a>
            <a href="logout.php">Logout</a>
        </nav>
    </header>  
<div class="container">
    <h1>Kindergarten Maths Quiz</h1>
    <?php if ($currentQuestion): ?>
        <div class="question">
            <p>Question <?php echo $_SESSION['quiz']['current'] + 1; ?> of 10:</p>
            <p id="question-text">
                <?php echo htmlspecialchars($currentQuestion['num1'] . ' ' . $currentQuestion['operation'] . ' ' . $currentQuestion['num2']); ?>
            </p>
        </div>
        <form id="quiz-form">
            <input type="number" name="answer" id="answer" placeholder="Your answer" required>
            <br>
            <button type="button" onclick="submitAnswer()">Next</button>
        </form>
    <?php else: ?>
        <p>Error generating question. Please reload the page.</p>
    <?php endif; ?>
</div>


    <script>
        function submitAnswer() {
            const answer = document.getElementById('answer').value;
            const formData = new FormData();
            formData.append('answer', answer);

            fetch('maths_quiz1.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                document.querySelector('.container').innerHTML = data;
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }
    </script>
</body>
</html>
