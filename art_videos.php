<?php
// Database connection
$host = 'localhost'; 
$dbname = 'new_edufun';  
$username = 'root';  
$password = '';      

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Fetch all art videos
$query = "SELECT * FROM videos WHERE category = 'art'";
$stmt = $pdo->query($query);
$artVideos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Art Videos</title>
    <link rel="stylesheet" href="css/dashboard.css"> 
    <link rel="stylesheet" href="/edufun/style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            color: #333;
        }
        ul {
            list-style-type: none;
            padding: 0;
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }
        li {
            flex: 0 1 calc(33.333% - 20px); /* 3 items per row with spacing */
            margin: 10px 0;
            padding: 20px;
            background: lightyellow;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
            position: relative;
        }
        li h2 {
            margin: 10px 0;
            font-size: 18px;
        }
        .thumbnail {
            position: relative;
            cursor: pointer;
            display: inline-block;
            margin: 10px auto;
        }
        .thumbnail img {
            max-width: 100%;
            border-radius: 5px;
            transition: transform 0.3s;
        }
        .thumbnail img:hover {
            transform: scale(1.05);
        }
        .play-button {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 50px;
            height: 50px;
            background: rgba(0, 0, 0, 0.7);
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
        }
        .play-button::before {
            content: '';
            display: block;
            width: 0;
            height: 0;
            border-left: 15px solid white;
            border-top: 10px solid transparent;
            border-bottom: 10px solid transparent;
        }
        iframe {
            width: 100%;
            height: 240px;
            border: none;
        }
        p {
            font-size: 14px;
            color: #555;
        }
        .mvid {
            margin: 30px auto;
        }
        h1 {
            text-align: center;
            margin: 100px auto;
            color: #f9ba60;
        }
    </style>
</head>
<body>
    <header class="header">
        <a href="#" class="logo"> <i class="fas fa-school"></i><marquee>"EDU-FUN - An E-Learning Platform For KG Students"</marquee></a>  
        <nav class="navbar">
            <a href="artdash.php">Dashboard</a>
            <a href="logout.php">Logout</a>
        </nav>
    </header>
    <div class="mvid">
        <h1>Art Videos</h1>
        <?php if (!empty($artVideos)): ?>
            <ul>
                <?php foreach ($artVideos as $video): 
                    // Extract video ID for YouTube
                    $videoLink = htmlspecialchars($video['video_link']);
                    $videoId = '';
                    if (strpos($videoLink, 'youtube.com/watch?v=') !== false) {
                        $videoId = explode('v=', $videoLink)[1];
                        $videoId = explode('&', $videoId)[0]; // Remove extra parameters
                    } elseif (strpos($videoLink, 'youtu.be/') !== false) {
                        $videoId = explode('youtu.be/', $videoLink)[1];
                    }

                    // Build YouTube embed link
                    $embedLink = "https://www.youtube.com/embed/$videoId";
                    $thumbnail = "https://img.youtube.com/vi/$videoId/0.jpg";
                ?>
                    <li>
                        <h2><?php echo htmlspecialchars($video['title']); ?></h2>
                        <div class="thumbnail" onclick="playVideo(this, '<?php echo $embedLink; ?>')">
                            <img src="<?php echo $thumbnail; ?>" alt="Video Thumbnail">
                            <div class="play-button"></div>
                        </div>
                        <p>Uploaded at: <?php echo htmlspecialchars($video['created_at']); ?></p>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p style="text-align: center; font-size: 18px; color: #888;">No art videos found.</p>
        <?php endif; ?>
    </div>
    <script>
        function playVideo(thumbnailDiv, videoUrl) {
            // Replace the thumbnail with an iframe to play the video
            const iframe = document.createElement('iframe');
            iframe.src = videoUrl + '?autoplay=1';
            iframe.allow = 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture';
            iframe.allowFullscreen = true;
            thumbnailDiv.innerHTML = ''; // Clear the thumbnail div
            thumbnailDiv.appendChild(iframe); // Add the iframe
        }
    </script>
</body>
</html>
