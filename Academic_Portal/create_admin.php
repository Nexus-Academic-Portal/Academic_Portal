<?php
include 'db.php';

$username = 'admin';
$password = password_hash('12345', PASSWORD_DEFAULT);
$role = 'admin';

$sql = "INSERT INTO users (username, password, role) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $username, $password, $role);

if ($stmt->execute()) {
    echo "Admin user created successfully!<br>";
    echo "Username: admin<br>";
    echo "Password: 12345<br>";
    echo "Hash: " . $password;
} else {
    echo "Error: " . $conn->error;
}
?>