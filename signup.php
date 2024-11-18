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

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);
    $error = "";
    // Basic validation
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        // Check if email already exists
        $email_check_query = "SELECT * FROM users WHERE email = '$email' LIMIT 1";
        $result = $conn->query($email_check_query);
        if ($result->num_rows > 0) {
            $error = "Email is already registered.";
        } else {
            // Hash the password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            // Insert user into database
            $insert_query = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";
            if ($conn->query($insert_query) === TRUE) {
                $_SESSION['username'] = $username;
                header("Location: login.php");
                exit;
            } else {
                $error = "Error: " . $insert_query . "<br>" . $conn->error;
            }
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
        .form-container input[type="text"]{
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
        <h2>Sign Up</h2>
        <?php if (!empty($error)): ?>
            <div style="color: red; margin-bottom: 15px;">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>
        <form action="signup.php" method="POST">
            <input type="text" name="username" placeholder="Full Name" required>
            <input type="text" name="email" placeholder="Email" required>
            <input type="text" name="password" placeholder="Password" required>
            <input type="text" name="confirm_password" placeholder="Confirm Password" required>
            <button type="submit">Sign Up</button>
        </form>
        <p>Already have an account? <a href="login.php">Login here</a></p>
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
