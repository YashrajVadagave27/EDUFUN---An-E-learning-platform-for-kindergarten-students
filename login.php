<?php
// Start the session
session_start();

// Disable error reporting for production environment
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);

// Redirect if the user is already logged in
if (isset($_COOKIE['email'])) {
    echo "<script>alert('Already logged in. Redirecting you to Dashboard.'); window.location.assign('dashboard.php');</script>";
    exit;
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    include 'connect.php'; // Assuming you are using PDO connection from 'connect.php'

    // Ensure that username and password are not empty
    if (!empty($_POST['username']) && !empty($_POST['pass'])) {
        $username = test_input($_POST['username']);
        $pass = test_input($_POST['pass']);

        // Check user table
        $stmtUser = $conn->prepare("SELECT * FROM user WHERE email = :email");
        $stmtUser->bindParam(':email', $username);
        $stmtUser->execute();
        $resultUser = $stmtUser->fetch(PDO::FETCH_ASSOC);

        // Check admin table
        $stmtAdmin = $conn->prepare("SELECT * FROM admin WHERE aemail = :email");
        $stmtAdmin->bindParam(':email', $username);
        $stmtAdmin->execute();
        $resultAdmin = $stmtAdmin->fetch(PDO::FETCH_ASSOC);

        // Check parent table
        $stmtParent = $conn->prepare("SELECT * FROM parent WHERE Pemail = :email");
        $stmtParent->bindParam(':email', $username);
        $stmtParent->execute();
        $resultParent = $stmtParent->fetch(PDO::FETCH_ASSOC);

        // Check password and login
        if ($resultUser && password_verify($pass, $resultUser['password'])) {
            $_SESSION['email'] = $username;
            $_SESSION['user_type'] = 'student';
            header("Location: student_dashboard.php");
            exit;
        } elseif ($resultAdmin && password_verify($pass, $resultAdmin['password'])) {
            $_SESSION['email'] = $username;
            $_SESSION['user_type'] = 'admin';
            header("Location: admin_dashboard.php");
            exit;
        } elseif ($resultParent && password_verify($pass, $resultParent['password'])) {
            $_SESSION['email'] = $username;
            $_SESSION['user_type'] = 'parent';
            header("Location: parent_dashboard.php");
            exit;
        } else {
            echo "<script>alert('Incorrect details. Please check your username and password.');</script>";
        }
    } else {
        echo "<script>alert('Fields cannot be left blank. Please fill in all details.');</script>";
    }
}

// Function to sanitize input
function test_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <link rel="stylesheet" href="css/login.css">
  <link rel="stylesheet" href="/edufun/style.css">
  <style>
    /* Dropdown styles */
    .dropdown {
        position: relative;
        display: inline-block;
    }

    .dropbtn {
        color: #fff;
        text-decoration: none;
        font-size: 18px;
        padding: 10px;
        display: inline-block;
    }

    .dropdown-content {
        display: none;
        position: absolute;
        background-color: #f9f9f9;
        box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
        z-index: 1;
        min-width: 160px;
        border-radius: 5px;
    }

    .dropdown-content a {
        color: #333;
        padding: 12px 16px;
        text-decoration: none;
        display: block;
    }

    .dropdown-content a:hover {
        background-color: #f1f1f1;
    }

    .dropdown:hover .dropdown-content {
        display: block;
    }

    .dropdown:hover .dropbtn {
        color: #ffd700;
    }
  </style>
</head>
<body>
  <header class="header">
    <a href="#" class="logo"> <i class="fas fa-school"></i><marquee>"EDU-FUN - An E-Learning Platform For KG Students"</marquee></a>
      <nav class="navbar">
          <a href="index.php">home</a>
          <!-- Dropdown for Register -->
          <div class="dropdown">
              <a href="register.php" class="dropbtn">register</a>
              <!-- <div class="dropdown-content">
                  <a href="register_student.php">Student</a>
                  <a href="register_parent.php">Parent</a>
                  <a href="register_admin.php">Admin</a>
              </div> -->
          </div>
      </nav>
  </header>

  <div class="login-form">
    <center>
      <h2> Login to your Account </h2>
      <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
        <input type="text" placeholder="Username" name="username" autocomplete="off" required />
        <input type="password" placeholder="Password" name="pass" autocomplete="off" required />
        <button type="submit">LOGIN</button>
      </form>
      <a href="register.php"><button>Not Registered? Please Register!!!</button></a>
    </center>
  </div>
</body>
</html>
