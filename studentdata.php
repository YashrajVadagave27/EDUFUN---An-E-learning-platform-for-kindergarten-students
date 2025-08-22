<?php
session_start();
if (!isset($_SESSION['email']) || $_SESSION['user_type'] != 'admin') {
    header("Location: login.php");
    exit;
}

include 'connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $uid = $_POST['id']; 
    $name = $_POST['name'];
    $username = $_POST['username']; // This will be the username, which is stored in the email column
    $phone = $_POST['phone'];
    $gender = $_POST['gender'];

    // Update the email column with the username
    $sql = "UPDATE user SET name = :name, email = :username, phone = :phone, gender = :gender WHERE uid = :id ";
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ':name' => $name,
        ':username' => $username, // Bind the username to the email column
        ':phone' => $phone,
        ':gender' => $gender,
        ':id' => $uid
    ]);
    echo "Student data updated successfully!";
}

$sql = "SELECT * FROM user";
$stmt = $conn->query($sql);
$students = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Data</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: rgb(250, 249, 249);
            padding: 50px 0;
        }
        .container {
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
        .container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(90deg, #ff6b6b, #4ecdc4);
        }
        caption {
            font-size: 32px;
            font-weight: 700;
            color: #2d3436;
            margin-bottom: 30px;
            text-transform: uppercase;
            letter-spacing: 1px;
            caption-side: top;
        }
        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin-top: 20px;
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
        }
        tr:hover td {
            background: #f8f9fa;
        }
        input[type="text"],
        input[type="number"],
        select {
            width: 100%;
            padding: 10px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s ease;
            background: #ffffff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        }
        input[type="text"]:focus,
        input[type="number"]:focus,
        select:focus {
            border-color: #0984e3;
            box-shadow: 0 4px 15px rgba(9, 132, 227, 0.2);
            outline: none;
        }
        button[type="submit"] {
            padding: 10px 20px;
            background: linear-gradient(45deg, #00b894, #00cec9);
            color: white;
            font-size: 14px;
            font-weight: 600;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 184, 148, 0.3);
        }
        button[type="submit"]:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 184, 148, 0.4);
        }
        @media (max-width: 768px) {
            .container {
                width: 95%;
                padding: 20px;
            }
            table {
                display: block;
                overflow-x: auto;
            }
            th, td {
                padding: 10px;
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
        <table>
            <thead>
                <caption>Student Data</caption>
                <tr>
                    <th>Uid</th>
                    <th>Name</th>
                    <th>Username</th>
                    <th>Phone</th>
                    <th>Gender</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($students as $student): ?>
                    <tr>
                        <form method="POST">
                            <td><input type="number" name="id" value="<?php echo htmlspecialchars($student['uid']); ?>"></td>
                            <td><input type="text" name="name" value="<?php echo htmlspecialchars($student['name']); ?>"></td>
                            <td><input type="text" name="username" value="<?php echo htmlspecialchars($student['email']); ?>"></td>
                            <td><input type="text" name="phone" value="<?php echo htmlspecialchars($student['phone']); ?>"></td>
                            <td>
                                <select name="gender">
                                    <option value="Male" <?php echo $student['gender'] == 'Male' ? 'selected' : ''; ?>>Male</option>
                                    <option value="Female" <?php echo $student['gender'] == 'Female' ? 'selected' : ''; ?>>Female</option>
                                    <option value="Other" <?php echo $student['gender'] == 'Other' ? 'selected' : ''; ?>>Other</option>
                                </select>
                            </td>
                            <td><button type="submit">Update</button></td>
                        </form>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
