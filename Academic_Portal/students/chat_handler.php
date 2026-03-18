<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['username'])) {
    echo json_encode(['error' => 'Not logged in']);
    exit();
}

$data = json_decode(file_get_contents('php://input'), true);
$message = strtolower(trim($data['message'] ?? ''));

$response = '';

// Simple chatbot responses
if (strpos($message, 'hello') !== false || strpos($message, 'hi') !== false) {
    $response = "Hello! How can I help you today?";
} elseif (strpos($message, 'grade') !== false) {
    $response = "You can check your grades by clicking on 'View Grades' in the sidebar.";
} elseif (strpos($message, 'subject') !== false) {
    $response = "All available subjects are listed in the 'Subjects' section.";
} elseif (strpos($message, 'announcement') !== false) {
    $response = "Recent announcements are displayed on your dashboard. Click 'Announcements' to see all.";
} elseif (strpos($message, 'event') !== false || strpos($message, 'calendar') !== false) {
    $response = "Check the Calendar section for upcoming events and activities.";
} elseif (strpos($message, 'thank') !== false) {
    $response = "You're welcome! Is there anything else I can help with?";
} else {
    $response = "I'm not sure about that. Please contact the admin for assistance, or try asking about: grades, subjects, announcements, or events.";
}

echo json_encode(['response' => $response]);
?>