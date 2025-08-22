<?php
session_start();

if (!isset($_SESSION['email']) || $_SESSION['user_type'] != 'admin') {
    header("Location: login.php");
    exit;
}

include 'connect.php';

// Define the allowed categories
$allowedCategories = ['maths', 'english', 'art', 'stories'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $category = $_POST['category'];
    $videoLink = $_POST['video_link'];

    // Validate the category
    if (!in_array($category, $allowedCategories)) {
        echo "Invalid category selected.";
        exit;
    }

    // Validate the video link
    if (!filter_var($videoLink, FILTER_VALIDATE_URL)) {
        echo "Invalid video link.";
        exit;
    }

    // Store the video details in the database
    $sql = "INSERT INTO videos (title, category, video_link) VALUES (:title, :category, :video_link)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ':title' => $title,
        ':category' => $category,
        ':video_link' => $videoLink
    ]);

    echo "Video link uploaded successfully.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Video Link</title>
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
        input[type="text"],
        input[type="url"],
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
        input[type="text"]:focus,
        input[type="url"]:focus,
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
            input[type="text"],
            input[type="url"],
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
            <h1>Upload Video Link</h1>
            
            <label for="title">Video Title:</label>
            <input type="text" name="title" id="title" placeholder="Enter video title" required>
            
            <label for="category">Select Subject:</label>
            <select name="category" id="category" required>
                <option value="maths">Maths</option>
                <option value="english">English</option>
                <option value="art">Art</option>
                <option value="stories">Stories</option>
            </select>
            
            <label for="video_link">Video Link (YouTube, Vimeo, etc.):</label>
            <input type="url" name="video_link" id="video_link" placeholder="Enter video link" required>
            
            <button type="submit">Upload Video Link</button>
        </form>
    </div>
</body>
</html>
