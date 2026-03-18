<?php
header('Content-Type: application/json');

// Sample events para sa testing
$events = [
    [
        'id' => 1,
        'title' => 'First Day of Classes',
        'start' => date('Y-m-d'),
        'description' => 'Start of academic year',
        'backgroundColor' => '#4361ee',
        'borderColor' => '#4361ee',
        'textColor' => '#ffffff',
        'allDay' => true
    ],
    [
        'id' => 2,
        'title' => 'Christmas Break',
        'start' => date('Y-m-d', strtotime('+1 week')),
        'description' => 'School holiday',
        'backgroundColor' => '#f72585',
        'borderColor' => '#f72585',
        'textColor' => '#ffffff',
        'allDay' => true
    ],
    [
        'id' => 3,
        'title' => 'Final Exams',
        'start' => date('Y-m-d', strtotime('+2 weeks')),
        'description' => 'End of semester exams',
        'backgroundColor' => '#f8961e',
        'borderColor' => '#f8961e',
        'textColor' => '#ffffff',
        'allDay' => true
    ]
];

echo json_encode($events);
?>