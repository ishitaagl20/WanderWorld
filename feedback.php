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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $feedback = mysqli_real_escape_string($conn, $_POST['feedback']);
    $error = "";

    if (empty($name) || empty($email) || empty($feedback)) {
        $error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } else {
        $insert_query = "INSERT INTO feedback (name, email, feedback) VALUES ('$name', '$email', '$feedback')";
        if ($conn->query($insert_query) === TRUE) {
            $success = "Thank you for your feedback!";
        } else {
            $error = "Error: " . $insert_query . "<br>" . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>WanderWorld - Feedback</title>
<meta name="description" content="Your gateway to global adventures and meaningful connections">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="sytles.css">
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500&family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">
<style>
    .form-container {
        max-width: 400px;
        margin: 50px auto;
        padding: 20px;
        background-color: #fff;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
    }
    .form-container h2 {
        text-align: center;
        margin-bottom: 20px;
    }
    .form-container input[type="text"], .form-container textarea {
        width: 95%;
        padding: 10px;
        margin-bottom: 15px;
        border: 1px solid #ddd;
        border-radius: 4px;
    }
    .form-container button {
        width: 100%;
        padding: 10px;
        background-color: #3f51b5;
        color: #fff;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }
    .form-container button:hover {
        background-color: #303f9f;
    }
</style>
</head>
<body>
<header id="header">
    <h2><a href="index.html">WanderWorld</a></h2>
    <nav>
    <li><a href="index.html">Home</a></li>
    <li><a href="#about">About</a></li>
    <li><a href="#team">Our Team</a></li>
    <li><a href="#destinations">Featured Destinations</a></li>
    <li><a href="#testimonials">Testimonials</a></li>
    <li><a href="#footer">Contact</a></li>
    </nav>
</header>

<div class="form-container">
    <h2>Feedback</h2>
    <?php if (!empty($error)): ?>
        <div style="color: red; margin-bottom: 15px;">
            <?php echo $error; ?>
        </div>
    <?php elseif (!empty($success)): ?>
        <div style="color: green; margin-bottom: 15px;">
            <?php echo $success; ?>
        </div>
    <?php endif; ?>
    <form action="feedback.php" method="POST">
        <input type="text" name="name" placeholder="Your Name" required>
        <input type="text" name="email" placeholder="Your Email" required>
        <textarea name="feedback" placeholder="Your Feedback" rows="5" required></textarea>
        <button type="submit">Submit Feedback</button>
    </form>
</div>

<footer id="footer">
    <div class="footer-content">
      <a href="#" class="footer-logo">WanderWorld</a>
      <div class="socials">
        <a href="#"><i class="fab fa-facebook-f"></i></a>
        <a href="#"><i class="fab fa-twitter"></i></a>
        <a href="#"><i class="fab fa-instagram"></i></a>
        <a href="#"><i class="fab fa-youtube"></i></a>
      </div>
    </div>
    <div class="footer-bottom">
      <p>&copy; 2024 WanderWorld. Made by Ishita Agarwal - 21BCE5317</p>
      <br>
      <a href="#" class="btn">Back to Home</a>
    </div>
  </footer>

</body>
</html>

<?php
$conn->close();
?>
