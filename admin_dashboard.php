<?php
session_start();
if (!isset($_SESSION['email']) || $_SESSION['user_type'] != 'admin') {
    header("Location: login.php");
    exit;
}

include 'connect.php'; 

$email = $_SESSION['email'];

$sql = "SELECT * FROM admin WHERE aemail = :email"; 
$stmt = $conn->prepare($sql);
$stmt->bindParam(':email', $email, PDO::PARAM_STR);
$stmt->execute();
$Admin = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$Admin) {
    echo "Admin information not found.";
    exit;
}

$name = $Admin['Name'] ?? 'Not Provided';
$phone = $Admin['Phone'] ?? 'Not Provided';
$email = $Admin['aemail'] ?? 'Not Provided';
$gender = $Admin['gender'] ?? 'Not Provided';

// Handle profile update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_profile'])) {
    try {
        $newName = trim($_POST['update_name']);
        $newPhone = trim($_POST['update_phone']);
        $newUsername = trim($_POST['update_username']);
        $newGender = trim($_POST['update_gender']);

        if (empty($newName) || empty($newPhone) || empty($newUsername) || empty($newGender)) {
            throw new Exception("All fields are required.");
        }

        $updateSql = "UPDATE admin SET Name = :name, Phone = :phone, aemail = :username, gender = :gender WHERE aemail = :current_email";
        $updateStmt = $conn->prepare($updateSql);
        $updateStmt->bindParam(':name', $newName);
        $updateStmt->bindParam(':phone', $newPhone);
        $updateStmt->bindParam(':username', $newUsername);
        $updateStmt->bindParam(':gender', $newGender);
        $updateStmt->bindParam(':current_email', $email);

        if ($updateStmt->execute()) {
            $_SESSION['email'] = $newUsername;
            echo "<script>alert('Profile updated successfully!'); window.location.href='admin_dashboard.php';</script>";
            exit;
        } else {
            echo "<script>alert('Failed to update profile.');</script>";
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
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="stylesheet" href="/edufun/style.css"> 
</head>
<body>
    <header class="header">
        <a href="#" class="logo"> <i class="fas fa-school"></i><marquee>"EDU-FUN - An E-Learning Platform For KG Students"</marquee></a>
        <nav class="navbar">
            <a href="admin_dashboard.php">Dashboard</a>
            <a href="#update">Update Profile</a>
            <a href="logout.php">Logout</a>
        </nav>
    </header>

    <div class="dashboard-container">
        <h1>Welcome, <?php echo htmlspecialchars($name); ?>!</h1>
        <p>Name: <?php echo htmlspecialchars($name); ?></p>
        <p>Username: <?php echo htmlspecialchars($email); ?></p>
        <p>Phone Number: <?php echo htmlspecialchars($phone); ?></p>
        <p>Gender: <?php echo htmlspecialchars($gender); ?></p>
    </div>

    <div class="sdashboard">
        <div class="box"><a href="upload.php">Upload Subject Videos</a></div>
        <div class="box"><a href="create_user.php">Create User Account</a></div>
        <div class="box"><a href="delete_user.php">Delete User Account</a></div>
        <div class="box"><a href="admin_progress.php">View Students Progress</a></div>
    </div><br><br><br>
    <div class="sdashboard">
        <div class="box"><a href="admin_scorecard.php">View All Student Scorecard</a></div>
        <div class="box"><a href="studentdata.php">See All Student Data</a></div>
        <div class="box"><a href="parentdata.php">See All Parent Data</a></div>
        <div class="box"><a href="view_feedback.php">View All Received Feedback</a></div>
    </div>

    <!-- Update Profile Form -->
    <section class="contact" id="update">
        <div class="row">
            <div class="image">
                <img src="images/profile-update.gif" alt="">
            </div>
            <form action="" method="POST">
                <h3>Update Profile</h3>
                <div class="inputBox">
                    <h2>Update Name</h2>
                    <input type="text" name="update_name" value="<?php echo htmlspecialchars($name); ?>" placeholder="Your Name" required>
                </div>
                <div class="inputBox">
                    <h2>Update Username</h2>
                    <input type="text" name="update_username" value="<?php echo htmlspecialchars($email); ?>" placeholder="Your Username" required>
                </div>
                <div class="inputBox">
                    <h2>Update Phone Number</h2>
                    <input type="text" name="update_phone" value="<?php echo htmlspecialchars($phone); ?>" placeholder="Your Phone Number" required>
                </div>
                <div class="inputBox">
                    <h2>Select Gender</h2>
                    <select name="update_gender" required>
                        <option value="" disabled>Select Gender</option>
                        <option value="Male" <?php if ($gender == 'Male') echo 'selected'; ?>>Male</option>
                        <option value="Female" <?php if ($gender == 'Female') echo 'selected'; ?>>Female</option>
                        <option value="Other" <?php if ($gender == 'Other') echo 'selected'; ?>>Other</option>
                    </select>
                </div>
                <input type="submit" name="update_profile" value="Update Profile" class="btn">
            </form>
        </div>
    </section>
</body>
</html>
