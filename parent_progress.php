<?php
session_start();
include 'connect.php'; // Include database connection

// Check if the user is logged in as parent
if (!isset($_SESSION['email']) || $_SESSION['user_type'] != 'parent') {
    header("Location: login.php");
    exit;
}

$parentEmail = $_SESSION['email'];

// Fetch parent's phone number from DB
$stmt = $conn->prepare("SELECT Phone FROM parent WHERE Pemail = :email LIMIT 1");
$stmt->bindParam(':email', $parentEmail, PDO::PARAM_STR);
$stmt->execute();
$parent = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$parent) {
    echo "<p>Parent details not found.</p>";
    exit;
}

$parentPhone = $parent['Phone'];

// Fetch all students (user) that have the same phone number
$stmt = $conn->prepare("SELECT * FROM user WHERE phone = :phone");
$stmt->bindParam(':phone', $parentPhone, PDO::PARAM_STR);
$stmt->execute();
$students = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Function to fetch quiz results for a student by email
function fetchQuizResults($email, $quizType) {
    global $conn;
    $query = $conn->prepare("SELECT created_at, score FROM {$quizType}quizresult WHERE email = :email ORDER BY created_at");
    $query->bindParam(':email', $email, PDO::PARAM_STR);
    $query->execute();
    return $query->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Parent - Student Progress</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        .container { margin: 140px;margin-left:320px; max-width: 900px; background: linear-gradient(145deg, #ffffff, #f0f0f0); #a7a7a7; padding: 30px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        h2 { text-align: center; margin-bottom: 40px; }
        canvas { margin: 40px 0; }
        .student-section { margin-bottom: 80px; border-top: 1px solid #ccc; padding-top: 20px; }
    </style>
    <link rel="stylesheet" href="css/dashboard.css"> 
    <link rel="stylesheet" href="/edufun/style.css">
</head>
<body>
<header class="header">
    <a href="#" class="logo"> <i class="fas fa-school"></i><marquee>"EDU-FUN - An E-Learning Platform For KG Students"</marquee></a>
    <nav class="navbar">
        <a href="parent_dashboard.php">Dashboard</a>
        <a href="logout.php">Logout</a>
    </nav>
</header>
<div class="container">
    <h2>Student Progress for Parent: <?= htmlspecialchars($parentEmail) ?></h2>

    <?php if (empty($students)): ?>
        <p>No student linked with your phone number.</p>
    <?php else: ?>
        <?php foreach ($students as $index => $student): 
            $email = $student['email'];
            $name = $student['name'];

            $englishData = fetchQuizResults($email, 'english');
            $mathData = fetchQuizResults($email, 'math');
            $artsData = fetchQuizResults($email, 'arts');

            if (empty($englishData) && empty($mathData) && empty($artsData)) {
                echo "<p>No Progress Data found for <strong>" . htmlspecialchars($name) . "</strong>.</p>";
                continue;
            }

            $chartId = "chart_$index";
        ?>
            <div class="student-section">
                <h3><?= htmlspecialchars($name) ?>'s Progress</h3>
                <canvas id="<?= $chartId ?>"></canvas>

                <script>
                    const ctx<?= $index ?> = document.getElementById('<?= $chartId ?>').getContext('2d');

                    const englishScores = <?= json_encode(array_column($englishData, 'score')) ?>;
                    const mathScores = <?= json_encode(array_column($mathData, 'score')) ?>;
                    const artsScores = <?= json_encode(array_column($artsData, 'score')) ?>;
                    const labels = <?= json_encode(array_column($englishData, 'created_at')) ?>;

                    new Chart(ctx<?= $index ?>, {
                        type: 'line',
                        data: {
                            labels: labels,
                            datasets: [
                                {
                                    label: 'English',
                                    data: englishScores,
                                    borderColor: 'rgb(255, 99, 132)',
                                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                                    fill: false,
                                    tension: 0.4
                                },
                                {
                                    label: 'Math',
                                    data: mathScores,
                                    borderColor: 'rgb(54, 162, 235)',
                                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                                    fill: false,
                                    tension: 0.4
                                },
                                {
                                    label: 'Arts',
                                    data: artsScores,
                                    borderColor: 'rgb(75, 192, 192)',
                                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                    fill: false,
                                    tension: 0.4
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                title: {
                                    display: true,
                                    text: 'Quiz Scores Over Time'
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    title: { display: true, text: 'Score' }
                                },
                                x: {
                                    title: { display: true, text: 'Date' }
                                }
                            }
                        }
                    });
                </script>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

</body>
</html>
