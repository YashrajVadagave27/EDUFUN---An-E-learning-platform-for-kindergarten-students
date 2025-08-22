<?php
session_start();
include 'connect.php';

// Check if parent is logged in
if (!isset($_SESSION['email']) || $_SESSION['user_type'] != 'parent') {
    header("Location: login.php");
    exit;
}

// Helper: Calculate average score for subject
function getAverageScore($conn, $email, $table) {
    $query = $conn->prepare("SELECT AVG(score) as avg_score FROM $table WHERE email = :email");
    $query->bindParam(':email', $email, PDO::PARAM_STR);
    $query->execute();
    $result = $query->fetch(PDO::FETCH_ASSOC);
    return round($result['avg_score'], 1);
}

// Helper: Get remark
function getRemark($percentage) {
    if ($percentage >= 80) return "Distinction";
    if ($percentage >= 55) return "First Class";
    if ($percentage >= 40) return "Second Class";
    return "Fail";
}

$errorMessage = "";
$students = [];
$parentPhone = "";

// Get parent phone using session Pemail
$stmt = $conn->prepare("SELECT Phone FROM parent WHERE Pemail = :email LIMIT 1");
$stmt->bindParam(':email', $_SESSION['email'], PDO::PARAM_STR);
$stmt->execute();
$parent = $stmt->fetch(PDO::FETCH_ASSOC);

if ($parent) {
    $parentPhone = $parent['Phone'];

    // Find all students with same phone number
    $stmt = $conn->prepare("SELECT uid, name, email FROM user WHERE phone = :phone");
    $stmt->bindParam(':phone', $parentPhone, PDO::PARAM_STR);
    $stmt->execute();
    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!$students) {
        $errorMessage = "No students found matching parent's phone number.";
    }
} else {
    $errorMessage = "Parent record not found.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Parent - Student Scorecard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/dashboard.css"> 
    <link rel="stylesheet" href="/edufun/style.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
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
        .container h2 {
            color: #2d3436;
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 30px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .student-card {
            margin-bottom: 40px;
            background: #ffffff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease;
        }
        .student-card:hover {
            transform: translateY(-5px);
        }
        .student-card h3 {
            color: #2d3436;
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f0f0f0;
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
        .error-msg {
            color: #d63031;
            font-weight: 600;
            font-size: 18px;
            padding: 15px;
            background: rgba(214, 48, 49, 0.1);
            border-radius: 10px;
            margin: 20px 0;
        }
        @media (max-width: 768px) {
            .container {
                width: 90%;
                margin: 120px auto 0;
                padding: 20px;
            }
            .student-card {
                padding: 20px;
            }
            .subject {
                flex-direction: column;
                gap: 10px;
                text-align: center;
            }
            .score {
                width: 100%;
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
    <h2>Student Scorecard</h2>
    <?php if ($errorMessage): ?>
        <p class="error-msg"><?= $errorMessage ?></p>
    <?php else: ?>
        <?php foreach ($students as $student): 
            $englishAvg = getAverageScore($conn, $student['email'], 'englishquizresult');
            $mathAvg = getAverageScore($conn, $student['email'], 'mathquizresult');
            $artsAvg = getAverageScore($conn, $student['email'], 'artsquizresult');
            $overallPercentage = round((($englishAvg + $mathAvg + $artsAvg) / 30) * 100, 2);
            $remark = getRemark($overallPercentage);
            $color = ($overallPercentage >= 80) ? 'green' : (($overallPercentage >= 55) ? 'yellow' : 'red');
        ?>
            <div class="student-card">
                <h3><?= htmlspecialchars($student['name']) ?>'s Scorecard</h3>
                <div class="subject">
                    <span>English</span>
                    <span class="score <?= ($englishAvg >= 8) ? 'green' : (($englishAvg >= 5.5) ? 'yellow' : 'red') ?>"><?= $englishAvg ?> / 10</span>
                </div>
                <div class="subject">
                    <span>Math</span>
                    <span class="score <?= ($mathAvg >= 8) ? 'green' : (($mathAvg >= 5.5) ? 'yellow' : 'red') ?>"><?= $mathAvg ?> / 10</span>
                </div>
                <div class="subject">
                    <span>Arts</span>
                    <span class="score <?= ($artsAvg >= 8) ? 'green' : (($artsAvg >= 5.5) ? 'yellow' : 'red') ?>"><?= $artsAvg ?> / 10</span>
                </div>
                <div class="subject" style="background: #ddd;">
                    <span><strong>Overall Percentage</strong></span>
                    <span class="score <?= $color ?>"><?= $overallPercentage ?>%</span>
                </div>
                <div class="remark <?= $color ?>">
                    <?= $remark ?>
                </div>
                <div class="button-group" style="margin-top: 20px;">
                    <a href="parent_dashboard.php" class="btn-back"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
                <button onclick="downloadScorecard(this)" class="btn-back" style="margin-top: 10px; background: #28a745;">
                    <i class="fas fa-download"></i> Download Scorecard
                </button>
            </div>
        <!-- <?php endforeach; ?>
        <a href="parent_dashboard.php" class="btn-back"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
    <?php endif; ?> -->
</div>

<script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script>
function downloadScorecard(button) {
    const studentCard = button.closest('.student-card');
    const studentName = studentCard.querySelector('h3').textContent.replace("'s Scorecard", "");
    
    html2canvas(studentCard).then(canvas => {
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
        pdf.save(`${studentName}_scorecard.pdf`);
    });
}
</script>

</body>
</html>
