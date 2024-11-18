<?php
session_start();

$host = 'localhost';
$dbUser = 'root';
$dbPassword = '';
$dbName = 'wanderworld';

$conn = new mysqli($host, $dbUser, $dbPassword, $dbName);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch travel stories
$stories_query = "SELECT * FROM stories ORDER BY timestamp DESC";
$stories_result = $conn->query($stories_query);

// Handle story submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Anonymous';
    $story_title = mysqli_real_escape_string($conn, $_POST['story_title']);
    $story_content = mysqli_real_escape_string($conn, $_POST['story_content']);
    $error = "";

    if (empty($story_title) || empty($story_content)) {
        $error = "Both title and content are required.";
    } else {
        $insert_story_query = "INSERT INTO stories (username, title, content) VALUES ('$username', '$story_title', '$story_content')";
        if ($conn->query($insert_story_query) === TRUE) {
            header("Location: welcome.php");
            exit;
        } else {
            $error = "Error: " . $insert_story_query . "<br>" . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>WanderWorld - Welcome</title>
<meta name="description" content="Your gateway to global adventures and meaningful connections">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="sytles.css">
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500&family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">
<style>
    .container {
        max-width: 800px;
        margin: 50px auto;
        padding: 20px;
        background-color: #fff;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
    }
    .story {
        margin-bottom: 20px;
        padding: 15px;
        background-color: #f9f9f9;
        border-radius: 8px;
    }
    .story h3 {
        margin: 0 0 10px;
    }
    .story p {
        margin: 0;
    }
    .form-container textarea {
        width: 95%;
        padding: 10px;
        margin-bottom: 15px;
        border: 1px solid #ddd;
        border-radius: 4px;
    }
    .form-container h2 {
        text-align: center;
        margin-bottom: 20px;
    }
</style>
</head>
<body>
<header id="header">
    <h2><a href="index.html">WanderWorld</a></h2>
    <nav>
    <li><a href="#">Home</a></li>
    <li><a href="#about">About</a></li>
    <li><a href="#team">Our Team</a></li>
    <li><a href="#destinations">Featured Destinations</a></li>
    <li><a href="#testimonials">Testimonials</a></li>
    <li><a href="#footer">Contact</a></li>
    </nav>
</header>

<div class="container">
    <h1>Welcome, <?php echo isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest'; ?>!</h1>
    <h2>Travel Stories</h2>

    <?php if ($stories_result->num_rows > 0): ?>
        <?php while ($story = $stories_result->fetch_assoc()): ?>
            <div class="story">
                <h3><?php echo htmlspecialchars($story['title']); ?></h3>
                <p><strong>By:</strong> <?php echo htmlspecialchars($story['username']); ?> | <strong>On:</strong> <?php echo $story['timestamp']; ?></p>
                <p><?php echo nl2br(htmlspecialchars($story['content'])); ?></p>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No stories to display. Be the first to share your travel adventure!</p>
    <?php endif; ?>

    <div class="form-container">
        <h2>Share Your Story</h2>
        <?php if (!empty($error)): ?>
            <div style="color: red; margin-bottom: 15px;">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>
        <form action="welcome.php" method="POST">
            <input type="text" name="story_title" placeholder="Story Title" required>
            <textarea name="story_content" placeholder="Your Story" rows="5" required></textarea>
            <button type="submit">Submit Story</button>
        </form>
    </div>
</div>

<footer>
    <p>&copy; 2024 WanderWorld. All rights reserved.</p>
</footer>
</body>
</html>

<?php
$conn->close();
?>
