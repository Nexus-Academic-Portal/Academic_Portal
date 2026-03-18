<?php
// calendar.php - ITO ANG ACTUAL NA PAGE NA MAY DESIGN
session_start();
if(!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Academic Calendar | NEXUS Admin</title>
    
    <!-- CSS Files -->
    <link rel="stylesheet" href="designs_admin/dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />
    
    <!-- JavaScript Files -->
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
    
    <style>
        /* Reset at base styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
            background: #f4f7fc;
            overflow-x: hidden;
        }

        /* Sidebar styles */
        .sidebar {
            width: 280px;
            background: white;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
            transition: 0.3s;
            z-index: 1000;
            overflow-y: auto;
        }

        .sidebar.active {
            left: -280px;
        }

        .sidebar-header {
            padding: 25px 20px;
            border-bottom: 1px solid #eee;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .sidebar-logo {
            width: 40px;
            height: 40px;
            border-radius: 10px;
        }

        .sidebar-header h3 {
            font-size: 1.3rem;
            color: #2b2d42;
        }

        .sidebar-header span {
            color: #4361ee;
            font-weight: 300;
        }

        .nav-links {
            list-style: none;
            padding: 20px 0;
        }

        .nav-links li {
            padding: 10px 25px;
            margin: 5px 0;
        }

        .nav-links li a {
            text-decoration: none;
            color: #64748b;
            display: flex;
            align-items: center;
            gap: 15px;
            font-size: 1rem;
            transition: 0.3s;
        }

        .nav-links li a i {
            width: 20px;
            font-size: 1.2rem;
        }

        .nav-links li a:hover {
            color: #4361ee;
        }

        .nav-links li.active a {
            color: #4361ee;
            font-weight: 500;
        }

        .nav-links li.active {
            background: #eef2ff;
            border-left: 3px solid #4361ee;
        }

        .nav-links .logout {
            margin-top: 50px;
            border-top: 1px solid #eee;
            padding-top: 20px;
        }

        .nav-links .logout a {
            color: #f72585;
        }

        /* Hamburger button */
        .hamburger-btn {
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 1001;
            background: white;
            width: 45px;
            height: 45px;
            border-radius: 10px;
            display: none;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            font-size: 1.3rem;
            color: #4361ee;
        }

        /* Overlay */
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

        /* Main content */
        .main-content {
            margin-left: 280px;
            padding: 30px;
            transition: 0.3s;
            min-height: 100vh;
        }

        .main-content.expanded {
            margin-left: 0;
        }

        /* Top header */
        .top-header {
            background: white;
            padding: 20px 30px;
            border-radius: 20px;
            margin-bottom: 30px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .profile-icon {
            font-size: 2rem;
            color: #4361ee;
            background: #eef2ff;
            padding: 1rem;
            border-radius: 50%;
        }

        .welcome-banner h2 {
            color: #2b2d42;
            font-size: 1.8rem;
            margin-bottom: 5px;
        }

        .welcome-banner p {
            color: #64748b;
            font-size: 1rem;
        }

        /* Dashboard cards */
        .dashboard-card {
            background: white;
            border-radius: 20px;
            padding: 25px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            margin-bottom: 25px;
        }

        /* Calendar styles */
        .fc {
            background: white;
            padding: 20px;
            border-radius: 15px;
        }

        .fc-toolbar-title {
            font-size: 1.5rem !important;
            color: #2b2d42 !important;
            font-weight: 600 !important;
        }

        .fc-button-primary {
            background-color: #4361ee !important;
            border-color: #4361ee !important;
        }

        .fc-button-primary:hover {
            background-color: #3046c0 !important;
            border-color: #3046c0 !important;
        }

        .fc-day-today {
            background-color: #eef2ff !important;
        }

        .fc-event {
            cursor: pointer;
            border-radius: 6px;
            padding: 3px 5px;
            font-size: 0.85rem;
            border: none;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            transition: transform 0.2s;
            margin: 2px 0;
        }

        .fc-event:hover {
            transform: scale(1.02);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }

        /* Event list styles */
        .event-item {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 15px;
            background: #f8fafc;
            border-radius: 12px;
            margin-bottom: 10px;
            transition: 0.3s;
        }

        .event-item:hover {
            background: #eef2ff;
        }

        .event-date-box {
            background: white;
            padding: 10px;
            border-radius: 10px;
            text-align: center;
            min-width: 60px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        .event-date-box .day {
            font-weight: bold;
            color: #2b2d42;
            font-size: 1.2rem;
        }

        .event-date-box .month {
            font-size: 0.8rem;
            color: #64748b;
        }

        .event-details {
            flex: 1;
        }

        .event-type-badge {
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 0.7rem;
            font-weight: 600;
            color: white;
            display: inline-block;
            margin-bottom: 5px;
        }

        .event-title {
            color: #2b2d42;
            margin-bottom: 3px;
            font-size: 1rem;
        }

        .event-description {
            color: #64748b;
            font-size: 0.85rem;
        }

        .btn-add-event {
            background: #4361ee;
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 12px;
            cursor: pointer;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: 0.3s;
        }

        .btn-add-event:hover {
            background: #3046c0;
            transform: translateY(-2px);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                left: -280px;
            }
            
            .sidebar.active {
                left: 0;
            }
            
            .main-content {
                margin-left: 0;
                padding: 20px;
            }
            
            .hamburger-btn {
                display: flex;
            }
            
            .top-header {
                flex-direction: column;
                text-align: center;
                padding: 20px;
            }
        }
    </style>
</head>
<body>

    <div class="hamburger-btn" id="btn">
        <i class="fas fa-bars"></i>
    </div>

    <nav class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <img src="logo.png" class="sidebar-logo" alt="Logo" onerror="this.src='https://via.placeholder.com/40x40?text=N'"> 
            <h3>NEXUS <span>Admin</span></h3>
        </div>
        <ul class="nav-links">
            <li><a href="dashboard.php"><i class="fas fa-th-large"></i> Dashboard</a></li>
            <li><a href="add_student.php"><i class="fas fa-user-plus"></i> Add Student</a></li>
            <li><a href="students.php"><i class="fas fa-user-graduate"></i> Students</a></li>
            <li><a href="manage_students.php"><i class="fas fa-users-cog"></i> Manage Students</a></li>
            <li><a href="subjects.php"><i class="fas fa-book"></i> Subjects</a></li>
            <li><a href="grades.php"><i class="fas fa-chart-line"></i> Grades</a></li>
            <li><a href="announcements.php"><i class="fas fa-bullhorn"></i> Announcements</a></li>
            <li class="active"><a href="calendar.php"><i class="fas fa-calendar-alt"></i> Calendar</a></li>
            <li class="logout"><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </nav>

    <div class="sidebar-overlay" id="overlay"></div>

    <main class="main-content" id="mainContent">
        <header class="top-header">
            <i class="fas fa-calendar-day profile-icon"></i>
            <div class="welcome-banner">
                <h2>Academic Calendar</h2>
                <p>View and manage school events, holidays, and examinations</p>
            </div>
        </header>

        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 25px;">
            <!-- Calendar Card -->
            <div class="dashboard-card">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                    <h3 style="color: #2b2d42; font-size: 1.3rem;">
                        <i class="fas fa-calendar-alt" style="color: #4361ee; margin-right: 10px;"></i>
                        Event Calendar
                    </h3>
                    <button class="btn-add-event" onclick="location.href='add_event.php'">
                        <i class="fas fa-plus"></i> Add Event
                    </button>
                </div>
                <div id="calendar"></div>
            </div>

            <!-- Upcoming Events List -->
            <div class="dashboard-card">
                <h3 style="color: #2b2d42; font-size: 1.3rem; margin-bottom: 20px;">
                    <i class="fas fa-list" style="color: #4361ee; margin-right: 10px;"></i>
                    Upcoming Events
                </h3>
                <div id="upcoming-events">
                    <div style="text-align: center; color: #64748b; padding: 30px;">
                        <i class="fas fa-spinner fa-spin" style="font-size: 2rem; margin-bottom: 10px;"></i>
                        <p>Loading events...</p>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        // Sidebar toggle functionality
        const btn = document.getElementById('btn');
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('overlay');
        const mainContent = document.getElementById('mainContent');

        btn.onclick = function() {
            sidebar.classList.toggle('active');
            overlay.classList.toggle('active');
            mainContent.classList.toggle('expanded');
        };

        overlay.onclick = function() {
            sidebar.classList.remove('active');
            overlay.classList.remove('active');
            mainContent.classList.remove('expanded');
        };

        // FullCalendar initialization
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            
            if (calendarEl) {
                var calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'dayGridMonth',
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,timeGridWeek,listWeek'
                    },
                    events: 'calendar_api.php',
                    eventClick: function(info) {
                        alert('📅 ' + info.event.title + 
                              '\n📝 ' + (info.event.extendedProps.description || 'No description') +
                              '\n📆 ' + info.event.start.toLocaleDateString());
                    },
                    eventDidMount: function(info) {
                        // Add tooltip
                        info.el.setAttribute('title', info.event.title);
                    },
                    loading: function(isLoading) {
                        if (!isLoading) {
                            loadUpcomingEvents();
                        }
                    },
                    height: 'auto',
                    aspectRatio: 1.5
                });
                
                calendar.render();
            }
        });

        // Load upcoming events
        function loadUpcomingEvents() {
            const container = document.getElementById('upcoming-events');
            
            fetch('calendar_api.php?start=' + new Date().toISOString().split('T')[0])
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(events => {
                    if (!events || events.length === 0) {
                        container.innerHTML = `
                            <div style="text-align: center; color: #64748b; padding: 30px;">
                                <i class="fas fa-calendar-times" style="font-size: 2rem; margin-bottom: 10px;"></i>
                                <p>No upcoming events</p>
                            </div>
                        `;
                        return;
                    }
                    
                    // Sort events by date
                    events.sort((a, b) => new Date(a.start) - new Date(b.start));
                    
                    // Show only next 5 events
                    const upcomingEvents = events.slice(0, 5);
                    
                    container.innerHTML = upcomingEvents.map(event => {
                        const date = new Date(event.start);
                        const formattedDate = date.toLocaleDateString('en-US', { 
                            month: 'short', 
                            day: 'numeric',
                            year: 'numeric'
                        });
                        
                        let eventType = 'Event';
                        let typeColor = '#4361ee';
                        
                        if (event.backgroundColor === '#f72585') {
                            eventType = 'Holiday';
                            typeColor = '#f72585';
                        } else if (event.backgroundColor === '#f8961e') {
                            eventType = 'Exam';
                            typeColor = '#f8961e';
                        }
                        
                        return `
                            <div class="event-item">
                                <div class="event-date-box">
                                    <div class="day">${date.getDate()}</div>
                                    <div class="month">${date.toLocaleString('default', { month: 'short' })}</div>
                                </div>
                                <div class="event-details">
                                    <span class="event-type-badge" style="background: ${typeColor};">${eventType}</span>
                                    <h4 class="event-title">${event.title}</h4>
                                    <p class="event-description">${event.description || 'No description'}</p>
                                </div>
                            </div>
                        `;
                    }).join('');
                })
                .catch(error => {
                    console.error('Error loading events:', error);
                    container.innerHTML = `
                        <div style="text-align: center; color: #f72585; padding: 30px;">
                            <i class="fas fa-exclamation-circle" style="font-size: 2rem; margin-bottom: 10px;"></i>
                            <p>Error loading events</p>
                        </div>
                    `;
                });
        }
    </script>
</body>
</html>