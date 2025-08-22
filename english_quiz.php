<?php
session_start();
include 'connect.php'; // Include database connection

// Ensure only students can access
if (!isset($_SESSION['email']) || $_SESSION['user_type'] != 'student') {
    header("Location: login.php");
    exit;
}

// Fetch user details from the database
$email = $_SESSION['email'];
$query = $conn->prepare("SELECT uid FROM user WHERE email = :email LIMIT 1");
$query->bindParam(':email', $email, PDO::PARAM_STR);
$query->execute();
$user = $query->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die("User not found in the database.");
}

// Handle quiz submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['score'])) {
    $score = intval($_POST['score']);

    // Insert result into the englishquizresult table
    $stmt = $conn->prepare("INSERT INTO englishquizresult (uid, email, score, created_at) 
                            VALUES (:uid, :email, :score, NOW())");
    $stmt->bindParam(':uid', $user['uid'], PDO::PARAM_INT);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->bindParam(':score', $score, PDO::PARAM_INT);
    $stmt->execute();

    exit; // Stop further execution
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>English Quiz</title>
    <link rel="stylesheet" href="/edufun/style.css">
    <link rel="stylesheet" href="css/dashboard.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7fa;
            text-align: center;
            padding: 20px;
        }
        .container {
            margin: 100px auto;
            width: 70%;
            max-width: 600px;
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }
        .container h1 {
            font-size: 3rem;
            color: #333;
        }
        .question {
            font-size: 2rem;
            margin-bottom: 20px;
        }
        .options button {
            display: block;
            width: 90%;
            padding: 10px;
            margin: 10px 25px;
            font-size: 1.8rem;
            color: #fff;
            background-color: orange;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .options button:hover {
            background-color: #3700b3;
        }
        #playAgainButton {
            margin-top: 20px;
            padding: 10px 20px;
            font-size: 2rem;
            color: #fff;
            background-color: #007bff;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        #playAgainButton:hover {
            background-color: #0056b3;
        }
        #playAgainButton {
            display: none; /* Initially hidden */
        }
    </style>
</head>
<body>
    <header class="header">
    <a href="#" class="logo"> <i class="fas fa-school"></i><marquee>"EDU-FUN - An E-Learning Platform For KG Students"</marquee></a>
        <nav class="navbar">
            <a href="e_quiz.php">Dashboard</a>
            <a href="logout.php">Logout</a>
        </nav>
    </header>

    <div class="container">
        <h1>Kindergarten English Quiz</h1>
        <div id="quizContent">
            <div class="question" id="questionText"></div>
            <div class="options" id="optionsContainer"></div>
        </div>
        <h2 id="finalScoreText" style="display: none;"></h2> <!-- Final Score Display -->
        <a href="english_quiz.php">
            <button id="playAgainButton">Play Again</button>
        </a>
    </div>

    <script>
        const questions = [
            { question: 'Which is the odd one out?', options: ['Apple', 'Banana', 'Carrot', 'Grapes'], answer: 'Carrot' },
            { question: 'Which word is spelled correctly?', options: ['Apple', 'Aplle', 'Applle', 'Appl'], answer: 'Apple' },
            { question: 'Which word rhymes with "Cat"?', options: ['Dog', 'Hat', 'Mouse', 'Fish'], answer: 'Hat' },
            { question: 'Which is a fruit?', options: ['Table', 'Banana', 'Chair', 'Fan'], answer: 'Banana' },
            { question: 'How many letters are in the word "Tree"?', options: ['3', '4', '5', '6'], answer: '4' },
            { question: 'Which is the odd one out?', options: ['Dog', 'Cat', 'Fish', 'Car'], answer: 'Car' },
            { question: 'Which word is spelled correctly?', options: ['Eagle', 'Eagel', 'Eaglle', 'Egale'], answer: 'Eagle' },
            { question: 'Which word rhymes with "Sun"?', options: ['Run', 'Mouth', 'Cloud', 'Snow'], answer: 'Run' },
            { question: 'Which is a vegetable?', options: ['Carrot', 'Apple', 'Banana', 'Grapes'], answer: 'Carrot' },
            { question: 'How many sides does a triangle have?', options: ['3', '4', '5', '6'], answer: '3' }
        ];

        let currentQuestionIndex = 0;
        let score = 0;

        function loadQuestion() {
            if (currentQuestionIndex >= questions.length) {
                endQuiz();
                return;
            }

            const questionData = questions[currentQuestionIndex];
            document.getElementById('questionText').textContent = questionData.question;

            const optionsContainer = document.getElementById('optionsContainer');
            optionsContainer.innerHTML = '';

            questionData.options.forEach(option => {
                const button = document.createElement('button');
                button.textContent = option;
                button.onclick = () => checkAnswer(option, questionData.answer);
                optionsContainer.appendChild(button);
            });
        }

        function checkAnswer(selected, correct) {
            if (selected === correct) score++;
            currentQuestionIndex++;
            loadQuestion();
        }

        function endQuiz() {
            document.getElementById('quizContent').innerHTML = ''; // Clear quiz content
            document.getElementById('finalScoreText').textContent = `Your Final Score: ${score} / ${questions.length}`;
            document.getElementById('finalScoreText').style.display = 'block';
            document.getElementById('playAgainButton').style.display = 'block';

            // Save score to the database using AJAX
            const xhr = new XMLHttpRequest();
            xhr.open("POST", "english_quiz.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.send("score=" + score);
        }

        loadQuestion();
    </script>
</body>
</html>
