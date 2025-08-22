<?php
session_start();

// Check if the user is logged in as a student
if (!isset($_SESSION['email']) || $_SESSION['user_type'] != 'student') {
    header("Location: login.php");
    exit;
}

include 'connect.php';

$email = $_SESSION['email'];
$sql = "SELECT * FROM user WHERE email = :email";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':email', $email, PDO::PARAM_STR);
$stmt->execute();
$student = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$student) {
    echo "Student information not found.";
    exit;
}

// Handle feedback form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_feedback'])) {
    try {
        $name = trim($_POST['name']);
        $email = trim($_POST['email']);
        $phone_number = trim($_POST['number']);
        $subject = trim($_POST['subject']);
        $role = trim($_POST['role']);
        $feedback = trim($_POST['feedback']);

        if (empty($name) || empty($email) || empty($phone_number) || empty($subject) || empty($role) || empty($feedback)) {
            throw new Exception("All fields are required.");
        }

        $sql = "INSERT INTO feedback (name, email, phone_number, subject, role, feedback, created_at) 
                VALUES (:name, :email, :phone_number, :subject, :role, :feedback, NOW())";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':phone_number', $phone_number);
        $stmt->bindParam(':subject', $subject);
        $stmt->bindParam(':role', $role);
        $stmt->bindParam(':feedback', $feedback);

        if ($stmt->execute()) {
            echo "<script>alert('Thank you for your feedback!');</script>";
        } else {
            echo "<script>alert('Failed to submit your feedback. Please try again.');</script>";
        }
    } catch (Exception $e) {
        echo "<script>alert('Error: " . $e->getMessage() . "');</script>";
    }
}

// Handle profile update
if (isset($_POST['update_profile'])) {
    try {
        $newName = trim($_POST['update_name']);
        $newPhone = trim($_POST['update_phone']);
        $newEmail = trim($_POST['update_email']); // This is the "username"
        $newGender = trim($_POST['update_gender']);

        if (empty($newName) || empty($newPhone) || empty($newEmail) || empty($newGender)) {
            throw new Exception("All profile fields are required.");
        }

        // Update user data
        $updateSql = "UPDATE user SET 
                        name = :name, 
                        phone = :phone, 
                        email = :newEmail, 
                        gender = :gender 
                      WHERE email = :originalEmail";
        $updateStmt = $conn->prepare($updateSql);
        $updateStmt->bindParam(':name', $newName);
        $updateStmt->bindParam(':phone', $newPhone);
        $updateStmt->bindParam(':newEmail', $newEmail);
        $updateStmt->bindParam(':gender', $newGender);
        $updateStmt->bindParam(':originalEmail', $email);

        if ($updateStmt->execute()) {
            $_SESSION['email'] = $newEmail; // update session for login
            echo "<script>alert('Profile updated successfully!'); window.location.href='student_dashboard.php';</script>";
            exit;
        } else {
            echo "<script>alert('Failed to update profile. Please try again.');</script>";
        }
    } catch (Exception $e) {
        echo "<script>alert('Error: " . $e->getMessage() . "');</script>";
    }
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
</head>
<body>
<header class="header">
    <a href="#" class="logo"> <i class="fas fa-school"></i><marquee>"EDU-FUN - An E-Learning Platform For KG Students"</marquee></a>
    <nav class="navbar">
        <a href="student_dashboard.php">Dashboard</a>
        <a href="stu_progress.php">View Progress</a>
        <a href="stud_scorecard.php">View Scorecard</a>
        <!-- <a href="#update">Update Profile</a> -->
        <a href="logout.php">Logout</a>
    </nav>
</header>

<div class="main">
    <div class="dashboard-container">
        <h1>Welcome, <?php echo htmlspecialchars($student['name']); ?>!!!</h1>
        <p>Name: <?php echo htmlspecialchars($student['name']); ?></p>
        <p>Username: <?php echo htmlspecialchars($student['email']); ?></p>
        <p>Phone Number: <?php echo htmlspecialchars($student['phone']); ?></p>
        <p>Gender: <?php echo htmlspecialchars($student['gender']); ?></p>
    </div>

    <p class="p">Select the subject</p><br><br>
    <div class="sdashboard">
        <div class="box"><a href="mathsdash.php">Maths</a></div>
        <div class="box"><a href="englishdash.php">English</a></div>
        <div class="box"><a href="artdash.php">Art</a></div>
        <!-- <div class="box"><a href="elearn/NextGame.html">Games</a></div> -->
        <div class="box"><a href="NextGame.html">Games</a></div>
    </div>

    <!-- Feedback Section -->
    <section class="contact" id="contact">
        <div class="row">
            <div class="image">
                <img src="images/contact.gif" alt="">
            </div>
            <form action="" method="POST">
                <h3>Feedback</h3>
                <div class="inputBox">
                    <input type="text" name="name" value="<?php echo htmlspecialchars($student['name']); ?>" placeholder="Your Name" required>
                    <input type="email" name="email" value="<?php echo htmlspecialchars($student['email']); ?>" placeholder="Your Email" required>
                </div>
                <div class="inputBox">
                    <input type="number" name="number" placeholder="Your Phone Number" required>
                    <input type="text" name="subject" placeholder="Your Subject" required>
                </div>
                <div class="inputBox">
                    <select name="role" required>
                        <option value="" disabled selected>Select your role</option>
                        <option value="Parent">Parent</option>
                        <option value="Student">Student</option>
                    </select>
                </div>
                <textarea name="feedback" placeholder="Give Your Feedback" required></textarea>
                <input type="submit" name="submit_feedback" value="Send Feedback" class="btn">
            </form>
        </div>
    </section>

    <!-- Update Profile Section -->
    <section class="contact" id="update">
        <div class="row">
            <div class="image">
                <img src="images/profile-update.gif" alt="">
            </div>
            <form action="" method="POST">
                <h3>Update Profile</h3>
                <div class="inputBox">
                    <h2>Update Name</h2>
                    <input type="text" name="update_name" value="<?php echo htmlspecialchars($student['name']); ?>" placeholder="Your Name" required>
                </div>
                <div class="inputBox">
                    <h2>Update Phone Number</h2>
                    <input type="text" name="update_phone" value="<?php echo htmlspecialchars($student['phone']); ?>" placeholder="Your Phone Number" required>
                </div>
                <div class="inputBox">
                    <h2>Update Username</h2>
                    <input type="text" name="update_email" value="<?php echo htmlspecialchars($student['email']); ?>" placeholder="Your Username" required>
                </div>
                <div class="inputBox">
                    <h2>Select Gender</h2>
                    <select name="update_gender" required>
                        <option value="" disabled>Select Gender</option>
                        <option value="Male" <?php if($student['gender'] == 'Male') echo 'selected'; ?>>Male</option>
                        <option value="Female" <?php if($student['gender'] == 'Female') echo 'selected'; ?>>Female</option>
                        <option value="Other" <?php if($student['gender'] == 'Other') echo 'selected'; ?>>Other</option>
                    </select>
                </div>
                <input type="submit" name="update_profile" value="Update Profile" class="btn">
            </form>
        </div>
    </section>
</div>
</body>
</html>
