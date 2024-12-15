<?php
session_start(); // Start a session

// Database connection
$conn = new mysqli('localhost', 'root', '', 'lab5b');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$error_message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $matric = $_POST['matric'];
    $password = $_POST['password'];

    // Fetch user data
    $sql = "SELECT * FROM Users WHERE matric = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $matric);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            // Store session variables
            $_SESSION['logged_in'] = true;
            $_SESSION['user'] = [
                'matric' => $user['matric'],
                'name' => $user['name'],
                'role' => $user['role']
            ];

            // Redirect to the main page
            header("Location: display.php");
            exit();
        } else {
            $error_message = "Invalid username or password.";
        }
    } else {
        $error_message = "Invalid username or password.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>
    <form method="POST" action="">
        <label>Matric:</label>
        <input type="text" name="matric" required><br>
        <label>Password:</label>
        <input type="password" name="password" required><br>
        <button type="submit">Login</button>
    </form>
    <p>
        <a href="register.php">Register here if you have not.</a>
    </p>
    <?php if ($error_message): ?>
        <p style="color: red;"><?php echo $error_message; ?> Try <a href="login.php">login</a> again.</p>
    <?php endif; ?>
</body>
</html>
