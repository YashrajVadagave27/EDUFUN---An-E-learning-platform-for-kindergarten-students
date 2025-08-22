<?php
session_start();
include 'connect.php'; // Include database connection

// Check if the user is logged in as admin
if (!isset($_SESSION['email']) || $_SESSION['user_type'] != 'admin') {
    header("Location: login.php");
    exit;
}

// Fetch admin details
$email = $_SESSION['email'];
$query = $conn->prepare("SELECT Aid, Name FROM admin WHERE aemail = :email LIMIT 1");
$query->bindParam(':email', $email, PDO::PARAM_STR);
$query->execute();
$admin = $query->fetch(PDO::FETCH_ASSOC);

// Function to fetch quiz results for a student by username stored in the email column
function fetchQuizResults($username, $quizType) {
    global $conn;
    $query = $conn->prepare("SELECT created_at, score FROM {$quizType}quizresult WHERE email = :username ORDER BY created_at");
    $query->bindParam(':username', $username, PDO::PARAM_STR);
    $query->execute();
    return $query->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Student Progress</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: rgb(250, 249, 249);
            padding: 50px 0;
        }
        .container {
            width: 50%;
            margin-top: 120px;
            margin-left: 350px;
            background: linear-gradient(145deg, #ffffff, #f0f0f0);
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        .container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(90deg, #ff6b6b, #4ecdc4);
        }
        h1 {
            color: #2d3436;
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 30px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        canvas {
            width: 100%;
            height: 400px;
            margin-top: 20px;
        }
        .search-form {
            width: 100%;
            max-width: 400px;
            margin: 20px auto;
        }
        .form-input {
            width: 100%;
            padding: 15px;
            margin-bottom: 20px;
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            font-size: 16px;
            transition: all 0.3s ease;
            background: #ffffff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }
        .form-input:focus {
            border-color: #0984e3;
            box-shadow: 0 4px 15px rgba(9, 132, 227, 0.2);
            outline: none;
        }
        .form-button {
            width: 100%;
            padding: 15px;
            background: linear-gradient(45deg, #00b894, #00cec9);
            color: white;
            font-size: 16px;
            font-weight: 600;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 184, 148, 0.3);
        }
        .form-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 184, 148, 0.4);
        }
        @media (max-width: 768px) {
            .container {
                width: 90%;
                margin: 120px auto 0;
                padding: 20px;
            }
        }
    </style>
    <link rel="stylesheet" href="css/dashboard.css"> 
    <link rel="stylesheet" href="/edufun/style.css">
</head>
<body>
<header class="header">
    <a href="#" class="logo"> <i class="fas fa-school"></i><marquee>"EDU-FUN - An E-Learning Platform For KG Students"</marquee></a>
    <nav class="navbar">
        <a href="admin_dashboard.php">Dashboard</a>
        <a href="logout.php">Logout</a>
    </nav>
</header>
<div class="container">
    <h1>Student's Progress Report</h1>

    <form method="GET" action="" class="search-form">
        <input type="text" name="student_username" placeholder="Enter student username" required class="form-input">
        <button type="submit" class="form-button">Search</button>
    </form>

    <?php
    if (isset($_GET['student_username'])) {
        $student_username = $_GET['student_username'];

        // Fetch student results using username stored in email column
        $englishResults = fetchQuizResults($student_username, 'english');
        $mathResults = fetchQuizResults($student_username, 'math');
        $artsResults = fetchQuizResults($student_username, 'arts');

        if (empty($englishResults) && empty($mathResults) && empty($artsResults)) {
            echo "<p>No results found for the student with username: $student_username</p>";
        } else {
            ?>

            <h2>Progress of <?php echo htmlspecialchars($student_username); ?></h2>
            <canvas id="progressChart"></canvas>

            <script>
                const englishData = <?php echo json_encode($englishResults); ?>;
                const mathData = <?php echo json_encode($mathResults); ?>;
                const artsData = <?php echo json_encode($artsResults); ?>;

                const labels = [];
                const englishScores = [];
                const mathScores = [];
                const artsScores = [];

                const processData = (data, labelArr, scoreArr) => {
                    data.forEach(item => {
                        const date = new Date(item.created_at);
                        const formattedDateTime = date.toLocaleString('en-GB', {
                            day: '2-digit', month: '2-digit', year: 'numeric',
                            hour: '2-digit', minute: '2-digit', second: '2-digit'
                        });
                        labelArr.push(formattedDateTime);
                        scoreArr.push(item.score);
                    });
                };

                processData(englishData, labels, englishScores);
                processData(mathData, labels, mathScores);
                processData(artsData, labels, artsScores);

                const uniqueLabels = [...new Set(labels)].sort();

                const data = {
                    labels: uniqueLabels,
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

                const config = {
                    type: 'line',
                    data: data,
                    options: {
                        responsive: true,
                        scales: {
                            x: {
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

                const progressChart = new Chart(
                    document.getElementById('progressChart'),
                    config
                );
            </script>

            <?php
        }
    }
    ?>
</div>
</body>
</html>
