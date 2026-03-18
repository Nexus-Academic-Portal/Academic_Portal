<?php
session_start();
include '../db.php';

// ============================================
// SECURITY CHECK - Student Access Only
// ============================================
if (!isset($_SESSION['username']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'student') {
    header("Location: login.php");
    exit();
}

// ============================================
// GET STUDENT INFORMATION
// ============================================
$username = htmlspecialchars($_SESSION['username']);
$user_id = $_SESSION['user_id'];

// Get current date and time for greeting
$hour = date('H');
$greeting = '';
if ($hour < 12) {
    $greeting = 'Good Morning';
} elseif ($hour < 18) {
    $greeting = 'Good Afternoon';
} else {
    $greeting = 'Good Evening';
}

// ============================================
// DASHBOARD STATISTICS WITH ERROR HANDLING
// ============================================

// 1. SUBJECTS COUNT - Check if tables exist
$subject_count = 0;
$subject_count_query = "SELECT COUNT(*) as total FROM subjects";
$subject_result = $conn->query($subject_count_query);
if ($subject_result) {
    $subject_count = $subject_result->fetch_assoc()['total'];
}

// 2. ENROLLED SUBJECTS COUNT - Check if student_subjects table exists
$enrolled_count = 0;
$enrolled_query = "SELECT COUNT(*) as total FROM student_subjects WHERE student_id = ?";
$stmt_enrolled = $conn->prepare($enrolled_query);
if ($stmt_enrolled) {
    $stmt_enrolled->bind_param("i", $user_id);
    $stmt_enrolled->execute();
    $enrolled_result = $stmt_enrolled->get_result();
    if ($enrolled_result) {
        $enrolled_count = $enrolled_result->fetch_assoc()['total'];
    }
}

// 3. GPA CALCULATION - Check if grades table exists
$gpa_display = '0.00';
$grades_exist = false;

// Check if grades table exists
$table_check = $conn->query("SHOW TABLES LIKE 'grades'");
if ($table_check && $table_check->num_rows > 0) {
    $grades_exist = true;
    
    $gpa_query = "SELECT AVG((prelim + midterm + finals) / 3) as gpa 
                  FROM grades 
                  WHERE student_id = ? 
                  AND prelim IS NOT NULL 
                  AND midterm IS NOT NULL 
                  AND finals IS NOT NULL";
    
    $stmt_gpa = $conn->prepare($gpa_query);
    if ($stmt_gpa) {
        $stmt_gpa->bind_param("i", $user_id);
        $stmt_gpa->execute();
        $gpa_result = $stmt_gpa->get_result();
        if ($gpa_result && $gpa_result->num_rows > 0) {
            $gpa_row = $gpa_result->fetch_assoc();
            if ($gpa_row['gpa'] !== null) {
                $gpa_display = number_format($gpa_row['gpa'], 2);
            }
        }
    }
}

// 4. RECENT ANNOUNCEMENTS
$announcements = [];
$ann_query = "SELECT * FROM announcements ORDER BY created_at DESC LIMIT 3";
$ann_result = $conn->query($ann_query);
if ($ann_result && $ann_result->num_rows > 0) {
    while($row = $ann_result->fetch_assoc()) {
        $announcements[] = $row;
    }
}

// 5. TODAY'S EVENTS
$today = date('Y-m-d');
$events = [];
$events_query = "SELECT * FROM events WHERE event_date = ? ORDER BY title";
$stmt_events = $conn->prepare($events_query);
if ($stmt_events) {
    $stmt_events->bind_param("s", $today);
    $stmt_events->execute();
    $events_result = $stmt_events->get_result();
    if ($events_result && $events_result->num_rows > 0) {
        while($row = $events_result->fetch_assoc()) {
            $events[] = $row;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard | NEXUS Academic Portal</title>
    
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Chart.js for Analytics -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
        }

        /* ========== SIDEBAR STYLES ========== */
        .sidebar {
            position: fixed;
            left: -280px;
            top: 0;
            width: 280px;
            height: 100%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            transition: all 0.3s ease;
            z-index: 1000;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }

        .sidebar.active {
            left: 0;
        }

        .sidebar-header {
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .sidebar-logo {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            margin-bottom: 10px;
            border: 2px solid white;
        }

        .sidebar-header h3 {
            color: white;
            font-size: 1.2rem;
        }

        .sidebar-header span {
            font-size: 0.85rem;
            opacity: 0.8;
        }

        .nav-links {
            list-style: none;
            padding: 20px 0;
        }

        .nav-links li {
            padding: 10px 20px;
            transition: all 0.3s;
        }

        .nav-links li:hover {
            background: rgba(255,255,255,0.1);
        }

        .nav-links li.active {
            background: rgba(255,255,255,0.2);
            border-left: 4px solid white;
        }

        .nav-links a {
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.95rem;
        }

        .nav-links i {
            width: 20px;
            font-size: 1.1rem;
        }

        .nav-links .logout {
            position: absolute;
            bottom: 20px;
            width: 100%;
        }

        /* ========== MAIN CONTENT ========== */
        .main-content {
            margin-left: 0;
            transition: all 0.3s ease;
            padding: 20px;
        }

        @media (min-width: 992px) {
            .sidebar {
                left: 0;
            }
            .main-content {
                margin-left: 280px;
            }
            .hamburger-btn {
                display: none;
            }
        }

        /* ========== HAMBURGER BUTTON ========== */
        .hamburger-btn {
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 1001;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            width: 45px;
            height: 45px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
            transition: all 0.3s;
        }

        .hamburger-btn:hover {
            transform: scale(1.05);
        }

        .sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 999;
            display: none;
        }

        .sidebar-overlay.active {
            display: block;
        }

        /* ========== TOP HEADER ========== */
        .top-header {
            background: white;
            padding: 20px 30px;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .profile-icon {
            font-size: 2.5rem;
            color: #667eea;
        }

        .greeting {
            font-size: 1.1rem;
        }

        .greeting h1 {
            font-size: 1.5rem;
            margin-bottom: 5px;
            color: #333;
        }

        .greeting p {
            color: #666;
        }

        .date-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 25px;
            border-radius: 10px;
            text-align: center;
        }

        .date-card .day {
            font-size: 1.8rem;
            font-weight: bold;
            line-height: 1;
        }

        .date-card .month {
            font-size: 1rem;
            opacity: 0.9;
        }

        /* ========== STATS CARDS ========== */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: all 0.3s;
            position: relative;
            overflow: hidden;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            transform: translate(20px, -20px);
            opacity: 0.1;
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
        }

        .stat-icon i {
            font-size: 1.8rem;
            color: white;
        }

        .stat-card h3 {
            font-size: 2rem;
            margin-bottom: 5px;
            color: #333;
        }

        .stat-card p {
            color: #666;
            font-weight: 500;
        }

        .stat-card small {
            color: #999;
            font-size: 0.85rem;
        }

        /* ========== WIDGETS GRID ========== */
        .widgets-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 20px;
        }

        .widget {
            background: white;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .widget-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #f0f0f0;
        }

        .widget-header h3 {
            color: #333;
            font-size: 1.2rem;
        }

        .widget-header i {
            color: #667eea;
            font-size: 1.2rem;
        }

        /* ========== ANNOUNCEMENTS ========== */
        .announcement-item {
            padding: 15px 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .announcement-item:last-child {
            border-bottom: none;
        }

        .announcement-title {
            font-weight: 600;
            color: #333;
            margin-bottom: 5px;
        }

        .announcement-meta {
            font-size: 0.85rem;
            color: #999;
            display: flex;
            gap: 10px;
        }

        .announcement-meta i {
            margin-right: 3px;
        }

        .announcement-preview {
            color: #666;
            font-size: 0.9rem;
            margin-top: 5px;
        }

        /* ========== EVENTS LIST ========== */
        .event-item {
            display: flex;
            gap: 15px;
            padding: 10px 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .event-date {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 10px;
            border-radius: 10px;
            min-width: 60px;
            text-align: center;
        }

        .event-date .day {
            font-size: 1.3rem;
            font-weight: bold;
            line-height: 1;
        }

        .event-date .month {
            font-size: 0.8rem;
            opacity: 0.9;
        }

        .event-details {
            flex: 1;
        }

        .event-details h4 {
            color: #333;
            margin-bottom: 3px;
        }

        .event-details p {
            color: #666;
            font-size: 0.9rem;
        }

        .event-type {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 3px;
            font-size: 0.7rem;
            font-weight: 600;
            margin-top: 5px;
        }

        .type-academic { background: #4299e1; color: white; }
        .type-holiday { background: #f56565; color: white; }
        .type-exam { background: #ed8936; color: white; }
        .type-meeting { background: #9f7aea; color: white; }
        .type-other { background: #48bb78; color: white; }

        /* ========== CHAT BOT ========== */
        #chat-toggle-btn {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            cursor: pointer;
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
            font-size: 1.5rem;
            z-index: 999;
            transition: all 0.3s;
        }

        #chat-toggle-btn:hover {
            transform: scale(1.1);
        }

        #chat-container {
            position: fixed;
            bottom: 100px;
            right: 30px;
            width: 350px;
            height: 500px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            display: none;
            flex-direction: column;
            z-index: 1000;
            overflow: hidden;
        }

        #chat-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        #close-chat {
            background: none;
            border: none;
            color: white;
            font-size: 1.2rem;
            cursor: pointer;
        }

        #chat-box {
            flex: 1;
            padding: 15px;
            overflow-y: auto;
            background: #f8f9fa;
        }

        .bot-msg, .user-msg {
            margin-bottom: 15px;
            max-width: 80%;
            padding: 10px 15px;
            border-radius: 10px;
            animation: slideIn 0.3s;
        }

        @keyframes slideIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .bot-msg {
            background: white;
            color: #333;
            align-self: flex-start;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .user-msg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            margin-left: auto;
        }

        #chat-input-area {
            padding: 15px;
            background: white;
            border-top: 1px solid #f0f0f0;
            display: flex;
            gap: 10px;
        }

        #user-input {
            flex: 1;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
            outline: none;
        }

        #user-input:focus {
            border-color: #667eea;
        }

        #send-btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            width: 45px;
            height: 45px;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s;
        }

        #send-btn:hover {
            transform: scale(1.05);
        }

        /* ========== RESPONSIVE ========== */
        @media (max-width: 768px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .widgets-grid {
                grid-template-columns: 1fr;
            }
            
            .top-header {
                flex-direction: column;
                text-align: center;
            }
            
            #chat-container {
                width: 90%;
                right: 5%;
                left: 5%;
            }
        }

        /* ========== LOADING STATES ========== */
        .loading {
            opacity: 0.6;
            pointer-events: none;
        }
        
        .no-data {
            text-align: center;
            color: #999;
            padding: 20px;
        }
        
        .no-data i {
            font-size: 3rem;
            margin-bottom: 10px;
            opacity: 0.3;
        }
    </style>
</head>
<body>
    <!-- Sidebar Overlay (Mobile) -->
    <div class="sidebar-overlay" id="sidebar-overlay"></div>

    <!-- Hamburger Button (Mobile) -->
    <div class="hamburger-btn" id="hamburger-btn">
        <i class="fas fa-bars"></i>
    </div>

    <!-- Sidebar Navigation -->
    <nav class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <img src="../images/olshco.jpg" alt="Logo" class="sidebar-logo">
            <h3>NEXUS</h3>
            <span>Academic Portal</span>
        </div>
        <ul class="nav-links">
            <li class="active">
                <a href="home.php">
                    <i class="fas fa-th-large"></i> <span>Dashboard</span>
                </a>
            </li>
            <li>
                <a href="subjects.php">
                    <i class="fas fa-book"></i> <span>Subjects</span>
                </a>
            </li>
            <li>
                <a href="view_grades.php">
                    <i class="fas fa-file-invoice"></i> <span>View Grades</span>
                </a>
            </li>
            <li>
                <a href="announcements.php">
                    <i class="fas fa-bullhorn"></i> <span>Announcements</span>
                </a>
            </li>
            <li>
                <a href="about.php">
                    <i class="fas fa-users"></i> <span>About Us</span>
                </a>
            </li>
            <li class="logout">
                <a href="../logout.php">
                    <i class="fas fa-sign-out-alt"></i> <span>Logout</span>
                </a>
            </li>
        </ul>
    </nav>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Top Header with Greeting -->
        <header class="top-header">
            <div class="user-info">
                <i class="fas fa-user-circle profile-icon"></i>
                <div class="greeting">
                    <h1><?php echo $greeting; ?>, <?php echo $username; ?>!</h1>
                    <p>Welcome back to your academic dashboard</p>
                </div>
            </div>
            <div class="date-card">
                <div class="day"><?php echo date('d'); ?></div>
                <div class="month"><?php echo date('F Y'); ?></div>
            </div>
        </header>

        <!-- Statistics Cards -->
        <div class="stats-grid">
            <!-- Total Subjects Card -->
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-book-open"></i>
                </div>
                <h3><?php echo $subject_count; ?></h3>
                <p>Total Subjects</p>
                <small>Available in curriculum</small>
            </div>

            <!-- Enrolled Subjects Card -->
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-user-graduate"></i>
                </div>
                <h3><?php echo $enrolled_count; ?></h3>
                <p>Enrolled Subjects</p>
                <small>Current enrollment</small>
            </div>

            <!-- GPA Card -->
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-star"></i>
                </div>
                <h3><?php echo $gpa_display; ?></h3>
                <p>Current GPA</p>
                <small><?php echo $grades_exist ? 'Based on completed subjects' : 'No grades yet'; ?></small>
            </div>
        </div>

        <!-- Widgets Grid -->
        <div class="widgets-grid">
            <!-- Recent Announcements Widget -->
            <div class="widget">
                <div class="widget-header">
                    <h3><i class="fas fa-bullhorn"></i> Recent Announcements</h3>
                    <a href="announcements.php" style="color: #667eea; text-decoration: none; font-size: 0.9rem;">View All →</a>
                </div>
                <div class="announcements-list">
                    <?php if (empty($announcements)): ?>
                        <div class="no-data">
                            <i class="fas fa-inbox"></i>
                            <p>No announcements yet</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($announcements as $ann): ?>
                            <div class="announcement-item">
                                <div class="announcement-title"><?php echo htmlspecialchars($ann['title']); ?></div>
                                <div class="announcement-meta">
                                    <span><i class="fas fa-calendar"></i> <?php echo date('M d, Y', strtotime($ann['created_at'])); ?></span>
                                </div>
                                <div class="announcement-preview">
                                    <?php echo substr(htmlspecialchars($ann['message']), 0, 100) . '...'; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Today's Events Widget -->
            <div class="widget">
                <div class="widget-header">
                    <h3><i class="fas fa-calendar-alt"></i> Today's Events</h3>
                    <a href="calendar.php" style="color: #667eea; text-decoration: none; font-size: 0.9rem;">View Calendar →</a>
                </div>
                <div class="events-list">
                    <?php if (empty($events)): ?>
                        <div class="no-data">
                            <i class="fas fa-calendar-check"></i>
                            <p>No events scheduled for today</p>
                            <small><?php echo date('F d, Y'); ?></small>
                        </div>
                    <?php else: ?>
                        <?php foreach ($events as $event): ?>
                            <div class="event-item">
                                <div class="event-date">
                                    <div class="day"><?php echo date('d', strtotime($event['event_date'])); ?></div>
                                    <div class="month"><?php echo date('M', strtotime($event['event_date'])); ?></div>
                                </div>
                                <div class="event-details">
                                    <h4><?php echo htmlspecialchars($event['title']); ?></h4>
                                    <p><?php echo htmlspecialchars($event['description']); ?></p>
                                    <span class="event-type type-<?php echo $event['event_type']; ?>">
                                        <?php echo ucfirst($event['event_type']); ?>
                                    </span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Quick Actions Widget -->
            <div class="widget">
                <div class="widget-header">
                    <h3><i class="fas fa-bolt"></i> Quick Actions</h3>
                </div>
                <div style="display: grid; gap: 10px;">
                    <a href="subjects.php" style="display: block; padding: 12px; background: #f8f9fa; border-radius: 8px; color: #333; text-decoration: none; transition: all 0.3s;">
                        <i class="fas fa-book" style="color: #667eea; margin-right: 10px;"></i>
                        View Available Subjects
                    </a>
                    <a href="view_grades.php" style="display: block; padding: 12px; background: #f8f9fa; border-radius: 8px; color: #333; text-decoration: none; transition: all 0.3s;">
                        <i class="fas fa-chart-line" style="color: #667eea; margin-right: 10px;"></i>
                        Check Your Grades
                    </a>
                    <a href="announcements.php" style="display: block; padding: 12px; background: #f8f9fa; border-radius: 8px; color: #333; text-decoration: none; transition: all 0.3s;">
                        <i class="fas fa-bullhorn" style="color: #667eea; margin-right: 10px;"></i>
                        Read Announcements
                    </a>
                </div>
            </div>
        </div>
    </main>

    <!-- Chat Bot Toggle Button -->
    <button id="chat-toggle-btn">
        <i class="fas fa-comment-dots"></i>
    </button>

    <!-- Chat Bot Container -->
    <div id="chat-container">
        <div id="chat-header">
            <span><i class="fas fa-robot"></i> Nexus AI Assistant</span>
            <button id="close-chat"><i class="fas fa-times"></i></button>
        </div>
        <div id="chat-box">
            <div class="bot-msg">
                <?php echo $greeting; ?>! I'm your Nexus Assistant. How can I help you today?
            </div>
        </div>
        <div id="chat-input-area">
            <input type="text" id="user-input" placeholder="Type your message...">
            <button id="send-btn"><i class="fas fa-paper-plane"></i></button>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        // ========== SIDEBAR TOGGLE ==========
        const hamburgerBtn = document.getElementById('hamburger-btn');
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');

        function toggleSidebar() {
            sidebar.classList.toggle('active');
            overlay.classList.toggle('active');
            
            // Change icon
            const icon = hamburgerBtn.querySelector('i');
            if (sidebar.classList.contains('active')) {
                icon.classList.remove('fa-bars');
                icon.classList.add('fa-times');
            } else {
                icon.classList.remove('fa-times');
                icon.classList.add('fa-bars');
            }
        }

        hamburgerBtn.addEventListener('click', toggleSidebar);
        overlay.addEventListener('click', toggleSidebar);

        // Close sidebar when clicking on a link (mobile)
        const navLinks = document.querySelectorAll('.nav-links a');
        navLinks.forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth < 992) {
                    toggleSidebar();
                }
            });
        });

        // ========== CHAT BOT FUNCTIONALITY ==========
        const chatToggle = document.getElementById('chat-toggle-btn');
        const chatContainer = document.getElementById('chat-container');
        const closeChat = document.getElementById('close-chat');
        const sendBtn = document.getElementById('send-btn');
        const userInput = document.getElementById('user-input');
        const chatBox = document.getElementById('chat-box');

        // Toggle chat
        chatToggle.addEventListener('click', () => {
            chatContainer.style.display = chatContainer.style.display === 'flex' ? 'none' : 'flex';
        });

        closeChat.addEventListener('click', () => {
            chatContainer.style.display = 'none';
        });

        // Send message function
        async function sendMessage() {
            const message = userInput.value.trim();
            if (!message) return;

            // Add user message to chat
            chatBox.innerHTML += `<div class="user-msg">${message}</div>`;
            userInput.value = '';
            chatBox.scrollTop = chatBox.scrollHeight;

            // Show typing indicator
            chatBox.innerHTML += `<div class="bot-msg" id="typing-indicator"><i class="fas fa-ellipsis-h"></i> Assistant is typing...</div>`;
            chatBox.scrollTop = chatBox.scrollHeight;

            try {
                const response = await fetch('chat_handler.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ message: message })
                });

                // Remove typing indicator
                document.getElementById('typing-indicator')?.remove();

                const data = await response.json();
                
                if (data.response) {
                    chatBox.innerHTML += `<div class="bot-msg">${data.response}</div>`;
                } else {
                    chatBox.innerHTML += `<div class="bot-msg" style="color: #f56565;">Sorry, I couldn't process that. Please try again.</div>`;
                }
            } catch (error) {
                document.getElementById('typing-indicator')?.remove();
                chatBox.innerHTML += `<div class="bot-msg" style="color: #f56565;">Network error. Please check your connection.</div>`;
            }

            chatBox.scrollTop = chatBox.scrollHeight;
        }

        sendBtn.addEventListener('click', sendMessage);
        
        userInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                sendMessage();
            }
        });

        // ========== PAGE LOAD ANIMATION ==========
        document.addEventListener('DOMContentLoaded', () => {
            const cards = document.querySelectorAll('.stat-card, .widget');
            cards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                setTimeout(() => {
                    card.style.transition = 'all 0.5s ease';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 100);
            });
        });
    </script>
</body>
</html>