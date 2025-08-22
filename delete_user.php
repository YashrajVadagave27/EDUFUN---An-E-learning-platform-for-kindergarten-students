<?php
session_start();
if (!isset($_SESSION['email']) || $_SESSION['user_type'] != 'admin') {
    header("Location: login.php");
    exit;
}

include 'connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $name = $_POST['name'];
    $userType = $_POST['user_type']; // Determines whether it's a parent or user

    try {
        // Begin a transaction
        $conn->beginTransaction();

        // Delete from respective table based on user type
        if ($userType === 'parent') {
            $sql = "DELETE FROM parent WHERE Pemail = :email AND Name = :name";
        } else {
            $sql = "DELETE FROM user WHERE email = :email AND name = :name";
        }

        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':email' => $email,
            ':name' => $name,
        ]);

        // Check if a record was deleted
        if ($stmt->rowCount() > 0) {
            $conn->commit();
            echo "User deleted successfully.";
        } else {
            $conn->rollBack();
            echo "No user found with the provided details.";
        }
    } catch (Exception $e) {
        // Rollback in case of an error
        $conn->rollBack();
        echo "Error deleting user: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete User</title>
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
            margin-left: auto;
            margin-right: auto;
            background: linear-gradient(145deg, #ffffff, #f0f0f0);
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
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
            font-size: 32px;
            font-weight: 700;
            color: #2d3436;
            margin-bottom: 30px;
            text-transform: uppercase;
            letter-spacing: 1px;
            text-align: center;
        }
        label {
            display: block;
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 10px;
            color: #2d3436;
        }
        input[type="email"],
        input[type="text"],
        select {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            font-size: 16px;
            transition: all 0.3s ease;
            background: #ffffff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        }
        input[type="email"]:focus,
        input[type="text"]:focus,
        select:focus {
            border-color: #0984e3;
            box-shadow: 0 4px 15px rgba(9, 132, 227, 0.2);
            outline: none;
        }
        button[type="submit"] {
            width: 100%;
            padding: 15px;
            background: linear-gradient(45deg, #00b894, #00cec9);
            color: white;
            font-size: 18px;
            font-weight: 600;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 184, 148, 0.3);
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        button[type="submit"]:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 184, 148, 0.4);
        }
        @media (max-width: 768px) {
            .container {
                width: 90%;
                padding: 20px;
                margin-top: 100px;
            }
            h1 {
                font-size: 24px;
            }
            input[type="email"],
            input[type="text"],
            select {
                font-size: 14px;
                padding: 10px;
            }
            button[type="submit"] {
                font-size: 16px;
                padding: 12px;
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
        <form action="" method="post">
            <h1>Delete User</h1>
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" placeholder="Enter user's email" required>

            <label for="name">Name:</label>
            <input type="text" name="name" id="name" placeholder="Enter user's name" required>

            <label for="user_type">User Type:</label>
            <select name="user_type" id="user_type" required>
                <option value="parent">Parent</option>
                <option value="user">Student</option>
            </select>

            <button type="submit">Delete User</button>
        </form>
    </div>
</body>
</html>

