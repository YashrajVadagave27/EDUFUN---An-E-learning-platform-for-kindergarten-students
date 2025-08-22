<?php
session_start();

// Database connection
$host = 'localhost';
$db = 'new_edufun';
$user = 'root';
$pass = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!empty($_POST['name']) && !empty($_POST['num']) && !empty($_POST['select']) && !empty($_POST['username']) && !empty($_POST['pass']) && !empty($_POST['user_type'])) {
        $name = trim($_POST['name']);
        $num = trim($_POST['num']);
        $sel = trim($_POST['select']);
        $username = trim($_POST['username']);
        $pass = trim($_POST['pass']);
        $user_type = trim($_POST['user_type']);

        $msg = "";
        $all_ok = true;

        // Common validations
        if (!preg_match('/^[0-9]{10}$/', $num)) {
            $all_ok = false;
            $msg = "Invalid mobile number.";
        } elseif (empty($sel)) {
            $all_ok = false;
            $msg = "Please select a gender.";
        }

        // Additional validation for admin and student usernames
        if ($user_type != 'parent' && !preg_match('/^[a-zA-Z0-9_]{3,20}$/', $username)) {
            $all_ok = false;
            $msg = "Please enter a valid username.";
        }

        if ($all_ok) {
            try {
                $hashed_pass = password_hash($pass, PASSWORD_DEFAULT);
                
                // Prepare the appropriate SQL statement based on user type
                switch($user_type) {
                    case 'admin':
                        $stmt = $conn->prepare("INSERT INTO admin (Name, Phone, aemail, password, gender, status) VALUES (?, ?, ?, ?, ?, 0)");
                        break;
                    case 'student':
                        $stmt = $conn->prepare("INSERT INTO user (name, phone, email, password, gender, status) VALUES (?, ?, ?, ?, ?, 0)");
                        break;
                    case 'parent':
                        $stmt = $conn->prepare("INSERT INTO parent (Name, Phone, Pemail, password, gender, status) VALUES (?, ?, ?, ?, ?, 0)");
                        break;
                }
                
                $stmt->execute([$name, $num, $username, $hashed_pass, $sel]);

                echo '<script>
                        alert("Registration successful.");
                        window.location.assign("login.php");
                      </script>';
            } catch (PDOException $e) {
                echo '<script>
                        alert("Username already exists. Please choose a different username.");
                      </script>';
            }
        } else {
            echo "<script>alert('$msg');</script>";
        }
    } else {
        echo '<script>alert("All fields are required.");</script>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Registration - EDU-FUN</title>
    <link rel="stylesheet" href="/edufun/style.css">
    <link rel="stylesheet" href="css/login.css">
    <link rel="stylesheet" href="css/dropdown.css">
    <style>
        .login-form {
            margin-top: 150px;
        }
        .gender-row {
            display: flex;
            justify-content: center;
            gap: 60px;
            margin: 10px 0;
        }
        .gender-row label {
            display: flex;
            align-items: center;
            font-size: 15px;
        }
        .gender-row input[type="radio"] {
            margin-right: 8px;
        }
        .user-type-select {
            margin-bottom: 15px;
            padding: 10px;
            width: 100%;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <header class="header">
        <a href="#" class="logo"> <i class="fas fa-school"></i><marquee>"EDU-FUN - An E-Learning Platform For KG Students"</marquee></a>
        <nav class="navbar">
            <a href="index.php">Home</a>
            <div class="dropdown">
                <a href="#" class="dropbtn">Register</a>
                <!-- <div class="dropdown-content">
                    <a href="register.php?type=student">Student</a>
                    <a href="register.php?type=parent">Parent</a>
                    <a href="register.php?type=admin">Admin</a>
                </div> -->
            </div>
        </nav>
    </header>

    <div class="login-form">
        <center>
            <h2>Registration</h2>
            <form method="post">
                <select name="user_type" class="user-type-select" required>
                    <option value="">Select User Type</option>
                    <option value="student">Student</option>
                    <option value="parent">Parent</option>
                    <option value="admin">Admin</option>
                </select>
                <input type="text" name="name" placeholder="Name" required />
                <input type="text" name="num" placeholder="10-digit Mobile" pattern="\d{10}" required />
                <div class="gender-row">
                    <label><strong>Select Gender:</strong></label>
                    <label><input type="radio" name="select" value="MALE" required> Male</label>
                    <label><input type="radio" name="select" value="FEMALE"> Female</label>
                </div>
                <input type="text" name="username" placeholder="Username" required />
                <input type="password" name="pass" placeholder="Password" required />
                <button type="submit">Register</button>
            </form>
            <a href="login.php"><button>Already registered? Login</button></a>
        </center>
    </div>

    <script>
        // Set the user type based on URL parameter
        const urlParams = new URLSearchParams(window.location.search);
        const type = urlParams.get('type');
        if (type) {
            document.querySelector('select[name="user_type"]').value = type;
        }
    </script>
</body>
</html> 