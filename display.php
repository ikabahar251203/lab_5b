<?php
include 'session_check.php'; // Ensure user is logged in

// Database connection
$conn = new mysqli('localhost', 'root', '', 'lab5b');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch data
$sql = "SELECT matric, name, role AS Level FROM Users";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Users List</title>
</head>
<body>
    <h2>Welcome, <?php echo $_SESSION['user']['name']; ?>!</h2>

    <!-- Logout button -->
    <form action="logout.php" method="POST" style="display: inline;">
        <button type="submit">Log Out</button>
    </form>

    <table border="1">
        <tr>
            <th>Matric</th>
            <th>Name</th>
            <th>Level</th>
            <th>Action</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['matric']}</td>
                        <td>{$row['name']}</td>
                        <td>{$row['Level']}</td>
                        <td>
                            <a href='update.php?matric={$row['matric']}'>Update</a> |
                            <a href='display.php?delete={$row['matric']}' onclick=\"return confirm('Are you sure?')\">Delete</a>
                        </td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='4'>No data available</td></tr>";
        }
        ?>
    </table>
</body>
</html>
