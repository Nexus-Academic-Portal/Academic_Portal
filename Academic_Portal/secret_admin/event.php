<?php
session_start();
include 'db.php';

if(!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Handle Add Event
if(isset($_POST['add_event'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $event_date = $_POST['event_date'];
    $event_type = $_POST['event_type'];
    $created_by = $_SESSION['username'];
    
    $sql = "INSERT INTO events (title, description, event_date, event_type, created_by) 
            VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $title, $description, $event_date, $event_type, $created_by);
    
    if($stmt->execute()) {
        $message = "Event added successfully!";
        $status = "success";
    } else {
        $message = "Error adding event: " . $conn->error;
        $status = "error";
    }
}

// Handle Delete Event
if(isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM events WHERE id = $id");
    header("Location: event.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendar | NEXUS Admin</title>
    <link rel="stylesheet" href="designs_admin/dashboard.css">
    <!-- FullCalendar CSS -->
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />
    
    <!-- Your existing admin styles -->
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        .calendar-container {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin: 2rem;
        }
        
        #calendar {
            height: 600px;
        }
        
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
        }
        
        .modal-content {
            background: white;
            margin: 5% auto;
            padding: 2rem;
            width: 90%;
            max-width: 500px;
            border-radius: 10px;
            position: relative;
        }
        
        .close {
            position: absolute;
            right: 1rem;
            top: 1rem;
            font-size: 1.5rem;
            cursor: pointer;
        }
        
        .form-group {
            margin-bottom: 1rem;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
        }
        
        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        
        .btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        
        .btn-danger {
            background: #f56565;
        }
        
        .event-details {
            padding: 1rem;
        }
        
        .event-details h3 {
            margin-bottom: 1rem;
        }
        
        .event-type {
            display: inline-block;
            padding: 0.25rem 0.5rem;
            border-radius: 3px;
            font-size: 0.85rem;
            margin: 0.5rem 0;
        }
        
        .type-academic { background: #4299e1; color: white; }
        .type-holiday { background: #f56565; color: white; }
        .type-exam { background: #ed8936; color: white; }
        .type-meeting { background: #9f7aea; color: white; }
        .type-other { background: #48bb78; color: white; }
    </style>
</head>
<body>
    <!-- Same sidebar as your other admin pages -->
    <div class="hamburger-btn" id="hamburger-btn">
        <i class="fas fa-bars"></i>
    </div>

    <nav class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <img src="../images/olshco.jpg" alt="Logo" class="sidebar-logo">
            <h3>NEXUS <span>Admin</span></h3>
        </div>
        <ul class="nav-links">
            <li>
                <a href="dashboard.php">
                    <i class="fas fa-chart-line"></i> <span>Dashboard</span>
                </a>
            </li>
            <li>
                <a href="add_students.php">
                    <i class="fas fa-user-plus"></i> <span>Add Student</span>
                </a>
            </li>
            <li>
                <a href="view_students.php">
                    <i class="fas fa-user-graduate"></i> <span>Students</span>
                </a>
            </li>
            <li>
                <a href="manage_students.php">
                    <i class="fas fa-users-cog"></i> <span>Manage Students</span>
                </a>
            </li>
            <li>
                <a href="subject_select_student.php">
                    <i class="fas fa-book"></i> <span>Subjects</span>
                </a>
            </li>
            <li>
                <a href="grade_select_student.php">
                    <i class="fas fa-graduation-cap"></i> <span>Grades</span>
                </a>
            </li>
            <li>
                <a href="announcement.php">
                    <i class="fas fa-bullhorn"></i> <span>Announcements</span>
                </a>
            </li>
            <!-- NEW: Calendar Link -->
            <li>
                <a href="event.php" class="active">
                    <i class="fas fa-calendar-alt"></i> <span>Calendar</span>
                </a>
            </li>
            <li class="logout">
                <a href="../logout.php">
                    <i class="fas fa-sign-out-alt"></i> <span>Logout</span>
                </a>
            </li>
        </ul>
    </nav>

    <div class="sidebar-overlay" id="sidebar-overlay"></div>

    <div class="main-wrapper">
        <div class="calendar-container">
            <!-- Add Event Button -->
            <div style="margin-bottom: 1rem; display: flex; justify-content: space-between; align-items: center;">
                <h2><i class="fas fa-calendar-alt"></i> Academic Calendar</h2>
                <button class="btn" onclick="openAddModal()">
                    <i class="fas fa-plus"></i> Add Event
                </button>
            </div>
            
            <!-- FullCalendar -->
            <div id="calendar"></div>
        </div>
    </div>

    <!-- Add Event Modal -->
    <div id="eventModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h3>Add New Event</h3>
            
            <?php if(isset($message)): ?>
                <div style="padding: 1rem; background: <?php echo $status == 'success' ? '#d4edda' : '#f8d7da'; ?>; color: <?php echo $status == 'success' ? '#155724' : '#721c24'; ?>; border-radius: 5px; margin-bottom: 1rem;">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" onsubmit="return validateForm()">
                <div class="form-group">
                    <label>Event Title</label>
                    <input type="text" name="title" required>
                </div>
                
                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" rows="3"></textarea>
                </div>
                
                <div class="form-group">
                    <label>Date</label>
                    <input type="date" name="event_date" id="eventDate" required>
                </div>
                
                <div class="form-group">
                    <label>Event Type</label>
                    <select name="event_type" required>
                        <option value="academic">Academic</option>
                        <option value="holiday">Holiday</option>
                        <option value="exam">Exam</option>
                        <option value="meeting">Meeting</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                
                <button type="submit" name="add_event" class="btn" style="width: 100%;">
                    <i class="fas fa-save"></i> Save Event
                </button>
            </form>
        </div>
    </div>

    <!-- View Event Modal -->
    <div id="viewEventModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeViewModal()">&times;</span>
            <div id="eventDetails" class="event-details"></div>
        </div>
    </div>

    <!-- FullCalendar JS -->
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
    
    <script>
        // Sidebar toggle
        const btn = document.getElementById('hamburger-btn');
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');

        function toggleSidebar() {
            sidebar.classList.toggle('active');
            overlay.classList.toggle('active');
        }

        if(btn) btn.addEventListener('click', toggleSidebar);
        if(overlay) overlay.addEventListener('click', toggleSidebar);

        // Calendar initialization
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                events: 'calendar_api.php',
                eventClick: function(info) {
                    showEventDetails(info.event);
                },
                dateClick: function(info) {
                    document.getElementById('eventDate').value = info.dateStr;
                    openAddModal();
                },
                height: 600,
                aspectRatio: 1.5,
                slotMinTime: '08:00:00',
                slotMaxTime: '20:00:00'
            });
            calendar.render();
        });

        // Modal functions
        function openAddModal() {
            document.getElementById('eventModal').style.display = 'block';
        }

        function closeModal() {
            document.getElementById('eventModal').style.display = 'none';
        }

        function closeViewModal() {
            document.getElementById('viewEventModal').style.display = 'none';
        }

        function showEventDetails(event) {
            const details = document.getElementById('eventDetails');
            const type = event.extendedProps.type || 'other';
            
            details.innerHTML = `
                <h3>${event.title}</h3>
                <div class="event-type type-${type}">${type.toUpperCase()}</div>
                <p><strong>Date:</strong> ${event.start.toDateString()}</p>
                <p><strong>Description:</strong><br>${event.extendedProps.description || 'No description'}</p>
                <div style="margin-top: 2rem; display: flex; gap: 1rem;">
                    <button class="btn" onclick="closeViewModal()">Close</button>
                    <button class="btn btn-danger" onclick="deleteEvent(${event.id})">Delete Event</button>
                </div>
            `;
            
            document.getElementById('viewEventModal').style.display = 'block';
        }

        function deleteEvent(id) {
            if(confirm('Are you sure you want to delete this event?')) {
                window.location.href = 'event.php?delete=' + id;
            }
        }

        function validateForm() {
            const date = document.getElementById('eventDate').value;
            if(!date) {
                alert('Please select a date');
                return false;
            }
            return true;
        }

        // Close modals when clicking outside
        window.onclick = function(event) {
            const modal1 = document.getElementById('eventModal');
            const modal2 = document.getElementById('viewEventModal');
            if (event.target == modal1) {
                modal1.style.display = 'none';
            }
            if (event.target == modal2) {
                modal2.style.display = 'none';
            }
        }
    </script>
</body>
</html>