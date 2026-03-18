<?php
include 'db.php';

// Password na gusto mong gamitin
$password = 'admin123'; // Pwede mong palitan 'to ng gusto mo

// Generate ng bagong hash
$hashed = password_hash($password, PASSWORD_DEFAULT);

echo "<h2>🔧 Admin Fix Tool</h2>";

// Una, tingnan kung may laman ang table
$check = $conn->query("SELECT COUNT(*) as total FROM users");
$row = $check->fetch_assoc();
echo "Total users in table: " . $row['total'] . "<br><br>";

// Tanggalin ang lumang admin para sigurado
$conn->query("DELETE FROM users WHERE username = 'admin'");
echo "✅ Lumang admin deleted (if any)<br>";

// Mag-insert ng bago
$sql = "INSERT INTO users (username, password, role) VALUES (?, ?, 'admin')";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $username, $hashed);

$username = 'admin';

if ($stmt->execute()) {
    echo "✅ Bagong admin created!<br>";
    echo "────────────────<br>";
    echo "Username: <strong>admin</strong><br>";
    echo "Password: <strong>" . $password . "</strong><br>";
    echo "Hash: " . $hashed . "<br>";
    echo "────────────────<br>";
    echo "<br>👉 <a href='secret_admin/login.php' target='_blank'>Mag-login na dito</a>";
} else {
    echo "❌ Error: " . $conn->error;
}

// Ipakita ang laman ng users table
echo "<h3>Current Users:</h3>";
$users = $conn->query("SELECT id, username, role, LEFT(password, 30) as pass_preview FROM users");
while($u = $users->fetch_assoc()) {
    echo "ID: {$u['id']} | Username: {$u['username']} | Role: {$u['role']} | Password: {$u['pass_preview']}...<br>";
}
?>