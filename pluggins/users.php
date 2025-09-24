<?php
$servername = "localhost";
$username = "root";
$password = "1234";
$dbname = "mailapp";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM users ORDER BY id ASC";
$result = $conn->query($sql);

echo "<div style='max-width: 600px; margin: 50px auto; padding: 20px;'>";
echo "<h2>Registered Users</h2>";
echo "<ol>";
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "<li>" . htmlspecialchars($row["username"]) . 
             " - " . htmlspecialchars($row["email"]) . 
             " (Registered on " . $row["created_at"] . ")</li>";
    }
} else {
    echo "<li>No users found</li>";
}
echo "</ol>";
echo "<a href='index.html'>Back to Form</a>";
echo "</div>";

$conn->close();
?>