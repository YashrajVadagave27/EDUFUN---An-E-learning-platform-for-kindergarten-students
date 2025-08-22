<?php
session_start();
include 'connect.php';

// Check if the user is logged in as a student
if (!isset($_SESSION['email']) || $_SESSION['user_type'] != 'student') {
    header("Location: login.php");
    exit;
}

// Fetch user details
$email = $_SESSION['email'];
$query = $conn->prepare("SELECT uid, name FROM user WHERE email = :email LIMIT 1");
$query->bindParam(':email', $email, PDO::PARAM_STR);
$query->execute();
$user = $query->fetch(PDO::FETCH_ASSOC);

// Questions array
$allQuestions = [
    ['question' => 'Which is a primary color?', 'options' => ['Red', 'Green', 'Orange', 'Purple'], 'answer' => 'Red'],
    ['question' => 'What shape is a circle?', 'options' => ['Round', 'Square', 'Triangle', 'Rectangle'], 'answer' => 'Round'],
    ['question' => 'What color is the sun?', 'options' => ['Yellow', 'Blue', 'Pink', 'Black'], 'answer' => 'Yellow'],
    ['question' => 'What do artists use to paint?', 'options' => ['Brush', 'Spoon', 'Fork', 'Plate'], 'answer' => 'Brush'],
    ['question' => 'Which is a drawing tool?', 'options' => ['Pencil', 'Knife', 'Spoon', 'Cup'], 'answer' => 'Pencil'],
    ['question' => 'What color is grass?', 'options' => ['Green', 'Red', 'Blue', 'Purple'], 'answer' => 'Green'],
    ['question' => 'What shape has three sides?', 'options' => ['Triangle', 'Square', 'Circle', 'Rectangle'], 'answer' => 'Triangle'],
    ['question' => 'What is used to draw a line?', 'options' => ['Ruler', 'Scissors', 'Plate', 'Eraser'], 'answer' => 'Ruler'],
    ['question' => 'Which is a warm color?', 'options' => ['Orange', 'Blue', 'Purple', 'Green'], 'answer' => 'Orange'],
    ['question' => 'What is the color of water?', 'options' => ['Blue', 'Red', 'Yellow', 'Green'], 'answer' => 'Blue'],
    ['question' => 'What shape is a square?', 'options' => ['Four-sided', 'Three-sided', 'Round', 'None'], 'answer' => 'Four-sided'],
    ['question' => 'What do you use to erase pencil marks?', 'options' => ['Eraser', 'Brush', 'Marker', 'Pen'], 'answer' => 'Eraser'],
    ['question' => 'Which is a secondary color?', 'options' => ['Purple', 'Red', 'Blue', 'Yellow'], 'answer' => 'Purple'],
    ['question' => 'What is the color of a ripe banana?', 'options' => ['Yellow', 'Green', 'Blue', 'Brown'], 'answer' => 'Yellow'],
];

// Shuffle and pick 10 random questions if not already set
if (!isset($_SESSION['quizQuestions'])) {
    shuffle($allQuestions);
    $_SESSION['quizQuestions'] = array_slice($allQuestions, 0, 10);
}
$quizQuestions = $_SESSION['quizQuestions'];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $score = 0;

    foreach ($quizQuestions as $index => $question) {
        if (isset($_POST["answer_$index"]) && $_POST["answer_$index"] == $question['answer']) {
            $score++;
        }
    }

    // Store result in the database
    $stmt = $conn->prepare("INSERT INTO artsquizresult (uid, email, score, created_at) VALUES (:uid, :email, :score, NOW())");
    $stmt->bindParam(':uid', $user['uid'], PDO::PARAM_INT);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->bindParam(':score', $score, PDO::PARAM_INT);
    $stmt->execute();

    // Display result
    echo "
    <!DOCTYPE html>
    <html lang='en'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Quiz Results</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                background: url('images/teacher-bg.png') no-repeat center center fixed;
                background-size: cover;
                margin: 0;
                padding: 0;
                display: flex;
                justify-content: center;
                align-items: center;
                min-height: 100vh;
            }
            .result-container {
                text-align: center;
                background: rgba(255, 255, 255, 0.9);
                padding: 30px;
                border-radius: 10px;
                box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            }
            .result-container h1 {
                font-size: 2rem;
                color: #333;
            }
            .result-container p {
                font-size: 1.2rem;
                color: #555;
            }
            .btn {
                display: inline-block;
                margin: 10px 5px;
                padding: 10px 20px;
                font-size: 1rem;
                text-decoration: none;
                color: white;
                background-color: #007bff;
                border-radius: 5px;
                cursor: pointer;
            }
            .btn:hover {
                background-color: #0056b3;
            }
        </style>
    </head>
    <body>
        <div class='result-container'>
            <h1>Your Final Score: $score / 10</h1>
            <p>Well done!</p>
            <a href='art_quiz.php' class='btn'>Play Again</a>
            <a href='./a_quiz.php' class='btn'>Back to Dashboard</a>
        </div>
    </body>
    </html>";
    unset($_SESSION['quizQuestions']);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Arts Quiz</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: url('images/teacher-bg.png') no-repeat center center fixed;
            background-size: cover;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
        }
        .container {
            width: 90%;
            max-width: 800px;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            margin: 20px 0;
            padding: 20px;
        }
        .question {
            font-size: 1.5rem;
            margin-bottom: 20px;
            text-align: left;
        }
        .options label {
            display: block;
            margin: 10px 0;
            font-size: 1.2rem;
            cursor: pointer;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f9f9f9;
            transition: background-color 0.3s, box-shadow 0.3s;
        }
        .options label:hover {
            background-color: #e8f0ff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .options input {
            margin-right: 10px;
        }
        .submit-btn {
            background: #28a745;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 1.2rem;
            border-radius: 5px;
            cursor: pointer;
        }
        .submit-btn:hover {
            background: #218838;
        }
    </style>
    <link rel="stylesheet" href="css/mathsquiz.css">
</head>
<body>
    <header class="header">
    <a href="#" class="logo"> <i class="fas fa-school"></i><marquee>"EDU-FUN - An E-Learning Platform For KG Students"</marquee></a>
        <nav class="navbar">
            <a href="a_quiz.php">Dashboard</a>
            <a href="logout.php">Logout</a>
        </nav>
    </header>
    <div class="container">
        <h2>Kindergarten Arts Quiz</h2>
        <form method="POST">
            <?php foreach ($quizQuestions as $index => $question): ?>
                <div class="question">
                    <p><strong>Q<?= ($index + 1) ?>:</strong> <?= htmlspecialchars($question['question']) ?></p>
                    <div class="options">
                        <?php foreach ($question['options'] as $option): ?>
                            <label>
                                <input type="radio" name="answer_<?= $index ?>" value="<?= htmlspecialchars($option) ?>" required>
                                <?= htmlspecialchars($option) ?>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
            <button type="submit" class="submit-btn">Submit Quiz</button>
        </form>
    </div>
</body>
</html>
