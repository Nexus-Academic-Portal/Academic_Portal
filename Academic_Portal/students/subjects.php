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

// Get all subjects
$subjects = [];
$subj_query = "SELECT * FROM subjects ORDER BY subject_code";
$subj_result = $conn->query($subj_query);
if ($subj_result && $subj_result->num_rows > 0) {
    while($row = $subj_result->fetch_assoc()) {
        $subjects[] = $row;
    }
}

// Add this for debugging (remove later)
echo "<!-- Number of subjects: " . count($subjects) . " -->";

// Get enrolled subjects
$enrolled = [];
$enroll_query = "SELECT subject_id FROM student_subjects WHERE student_id = ?";
$stmt_enroll = $conn->prepare($enroll_query);
if ($stmt_enroll) {
    $stmt_enroll->bind_param("i", $user_id);
    $stmt_enroll->execute();
    $enroll_result = $stmt_enroll->get_result();
    if ($enroll_result) {
        while($row = $enroll_result->fetch_assoc()) {
            $enrolled[] = $row['subject_id'];
        }
    }
}

// Handle enrollment
if (isset($_POST['enroll'])) {
    $subject_id = $_POST['subject_id'];
    
    // Check if already enrolled
    if (!in_array($subject_id, $enrolled)) {
        $insert_query = "INSERT INTO student_subjects (student_id, subject_id) VALUES (?, ?)";
        $stmt_insert = $conn->prepare($insert_query);
        $stmt_insert->bind_param("ii", $user_id, $subject_id);
        if ($stmt_insert->execute()) {
            header("Location: subjects.php?msg=enrolled");
            exit();
        }
    }
}

// Handle unenroll
if (isset($_GET['unenroll'])) {
    $subject_id = $_GET['unenroll'];
    $delete_query = "DELETE FROM student_subjects WHERE student_id = ? AND subject_id = ?";
    $stmt_delete = $conn->prepare($delete_query);
    $stmt_delete->bind_param("ii", $user_id, $subject_id);
    if ($stmt_delete->execute()) {
        header("Location: subjects.php?msg=unenrolled");
        exit();
    }
}

$message = "";
if (isset($_GET['msg'])) {
    if ($_GET['msg'] == 'enrolled') $message = "Successfully enrolled in subject!";
    if ($_GET['msg'] == 'unenrolled') $message = "Successfully unenrolled from subject!";
}
?>