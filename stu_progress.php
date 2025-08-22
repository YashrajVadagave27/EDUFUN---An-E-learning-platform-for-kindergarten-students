<?php
session_start();
include 'connect.php'; // Include database connection

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

// Fetch the results for English, Math, and Arts quizzes for the student
$queryEnglish = $conn->prepare("SELECT created_at, score FROM englishquizresult WHERE email = :email ORDER BY created_at");
$queryEnglish->bindParam(':email', $email, PDO::PARAM_STR);
$queryEnglish->execute();
$englishResults = $queryEnglish->fetchAll(PDO::FETCH_ASSOC);

$queryMath = $conn->prepare("SELECT created_at, score FROM mathquizresult WHERE email = :email ORDER BY created_at");
$queryMath->bindParam(':email', $email, PDO::PARAM_STR);
$queryMath->execute();
$mathResults = $queryMath->fetchAll(PDO::FETCH_ASSOC);

$queryArts = $conn->prepare("SELECT created_at, score FROM artsquizresult WHERE email = :email ORDER BY created_at");
$queryArts->bindParam(':email', $email, PDO::PARAM_STR);
$queryArts->execute();
$artsResults = $queryArts->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Progress</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> <!-- Include Chart.js -->
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            text-align: center;
        }
        .container {
            width: 80%;
            margin: auto;
            padding: 120px;
            border-radius: 8px;
        }
        h1 {
            font-size: 2.5rem;
            color: #333;
        }
        canvas {
            width: 100%;
            height: 400px;
        }
    </style>
    <link rel="stylesheet" href="css/dashboard.css"> 
    <link rel="stylesheet" href="/edufun/style.css">
</head>
<body>
    <header class="header">
        <a href="#" class="logo"> <i class="fas fa-school"></i><marquee>"EDU-FUN - An E-Learning Platform For KG Students"</marquee></a>
        <nav class="navbar">
            <a href="student_dashboard.php">Dashboard</a>
            <a href="logout.php">Logout</a>
        </nav>
    </header>

    <div class="container">
        <h1>Student Progress - Quiz Results</h1>
        <canvas id="progressChart"></canvas> <!-- Chart for progress -->
    </div>

    <script>
        // Prepare data for Chart.js from the PHP variables
        const englishData = <?php echo json_encode($englishResults); ?>;
        const mathData = <?php echo json_encode($mathResults); ?>;
        const artsData = <?php echo json_encode($artsResults); ?>;

        const labels = []; // Date and time labels for the x-axis
        const englishScores = [];
        const mathScores = [];
        const artsScores = [];

        // Process data for Chart.js
        const processData = (data, labelArr, scoreArr) => {
            data.forEach(item => {
                const date = new Date(item.created_at);
                const formattedDateTime = date.toLocaleString('en-GB', { 
                    day: '2-digit', month: '2-digit', year: 'numeric',
                    hour: '2-digit', minute: '2-digit', second: '2-digit'
                }); // Format the date (dd/mm/yyyy, hh:mm:ss)
                if (!labelArr.includes(formattedDateTime)) {
                    labelArr.push(formattedDateTime);
                }
                scoreArr.push(item.score);
            });
        };

        processData(englishData, labels, englishScores);
        processData(mathData, labels, mathScores);
        processData(artsData, labels, artsScores);

        // Create datasets for the graph
        const data = {
            labels: labels,
            datasets: [
                {
                    label: 'English',
                    data: englishScores,
                    borderColor: 'rgb(255, 99, 132)',
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    fill: true,
                    tension: 0.4
                },
                {
                    label: 'Math',
                    data: mathScores,
                    borderColor: 'rgb(54, 162, 235)',
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    fill: true,
                    tension: 0.4
                },
                {
                    label: 'Arts',
                    data: artsScores,
                    borderColor: 'rgb(75, 192, 192)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    fill: true,
                    tension: 0.4
                }
            ]
        };

        // Configure the chart
        const config = {
            type: 'line',
            data: data,
            options: {
                responsive: true,
                scales: {
                    x: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Date and Time'
                        }
                    },
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Score'
                        }
                    }
                }
            }
        };

        // Render the chart
        const progressChart = new Chart(
            document.getElementById('progressChart'),
            config
        );
    </script>
</body>
</html>