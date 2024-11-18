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

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $error = "";

    if (empty($email) || empty($password)) {
        $error = "Both fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } else {
        // Fetch user from the database
        $query = "SELECT * FROM users WHERE email = '$email'";
        $result = $conn->query($query);

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            // Verify password
            if (password_verify($password, $user['password'])) {
                $_SESSION['username'] = $user['username'];

                $username = $user['username'];
                $log_query = "INSERT INTO logins (username) VALUES ('$username')";
                $conn->query($log_query);

                // Redirect to a dashboard or homepage
                header("Location: welcome.php");
                exit;
            } else {
                $error = "Invalid password.";
            }
        } else {
            $error = "No account found with that email.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>WanderWorld</title>
<meta name="description" content="Your gateway to global adventures and meaningful connections">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="sytles.css">
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500&family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp" crossorigin="anonymous">
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
    .form-container input[type="text"], .form-container input[type="password"] {
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
    <li><a href="#">Home</a></li>
    <li><a href="#about">About</a></li>
    <li><a href="#team">Our Team</a></li>
    <li><a href="#destinations">Featured Destinations</a></li>
    <li><a href="#testimonials">Testimonials</a></li>
    <li><a href="#footer">Contact</a></li>
    </nav>
</header>

<div class="form-container">
    <h2>Login</h2>
    <?php if (!empty($error)): ?>
        <div style="color: red; margin-bottom: 15px;">
            <?php echo $error; ?>
        </div>
    <?php endif; ?>
    <form action="login.php" method="POST">
        <input type="text" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
    </form>
    <p>Don't have an account? <a href="signup.php">Sign up here</a></p>
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
