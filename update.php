<?php
// Database connection
$conn = new mysqli('localhost', 'root', '', 'lab5b');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize the $user variable
$user = [
    'matric' => '',
    'name' => '',
    'role' => ''
];

// Fetch the existing user data if `matric` is provided in the URL
if (isset($_GET['matric'])) {
    $matric = $_GET['matric'];

    // Prepare the SQL query
    $sql = "SELECT * FROM Users WHERE matric = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $matric);
    $stmt->execute();
    $result = $stmt->get_result();

    // If data is found, assign it to the $user variable
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
    } else {
        echo "<p style='color: red;'>User not found!</p>";
    }
}

// Handle form submission for updating the user
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $matric = $_POST['matric'];
    $name = $_POST['name'];
    $role = $_POST['role'];

    // Update query
    $sql = "UPDATE Users SET name = ?, role = ? WHERE matric = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sss', $name, $role, $matric);
    if ($stmt->execute()) {
        header("Location: display.php");
        exit();
    } else {
        echo "<p style='color: red;'>Failed to update user!</p>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update User</title>
</head>
<body>
    <form method="POST" action="">
        <label>Matric:</label>
        <input type="text" name="matric" value="<?php echo htmlspecialchars($user['matric']); ?>" readonly><br>
        <label>Name:</label>
        <input type="text" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required><br>
        <label>Role:</label>
        <select name="role" required>
            <option value="student" <?php if ($user['role'] === 'student') echo 'selected'; ?>>Student</option>
            <option value="lecturer" <?php if ($user['role'] === 'lecturer') echo 'selected'; ?>>Lecturer</option>
        </select><br>
        <button type="submit">Update</button>
    </form>
</body>
</html>
