<?php
session_start();
if (!isset($_SESSION['email']) || $_SESSION['user_type'] != 'admin') {
    header("Location: login.php"); // Redirect to login if not a valid admin session
    exit;
}

include 'connect.php'; 

// Prepare and execute SQL query to fetch all feedback
$sql = "SELECT * FROM feedback ORDER BY created_at ASC"; 
$stmt = $conn->prepare($sql);
$stmt->execute();
$feedbacks = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Check if feedback data is available
if (empty($feedbacks)) {
    $message = "No feedback found.";
} else {
    $message = null;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Feedback</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/edufun/style.css">
    <link rel="stylesheet" href="css/feedback.css">
    <style>
        .dashboard-container {
            width: 90%;
            margin-top: 120px;
            margin-left: auto;
            margin-right: auto;
            background: linear-gradient(145deg, #ffffff, #f0f0f0);
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
            position: relative;
            overflow: hidden;
        }
        .dashboard-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(90deg, #ff6b6b, #4ecdc4);
        }
        h1 {
            font-size: 32px;
            font-weight: 700;
            color: #2d3436;
            margin-bottom: 30px;
            text-transform: uppercase;
            letter-spacing: 1px;
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin-top: 20px;
            background: #ffffff;
            border-radius: 10px;
            overflow: hidden;
        }
        th {
            background: linear-gradient(45deg, #00b894, #00cec9);
            color: white;
            padding: 15px;
            text-align: left;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 14px;
            letter-spacing: 0.5px;
        }
        td {
            padding: 12px 15px;
            border-bottom: 1px solid #e0e0e0;
            background: #ffffff;
            font-size: 14px;
            line-height: 1.5;
        }
        tr:hover td {
            background: #f8f9fa;
        }
        .message {
            color: #d63031;
            font-weight: 600;
            margin: 20px 0;
            padding: 15px;
            background: #ffe3e3;
            border-radius: 8px;
            text-align: center;
            border-left: 4px solid #d63031;
        }
        @media (max-width: 768px) {
            .dashboard-container {
                width: 95%;
                padding: 20px;
                margin-top: 100px;
            }
            table {
                display: block;
                overflow-x: auto;
            }
            th, td {
                padding: 10px;
                font-size: 13px;
            }
            h1 {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <header class="header">
        <a href="#" class="logo"> <i class="fas fa-school"></i> <marquee>"EDU-FUN - An E-Learning Platform For KG Students"</marquee></a>
        <nav class="navbar">
            <a href="admin_dashboard.php">Dashboard</a>
            <a href="logout.php">Logout</a>
        </nav>
    </header>

    <div class="dashboard-container">
        <h1>All Feedback</h1>

        <?php if ($message): ?>
            <div class="message"><?php echo $message; ?></div>
        <?php endif; ?>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Username</th>
                    <th>Phone Number</th>
                    <th>Subject</th>
                    <th>Role</th>
                    <th>Feedback</th>
                    <th>Submitted On</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($feedbacks as $feedback) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($feedback['id']); ?></td>
                    <td><?php echo htmlspecialchars($feedback['name']); ?></td>
                    <td><?php echo htmlspecialchars($feedback['email']); ?></td>
                    <td><?php echo htmlspecialchars($feedback['phone_number']); ?></td>
                    <td><?php echo htmlspecialchars($feedback['subject']); ?></td>
                    <td><?php echo htmlspecialchars($feedback['role']); ?></td>
                    <td><?php echo nl2br(htmlspecialchars($feedback['feedback'])); ?></td>
                    <td><?php echo htmlspecialchars($feedback['created_at']); ?></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>
