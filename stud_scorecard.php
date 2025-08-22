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
$query = $conn->prepare("SELECT uid, name, phone FROM user WHERE email = :email LIMIT 1");
$query->bindParam(':email', $email, PDO::PARAM_STR);
$query->execute();
$user = $query->fetch(PDO::FETCH_ASSOC);

// Fetch average scores for English, Math, and Arts quizzes
function getAverageScore($conn, $email, $table) {
    $query = $conn->prepare("SELECT AVG(score) as avg_score FROM $table WHERE email = :email");
    $query->bindParam(':email', $email, PDO::PARAM_STR);
    $query->execute();
    $result = $query->fetch(PDO::FETCH_ASSOC);
    return round($result['avg_score'], 1); // Keep score as it is, out of 10
}

$englishAvg = getAverageScore($conn, $email, 'englishquizresult');
$mathAvg = getAverageScore($conn, $email, 'mathquizresult');
$artsAvg = getAverageScore($conn, $email, 'artsquizresult');

// Calculate overall percentage using average marks out of 100
$overallPercentage = round((($englishAvg + $mathAvg + $artsAvg) / 30) * 100, 2);

// Get remarks based on overall percentage
function getRemark($percentage) {
    if ($percentage >= 80) {
        return "Distinction";
    } elseif ($percentage >= 55) {
        return "First Class";
    } elseif ($percentage >= 40) {
        return "Second Class";
    } else {
        return "Fail";
    }
}

$overallRemark = getRemark($overallPercentage);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Scorecard</title>
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"> -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background:rgb(250, 249, 249);
            padding: 50px 0;
        }
        .scorecard {
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
        .scorecard::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(90deg, #ff6b6b, #4ecdc4);
        }
        .scorecard h2 {
            color: #2d3436;
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 30px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .scorecard .info {
            margin: 30px 0;
            padding: 20px;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }
        .scorecard .info p {
            font-size: 18px;
            margin: 12px 0;
            color: #2d3436;
            display: flex;
            justify-content: space-between;
            padding: 0 20px;
        }
        .scorecard .info p strong {
            color: #636e72;
        }
        .subject {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #ffffff;
            padding: 20px;
            margin: 15px 0;
            border-radius: 12px;
            font-size: 18px;
            font-weight: 600;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease;
        }
        .subject:hover {
            transform: translateY(-3px);
        }
        .score {
            color: #fff;
            padding: 8px 20px;
            border-radius: 25px;
            font-weight: 600;
            min-width: 100px;
            text-align: center;
        }
        .score.green { 
            background: linear-gradient(45deg, #00b894, #00cec9);
            box-shadow: 0 4px 15px rgba(0, 184, 148, 0.3);
        }
        .score.yellow { 
            background: linear-gradient(45deg, #fdcb6e, #e17055);
            box-shadow: 0 4px 15px rgba(253, 203, 110, 0.3);
        }
        .score.red { 
            background: linear-gradient(45deg, #d63031, #e84393);
            box-shadow: 0 4px 15px rgba(214, 48, 49, 0.3);
        }
        .remark {
            font-size: 24px;
            font-weight: 700;
            margin-top: 25px;
            color: #fff;
            padding: 15px 30px;
            border-radius: 30px;
            display: inline-block;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .remark.green { 
            background: linear-gradient(45deg, #00b894, #00cec9);
            box-shadow: 0 4px 15px rgba(0, 184, 148, 0.3);
        }
        .remark.yellow { 
            background: linear-gradient(45deg, #fdcb6e, #e17055);
            box-shadow: 0 4px 15px rgba(253, 203, 110, 0.3);
        }
        .remark.red { 
            background: linear-gradient(45deg, #d63031, #e84393);
            box-shadow: 0 4px 15px rgba(214, 48, 49, 0.3);
        }
        .button-group {
            margin-top: 30px;
            display: flex;
            justify-content: center;
            gap: 15px;
        }
        .btn-back {
            margin-top: 20px;
            display: inline-block;
            padding: 12px 25px;
            background: linear-gradient(45deg, #0984e3, #74b9ff);
            color: #fff;
            text-decoration: none;
            border-radius: 25px;
            font-size: 16px;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(9, 132, 227, 0.3);
        }
        .btn-back:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(9, 132, 227, 0.4);
            background: linear-gradient(45deg, #74b9ff, #0984e3);
        }
        .btn-back i {
            margin-right: 8px;
        }
        @media (max-width: 768px) {
            .scorecard {
                width: 90%;
                margin: 120px auto 0;
                padding: 20px;
            }
            .button-group {
                flex-direction: column;
                align-items: center;
            }
            .btn-back {
                width: 100%;
                margin: 10px 0;
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
            <a href="student_dashboard.php">Dashboard</a>
            <a href="logout.php">Logout</a>
        </nav>
    </header>
<div class="scorecard">
    <h2>Student Scorecard</h2>
    <hr></hr>
    <div class="info">
        <p><strong>Name:</strong> <?= htmlspecialchars($user['name']); ?></p>
        <p><strong>Phone:</strong> <?= htmlspecialchars($user['phone']); ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($email); ?></p>
    </div>

    <div class="subject">
        <span>English</span>
        <span class="score <?= ($englishAvg >= 8) ? 'green' : (($englishAvg >= 5.5) ? 'yellow' : 'red') ?>">
            <?= $englishAvg ?> / 10
        </span>
    </div>

    <div class="subject">
        <span>Math</span>
        <span class="score <?= ($mathAvg >= 8) ? 'green' : (($mathAvg >= 5.5) ? 'yellow' : 'red') ?>">
            <?= $mathAvg ?> / 10
        </span>
    </div>

    <div class="subject">
        <span>Arts</span>
        <span class="score <?= ($artsAvg >= 8) ? 'green' : (($artsAvg >= 5.5) ? 'yellow' : 'red') ?>">
            <?= $artsAvg ?> / 10
        </span>
    </div>

    <div class="subject" style="background: #ddd;">
        <span><strong>Overall Percentage</strong></span>
        <span class="score <?= ($overallPercentage >= 80) ? 'green' : (($overallPercentage >= 55) ? 'yellow' : 'red') ?>">
            <?= $overallPercentage ?>%
        </span>
    </div>

    <div class="remark <?= ($overallPercentage >= 80) ? 'green' : (($overallPercentage >= 55) ? 'yellow' : 'red') ?>">
        <?= $overallRemark ?>
    </div>

    <div class="button-group" style="margin-top: 20px;">
        <a href="student_dashboard.php" class="btn-back"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
        <button onclick="downloadScorecard()" class="btn-back" style="margin-left: 10px; background: #28a745;">
            <i class="fas fa-download"></i> Download Scorecard
        </button>
    </div>
</div>

<script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script>
function downloadScorecard() {
    const scorecard = document.querySelector('.scorecard');
    
    html2canvas(scorecard).then(canvas => {
        const imgData = canvas.toDataURL('image/png');
        const pdf = new jspdf.jsPDF('p', 'mm', 'a4');
        const pdfWidth = pdf.internal.pageSize.getWidth();
        const pdfHeight = pdf.internal.pageSize.getHeight();
        const imgWidth = canvas.width;
        const imgHeight = canvas.height;
        const ratio = Math.min(pdfWidth / imgWidth, pdfHeight / imgHeight);
        const imgX = (pdfWidth - imgWidth * ratio) / 2;
        const imgY = 30;
        
        pdf.addImage(imgData, 'PNG', imgX, imgY, imgWidth * ratio, imgHeight * ratio);
        pdf.save('student_scorecard.pdf');
    });
}
</script>

</body>
</html>