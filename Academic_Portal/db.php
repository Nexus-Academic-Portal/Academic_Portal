<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'nexus_db';  // TAMA na ito, hindi 'user_system'

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// For debugging (optional)
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>