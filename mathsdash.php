<?php
session_start();

// Check if the user is logged in as a student
if (!isset($_SESSION['email']) || $_SESSION['user_type'] != 'student') {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <link rel="stylesheet" href="css/dashboard.css"> 
    <link rel="stylesheet" href="/edufun/style.css">
    <style>
        .main {
            text-align: center;
            padding: 20rem;
        }
        .main h1 {
            font-size: 2.5rem;
            color: #333;
            margin-bottom: 1.5rem;
        }
        .sdashboard {
            display: flex;
            justify-content: center;
            gap: 5rem;
        }
        .box {
            background-color: #6200ea;
            color: white;
            padding: 1rem 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s, background-color 0.3s;
        }
        .box a {
            text-decoration: none;
            color: white;
            font-size: 1.2rem;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <header class="header">

        <a href="#" class="logo"> <i class="fas fa-school"></i><marquee>"EDU-FUN - An E-Learning Platform For KG Students"</marquee></a>

        <nav class="navbar">
            <a href="student_dashboard.php">Dashboard</a>
            <a href="logout.php">Logout</a>
        </nav>

    </header>
    <div class="main">
        <p class="p">Math's Dashboard</p><br><br>
        <div class="sdashboard">
            <div class="box">
                <a href="m_quiz.php">quiz</a>
            </div>
            <div class="box">
                <a href="m_learn.php">learn</a>
            </div>
            <div class="box">
                <a href="maths_videos.php">videos</a>
            </div>
        </div>
    </div>
</body>
</html>
