<?php
session_start();
// Kung may session na, huwag nang pakitahin ang selection, diretso na sa home
if (isset($_SESSION['username']) && isset($_SESSION['role']) && $_SESSION['role'] === 'student') {
    header("Location: home.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Program Selection - NEXUS Student Portal</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary: #0a2540;
            --primary-light: #1a365d;
            --accent: #2563eb;
            --accent-light: #dbeafe;
            --gray-50: #f8fafc;
            --gray-100: #f1f5f9;
            --gray-200: #e2e8f0;
            --gray-300: #cbd5e1;
            --gray-400: #94a3b8;
            --gray-500: #64748b;
            --gray-600: #475569;
            --gray-700: #334155;
            --gray-800: #1e293b;
            --gray-900: #0f172a;
            --white: #ffffff;
            
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1);
            --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1);
            
            --radius-sm: 6px;
            --radius-md: 8px;
            --radius-lg: 12px;
            --radius-xl: 16px;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, var(--gray-50) 0%, var(--white) 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            color: var(--gray-800);
        }

        /* Professional Background Pattern */
        .bg-pattern {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
            opacity: 0.4;
            pointer-events: none;
        }

        .bg-pattern svg {
            position: absolute;
            width: 100%;
            height: 100%;
        }

        .bg-grid {
            position: absolute;
            width: 100%;
            height: 100%;
            background-image: 
                linear-gradient(var(--gray-200) 1px, transparent 1px),
                linear-gradient(90deg, var(--gray-200) 1px, transparent 1px);
            background-size: 50px 50px;
            opacity: 0.2;
        }

        .bg-gradient {
            position: absolute;
            top: -50%;
            right: -50%;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle, var(--accent-light) 0%, transparent 70%);
            opacity: 0.3;
            border-radius: 50%;
        }

        /* Main Container */
        .container {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 1000px;
            padding: 2rem;
            margin: 2rem;
        }

        /* Header */
        .header {
            text-align: center;
            margin-bottom: 3rem;
        }

        .logo {
            display: inline-flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .logo-icon {
            width: 48px;
            height: 48px;
            background: var(--primary);
            border-radius: var(--radius-md);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .logo-icon i {
            font-size: 24px;
            color: var(--white);
        }

        .logo-text {
            text-align: left;
        }

        .logo-text h1 {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--primary);
            letter-spacing: -0.5px;
        }

        .logo-text p {
            font-size: 0.75rem;
            color: var(--gray-500);
        }

        .header h2 {
            font-size: 2rem;
            font-weight: 600;
            color: var(--gray-800);
            margin-bottom: 0.5rem;
        }

        .header p {
            color: var(--gray-500);
            font-size: 0.95rem;
        }

        /* Step Indicator */
        .step-indicator {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .step {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .step-number {
            width: 32px;
            height: 32px;
            background: var(--gray-200);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 0.875rem;
            color: var(--gray-600);
            transition: all 0.3s;
        }

        .step.active .step-number {
            background: var(--accent);
            color: var(--white);
        }

        .step-label {
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--gray-600);
        }

        .step.active .step-label {
            color: var(--accent);
        }

        .step-line {
            width: 60px;
            height: 2px;
            background: var(--gray-200);
        }

        /* Cards Container */
        .card {
            background: var(--white);
            border-radius: var(--radius-xl);
            box-shadow: var(--shadow-xl);
            overflow: hidden;
            border: 1px solid var(--gray-200);
        }

        .card-header {
            padding: 1.5rem 2rem;
            border-bottom: 1px solid var(--gray-200);
            background: var(--gray-50);
        }

        .card-header h3 {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--gray-800);
        }

        .card-header p {
            font-size: 0.875rem;
            color: var(--gray-500);
            margin-top: 0.25rem;
        }

        .card-body {
            padding: 2rem;
        }

        /* Step 1 - Program Selection */
        .program-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
        }

        .program-card {
            background: var(--white);
            border: 1px solid var(--gray-200);
            border-radius: var(--radius-lg);
            padding: 1.5rem 1rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s ease;
            position: relative;
            overflow: hidden;
        }

        .program-card:hover {
            border-color: var(--accent);
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .program-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--accent), var(--primary));
            opacity: 0;
            transition: opacity 0.2s;
        }

        .program-card:hover::before {
            opacity: 1;
        }

        .program-icon {
            width: 56px;
            height: 56px;
            background: var(--accent-light);
            border-radius: var(--radius-md);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
        }

        .program-icon i {
            font-size: 28px;
            color: var(--accent);
        }

        .program-card h4 {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--gray-800);
            margin-bottom: 0.25rem;
        }

        .program-card p {
            font-size: 0.75rem;
            color: var(--gray-500);
            margin-bottom: 1rem;
        }

        .program-badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            background: var(--gray-100);
            border-radius: 50px;
            font-size: 0.7rem;
            font-weight: 500;
            color: var(--gray-600);
        }

        /* Step 2 - Details Form */
        .back-button {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 0;
            background: none;
            border: none;
            color: var(--gray-600);
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
            transition: color 0.2s;
            margin-bottom: 1.5rem;
        }

        .back-button:hover {
            color: var(--accent);
        }

        .back-button i {
            font-size: 0.875rem;
        }

        .selected-course {
            background: var(--accent-light);
            border-radius: var(--radius-lg);
            padding: 1rem 1.5rem;
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .selected-course-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .selected-course-icon {
            width: 40px;
            height: 40px;
            background: var(--white);
            border-radius: var(--radius-sm);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .selected-course-icon i {
            font-size: 20px;
            color: var(--accent);
        }

        .selected-course-text h5 {
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--gray-600);
            margin-bottom: 0.15rem;
        }

        .selected-course-text span {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--primary);
        }

        .selected-course-badge {
            padding: 0.4rem 1rem;
            background: var(--white);
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--accent);
            border: 1px solid var(--accent);
        }

        /* Form Styles */
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .form-group {
            width: 100%;
        }

        .form-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--gray-700);
            margin-bottom: 0.5rem;
        }

        .form-select {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid var(--gray-300);
            border-radius: var(--radius-md);
            font-size: 0.95rem;
            color: var(--gray-800);
            background: var(--white);
            cursor: pointer;
            transition: all 0.2s;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 1rem center;
            background-size: 16px;
        }

        .form-select:hover {
            border-color: var(--accent);
        }

        .form-select:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 3px var(--accent-light);
        }

        /* Proceed Button */
        .proceed-btn {
            width: 100%;
            padding: 1rem;
            background: var(--accent);
            color: var(--white);
            border: none;
            border-radius: var(--radius-md);
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .proceed-btn:hover {
            background: var(--primary);
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .proceed-btn i {
            font-size: 1rem;
            transition: transform 0.2s;
        }

        .proceed-btn:hover i {
            transform: translateX(4px);
        }

        /* Footer */
        .footer {
            text-align: center;
            margin-top: 2rem;
            color: var(--gray-500);
            font-size: 0.75rem;
        }

        .footer a {
            color: var(--gray-600);
            text-decoration: none;
            margin: 0 0.5rem;
        }

        .footer a:hover {
            color: var(--accent);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .container {
                padding: 1rem;
                margin: 1rem;
            }

            .program-grid {
                grid-template-columns: 1fr;
            }

            .form-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .step-line {
                width: 30px;
            }

            .step-label {
                display: none;
            }

            .header h2 {
                font-size: 1.5rem;
            }

            .card-body {
                padding: 1.5rem;
            }
        }

        @media (max-width: 480px) {
            .selected-course {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }

            .selected-course-badge {
                align-self: flex-start;
            }
        }

        /* Animation */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .step-visible {
            animation: fadeIn 0.3s ease forwards;
        }
    </style>
</head>
<body>
    <!-- Professional Background -->
    <div class="bg-pattern">
        <div class="bg-grid"></div>
        <div class="bg-gradient"></div>
    </div>

    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="logo">
                <div class="logo-icon">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <div class="logo-text">
                    <h1>NEXUS Portal</h1>
                    <p>STUDENT ACCESS</p>
                </div>
            </div>
            <h2>Program Selection</h2>
            <p>Choose your program to continue with the login process</p>
        </div>

        <!-- Step Indicator -->
        <div class="step-indicator">
            <div class="step active" id="step1Indicator">
                <div class="step-number">1</div>
                <div class="step-label">Program</div>
            </div>
            <div class="step-line"></div>
            <div class="step" id="step2Indicator">
                <div class="step-number">2</div>
                <div class="step-label">Details</div>
            </div>
        </div>

        <!-- Main Card -->
        <div class="card">
            <!-- Step 1: Program Selection -->
            <div id="step1">
                <div class="card-header">
                    <h3>Select Your Program</h3>
                    <p>Choose the program you are enrolled in</p>
                </div>
                <div class="card-body">
                    <div class="program-grid">
                        <!-- BSOAD -->
                        <div class="program-card" onclick="nextStep('BSOAD', 'Office Administration')">
                            <div class="program-icon">
                                <i class="fas fa-briefcase"></i>
                            </div>
                            <h4>BSOAD</h4>
                            <p>Office Administration</p>
                            <span class="program-badge">4 Years</span>
                        </div>

                        <!-- BSHM -->
                        <div class="program-card" onclick="nextStep('BSHM', 'Hospitality Management')">
                            <div class="program-icon">
                                <i class="fas fa-utensils"></i>
                            </div>
                            <h4>BSHM</h4>
                            <p>Hospitality Management</p>
                            <span class="program-badge">4 Years</span>
                        </div>

                        <!-- BSCRIM -->
                        <div class="program-card" onclick="nextStep('BSCRIM', 'Criminology')">
                            <div class="program-icon">
                                <i class="fas fa-shield-alt"></i>
                            </div>
                            <h4>BSCRIM</h4>
                            <p>Criminology</p>
                            <span class="program-badge">4 Years</span>
                        </div>

                        <!-- BSEDUC -->
                        <div class="program-card" onclick="nextStep('BSEDUC', 'Education')">
                            <div class="program-icon">
                                <i class="fas fa-chalkboard-teacher"></i>
                            </div>
                            <h4>BSEDUC</h4>
                            <p>Education</p>
                            <span class="program-badge">4 Years</span>
                        </div>

                        <!-- BSIT -->
                        <div class="program-card" onclick="nextStep('BSIT', 'Information Technology')">
                            <div class="program-icon">
                                <i class="fas fa-laptop-code"></i>
                            </div>
                            <h4>BSIT</h4>
                            <p>Information Technology</p>
                            <span class="program-badge">4 Years</span>
                        </div>

                        <!-- Add more if needed -->
                        <div class="program-card" onclick="nextStep('BSBA', 'Business Administration')">
                            <div class="program-icon">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <h4>BSBA</h4>
                            <p>Business Administration</p>
                            <span class="program-badge">4 Years</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 2: Details Form -->
            <div id="step2" style="display: none;">
                <div class="card-header">
                    <button class="back-button" onclick="prevStep()">
                        <i class="fas fa-arrow-left"></i>
                        Back to Programs
                    </button>
                </div>
                <div class="card-body">
                    <!-- Selected Course Display -->
                    <div class="selected-course" id="selectedCourseDisplay">
                        <div class="selected-course-info">
                            <div class="selected-course-icon">
                                <i class="fas fa-book-open" id="selectedCourseIcon"></i>
                            </div>
                            <div class="selected-course-text">
                                <h5>Selected Program</h5>
                                <span id="displayCourseCode">BSIT</span> - <span id="displayCourseName">Information Technology</span>
                            </div>
                        </div>
                        <div class="selected-course-badge" id="displayCourseBadge">
                            BSOAD
                        </div>
                    </div>

                    <!-- Form -->
                    <form action="login.php" method="GET" id="programForm">
                        <input type="hidden" name="course" id="courseInput">

                        <div class="form-grid">
                            <div class="form-group">
                                <label class="form-label" for="yearLevel">
                                    <i class="fas fa-calendar-alt" style="margin-right: 0.5rem; color: var(--accent);"></i>
                                    Year Level
                                </label>
                                <select class="form-select" id="yearLevel" name="year" required>
                                    <option value="" disabled selected>-- Select Year Level --</option>
                                    <option value="1">1st Year - Freshman</option>
                                    <option value="2">2nd Year - Sophomore</option>
                                    <option value="3">3rd Year - Junior</option>
                                    <option value="4">4th Year - Senior</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="blockSection">
                                    <i class="fas fa-layer-group" style="margin-right: 0.5rem; color: var(--accent);"></i>
                                    Block / Section
                                </label>
                                <select class="form-select" id="blockSection" name="block" required>
                                    <option value="" disabled selected>-- Select Block --</option>
                                    <option value="A">Block A - Morning</option>
                                    <option value="B">Block B - Morning</option>
                                    <option value="C">Block C - Afternoon</option>
                                    <option value="D">Block D - Afternoon</option>
                                    <option value="E">Block E - Evening</option>
                                </select>
                            </div>
                        </div>

                        <!-- Additional Info (Optional) -->
                        <div style="background: var(--gray-50); border-radius: var(--radius-lg); padding: 1rem; margin-bottom: 2rem; border: 1px solid var(--gray-200);">
                            <div style="display: flex; align-items: center; gap: 0.5rem; color: var(--gray-600); font-size: 0.85rem;">
                                <i class="fas fa-info-circle" style="color: var(--accent);"></i>
                                <span>Please ensure that your program and year level are correct before proceeding.</span>
                            </div>
                        </div>

                        <button type="submit" class="proceed-btn">
                            <span>Proceed to Login</span>
                            <i class="fas fa-arrow-right"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>
                <a href="#">Help</a> • 
                <a href="#">Privacy</a> • 
                <a href="#">Contact Registrar</a>
            </p>
            <p style="margin-top: 0.5rem;">© <?php echo date('Y'); ?> Our Lady of Sacred Heart College. All rights reserved.</p>
        </div>
    </div>

    <script>
        let selectedCourseCode = '';
        let selectedCourseName = '';

        function nextStep(courseCode, courseName) {
            // Update selected course
            selectedCourseCode = courseCode;
            selectedCourseName = courseName;
            
            // Update hidden input
            document.getElementById('courseInput').value = courseCode;
            
            // Update display elements
            document.getElementById('displayCourseCode').innerText = courseCode;
            document.getElementById('displayCourseName').innerText = courseName;
            document.getElementById('displayCourseBadge').innerText = courseCode;
            
            // Update icon based on course
            const icon = document.getElementById('selectedCourseIcon');
            switch(courseCode) {
                case 'BSOAD':
                    icon.className = 'fas fa-briefcase';
                    break;
                case 'BSHM':
                    icon.className = 'fas fa-utensils';
                    break;
                case 'BSCRIM':
                    icon.className = 'fas fa-shield-alt';
                    break;
                case 'BSEDUC':
                    icon.className = 'fas fa-chalkboard-teacher';
                    break;
                case 'BSIT':
                    icon.className = 'fas fa-laptop-code';
                    break;
                case 'BSBA':
                    icon.className = 'fas fa-chart-line';
                    break;
                default:
                    icon.className = 'fas fa-book-open';
            }
            
            // Update step indicators
            document.getElementById('step1Indicator').classList.remove('active');
            document.getElementById('step2Indicator').classList.add('active');
            
            // Show/hide steps with animation
            document.getElementById('step1').style.display = 'none';
            document.getElementById('step2').style.display = 'block';
            document.getElementById('step2').classList.add('step-visible');
        }

        function prevStep() {
            // Update step indicators
            document.getElementById('step1Indicator').classList.add('active');
            document.getElementById('step2Indicator').classList.remove('active');
            
            // Show/hide steps
            document.getElementById('step1').style.display = 'block';
            document.getElementById('step2').style.display = 'none';
        }

        // Form validation
        document.getElementById('programForm').addEventListener('submit', function(e) {
            const year = document.getElementById('yearLevel').value;
            const block = document.getElementById('blockSection').value;
            
            if (!year || !block) {
                e.preventDefault();
                alert('Please select both year level and block section.');
            }
        });

        // Add keyboard navigation
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && document.getElementById('step2').style.display === 'block') {
                prevStep();
            }
        });
    </script>
</body>
</html>