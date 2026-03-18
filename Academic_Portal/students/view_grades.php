<?php
session_start();
include '../db.php';

// Security check
if (!isset($_SESSION['username']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'student') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$username = htmlspecialchars($_SESSION['username']);

// Get grades with subject details
$grades = [];
$gpa_total = 0;
$grade_count = 0;

$grade_query = "SELECT g.*, s.subject_code, s.subject_name, s.units 
                FROM grades g 
                JOIN subjects s ON g.subject_id = s.id 
                WHERE g.student_id = ? 
                ORDER BY s.subject_code";

$stmt_grade = $conn->prepare($grade_query);
if ($stmt_grade) {
    $stmt_grade->bind_param("i", $user_id);
    $stmt_grade->execute();
    $grade_result = $stmt_grade->get_result();
    if ($grade_result && $grade_result->num_rows > 0) {
        while($row = $grade_result->fetch_assoc()) {
            // Calculate final grade if not set
            if ($row['prelim'] && $row['midterm'] && $row['finals']) {
                $final = ($row['prelim'] + $row['midterm'] + $row['finals']) / 3;
                $row['final_grade'] = round($final, 2);
                $gpa_total += $row['final_grade'];
                $grade_count++;
            }
            $grades[] = $row;
        }
    }
}

$gpa = ($grade_count > 0) ? round($gpa_total / $grade_count, 2) : 0;
?>