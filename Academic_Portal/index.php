<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Athena · academic core | OLSHCO</title>
    <!-- Professional type: Satoshi (via fontshare) + Inter fallback -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://api.fontshare.com/v2/css?f[]=satoshi@300,400,500,600,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --white: #ffffff;
            --black: #0b0c0e;
            
            /* refined scholarly palette */
            --navy-50: #f2f4f9;
            --navy-100: #e2e6f0;
            --navy-200: #c2cade;
            --navy-300: #9aa8c4;
            --navy-400: #6e80a3;
            --navy-500: #4d5f84;
            --navy-600: #2f3e60;
            --navy-700: #1f2b45;   /* primary deep navy */
            --navy-800: #141e33;
            --navy-900: #0c1322;

            --accent-50: #f1f7ff;
            --accent-100: #cde3ff;
            --accent-200: #9bc6ff;
            --accent-300: #569ef2;  /* clean blue */
            --accent-400: #2b7ad6;
            --accent-500: #1e5aab;
            
            --gold-300: #e3c992;
            --gold-400: #cdac6b;
            --gold-500: #b48b42;    /* classic school gold */
            --gold-600: #8e6b31;

            --gray-50: #f7f8fa;
            --gray-100: #f0f2f5;
            --gray-200: #e1e5ec;
            --gray-300: #cbd0da;
            --gray-400: #a3aab8;
            --gray-500: #7c8595;
            --gray-600: #5c6472;
            --gray-700: #3f4652;
            --gray-800: #2a2f39;
            --gray-900: #1a1e26;

            --radius-xs: 4px;
            --radius-sm: 8px;
            --radius-md: 12px;
            --radius-lg: 20px;
            --radius-xl: 28px;
            --radius-full: 999px;

            --shadow-xs: 0 1px 3px rgba(0,0,0,0.03);
            --shadow-sm: 0 4px 8px -2px rgba(0,0,0,0.05), 0 2px 4px -1px rgba(0,0,0,0.02);
            --shadow-md: 0 12px 24px -10px rgba(20,30,50,0.12), 0 4px 8px -2px rgba(0,0,0,0.02);
            --shadow-lg: 0 24px 44px -14px rgba(20,30,50,0.18), 0 8px 16px -4px rgba(0,0,0,0.04);
            --shadow-gold: 0 12px 28px -12px rgba(180, 139, 66, 0.3);
            
            --transition: all 0.25s cubic-bezier(0.2, 0, 0, 1);
        }

        body {
            font-family: 'Satoshi', 'Inter', system-ui, -apple-system, sans-serif;
            background-color: var(--gray-50);
            color: var(--gray-800);
            line-height: 1.5;
            -webkit-font-smoothing: antialiased;
        }

        .layout {
            max-width: 1320px;
            margin: 0 auto;
            padding: 0 28px;
        }

        /* ----- utility ----- */
        .overline {
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            color: var(--gold-500);
            margin-bottom: 0.5rem;
            display: block;
        }

        .section-title {
            font-size: 2.2rem;
            font-weight: 650;
            line-height: 1.2;
            letter-spacing: -0.02em;
            color: var(--navy-800);
        }

        .section-desc {
            font-size: 1rem;
            color: var(--gray-600);
            max-width: 580px;
            margin-top: 0.5rem;
        }

        /* ----- top bar (subtle) ----- */
        .top-bar {
            background: var(--navy-800);
            color: rgba(255,255,255,0.8);
            padding: 0.5rem 0;
            font-size: 0.8rem;
            border-bottom: 1px solid rgba(255,255,255,0.03);
        }

        .top-flex {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .info-badge {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .info-badge i {
            color: var(--gold-400);
            font-size: 0.6rem;
        }

        .contact-row {
            display: flex;
            gap: 1.5rem;
        }

        .contact-row span {
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }

        .contact-row i {
            color: var(--gold-400);
            font-size: 0.7rem;
        }

        /* ----- header / main navigation ----- */
        .header {
            background: rgba(255,255,255,0.92);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--gray-200);
            position: sticky;
            top: 0;
            z-index: 50;
        }

        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.9rem 0;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 0.9rem;
        }

        .brand-symbol {
            width: 46px;
            height: 46px;
            background: linear-gradient(135deg, var(--navy-700), var(--navy-900));
            border-radius: var(--radius-sm);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--white);
            font-size: 1.8rem;
            box-shadow: var(--shadow-sm);
        }

        .brand-text h1 {
            font-size: 1.6rem;
            font-weight: 700;
            letter-spacing: -0.03em;
            color: var(--navy-800);
            line-height: 1.1;
        }

        .brand-text p {
            font-size: 0.68rem;
            font-weight: 500;
            color: var(--gray-500);
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .nav-links {
            display: flex;
            gap: 1rem;
        }

        .nav-item {
            padding: 0.5rem 1.2rem;
            border-radius: var(--radius-full);
            font-size: 0.9rem;
            font-weight: 550;
            color: var(--gray-600);
            text-decoration: none;
            transition: var(--transition);
        }

        .nav-item:hover {
            background: var(--gray-100);
            color: var(--navy-700);
        }

        .nav-item.active {
            background: var(--navy-700);
            color: white;
        }

        /* ----- hero (very professional) ----- */
        .hero {
            margin: 2.5rem 0 4rem;
        }

        .hero-card {
            background: linear-gradient(125deg, var(--navy-800) 10%, #233150 100%);
            border-radius: var(--radius-xl);
            padding: 3.5rem 3.2rem;
            color: white;
            position: relative;
            isolation: isolate;
            box-shadow: var(--shadow-lg);
        }

        .hero-card::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at 100% 0%, rgba(255,255,240,0.08) 0%, transparent 50%);
            z-index: -1;
        }

        .hero-tagline {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: rgba(255,255,255,0.06);
            border: 1px solid rgba(255,255,255,0.08);
            padding: 0.3rem 1rem;
            border-radius: var(--radius-full);
            font-size: 0.7rem;
            font-weight: 500;
            margin-bottom: 2rem;
            color: var(--gold-300);
        }

        .hero-tagline i {
            color: var(--gold-400);
        }

        .hero-card h2 {
            font-size: 3.2rem;
            font-weight: 700;
            line-height: 1.1;
            letter-spacing: -0.03em;
            max-width: 700px;
            margin-bottom: 1rem;
        }

        .hero-card p {
            font-size: 1rem;
            opacity: 0.8;
            max-width: 550px;
            margin-bottom: 2.5rem;
        }

        .hero-stats {
            display: flex;
            gap: 3rem;
        }

        .stat {
            display: flex;
            flex-direction: column;
        }

        .stat-num {
            font-size: 2rem;
            font-weight: 650;
        }

        .stat-label {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            opacity: 0.6;
        }

        /* ----- portal cards (clean / elevated) ----- */
        .portal-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 2rem;
            margin: 3rem 0 4rem;
        }

        .portal-card {
            background: var(--white);
            border-radius: var(--radius-lg);
            padding: 2.4rem 2rem;
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--gray-200);
            text-decoration: none;
            color: inherit;
            transition: var(--transition);
            position: relative;
        }

        .portal-card:hover {
            transform: translateY(-6px);
            border-color: var(--gold-400);
            box-shadow: var(--shadow-md), var(--shadow-gold);
        }

        .portal-icon {
            width: 70px;
            height: 70px;
            background: var(--navy-50);
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.8rem;
        }

        .portal-icon i {
            font-size: 2.4rem;
            color: var(--navy-700);
        }

        .portal-card h3 {
            font-size: 1.7rem;
            font-weight: 650;
            color: var(--navy-800);
            margin-bottom: 0.5rem;
        }

        .portal-card p {
            color: var(--gray-600);
            font-size: 0.9rem;
            margin-bottom: 2rem;
        }

        .portal-arrow {
            display: flex;
            align-items: center;
            gap: 0.4rem;
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--gold-500);
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .portal-arrow i {
            transition: transform 0.15s;
        }

        .portal-card:hover .portal-arrow i {
            transform: translateX(4px);
        }

        /* ----- features grid (8 items, symmetrical) ----- */
        .features-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1.4rem;
            margin: 3rem 0 4rem;
        }

        .feature-item {
            background: var(--white);
            border-radius: var(--radius-md);
            padding: 1.8rem 1.2rem;
            border: 1px solid var(--gray-200);
            transition: var(--transition);
            text-align: center;
        }

        .feature-item:hover {
            border-color: var(--gold-400);
            background: var(--navy-50);
        }

        .feature-item i {
            font-size: 2.2rem;
            color: var(--navy-600);
            margin-bottom: 1rem;
        }

        .feature-item h4 {
            font-weight: 620;
            font-size: 1rem;
            color: var(--navy-800);
            margin-bottom: 0.2rem;
        }

        .feature-item p {
            font-size: 0.75rem;
            color: var(--gray-500);
        }

        /* ----- quick actions panel (minimal) ----- */
        .quick-panel {
            background: var(--white);
            border-radius: var(--radius-lg);
            padding: 1.4rem 2rem;
            border: 1px solid var(--gray-200);
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 4rem;
            box-shadow: var(--shadow-xs);
        }

        .quick-panel h4 {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            font-weight: 600;
            color: var(--navy-700);
            font-size: 1rem;
        }

        .quick-links {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .quick-link {
            padding: 0.4rem 1rem;
            border-radius: var(--radius-full);
            background: var(--gray-100);
            border: 1px solid var(--gray-200);
            font-size: 0.8rem;
            color: var(--gray-700);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            transition: var(--transition);
        }

        .quick-link i {
            color: var(--gold-500);
            font-size: 0.7rem;
        }

        .quick-link:hover {
            background: var(--navy-700);
            border-color: var(--navy-700);
            color: white;
        }
        .quick-link:hover i {
            color: white;
        }

        /* ----- information split (latest / campus) ----- */
        .info-split {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
            margin-bottom: 4rem;
        }

        .info-card {
            background: var(--white);
            border-radius: var(--radius-lg);
            padding: 2rem;
            border: 1px solid var(--gray-200);
            box-shadow: var(--shadow-sm);
        }

        .info-card.dark-card {
            background: linear-gradient(135deg, var(--navy-800), #1f293d);
            color: white;
            border: none;
        }

        .info-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .info-icon {
            width: 48px;
            height: 48px;
            background: var(--navy-50);
            border-radius: var(--radius-sm);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .dark-card .info-icon {
            background: rgba(255,255,255,0.08);
        }
        .info-icon i {
            font-size: 1.5rem;
            color: var(--gold-500);
        }

        .info-header h4 {
            font-size: 1.2rem;
            font-weight: 650;
        }

        .info-list {
            list-style: none;
        }
        .info-list li {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 0.7rem 0;
            border-bottom: 1px solid var(--gray-200);
            font-size: 0.9rem;
        }
        .dark-card .info-list li {
            border-bottom-color: rgba(255,255,255,0.1);
        }
        .info-list li:last-child {
            border-bottom: none;
        }
        .info-list i {
            color: var(--gold-500);
            font-size: 0.8rem;
            width: 1.2rem;
        }

        /* ----- footer (clean and institutional) ----- */
        .footer {
            background: var(--white);
            border-top: 1px solid var(--gray-200);
            padding: 3rem 0 2rem;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr;
            gap: 3rem;
            margin-bottom: 2.5rem;
        }

        .footer-about p {
            font-size: 0.8rem;
            color: var(--gray-600);
            margin-top: 1rem;
            line-height: 1.6;
        }

        .footer-links h5 {
            font-size: 0.8rem;
            font-weight: 650;
            color: var(--navy-800);
            margin-bottom: 1rem;
            text-transform: uppercase;
            letter-spacing: 0.4px;
        }

        .footer-links a {
            display: block;
            color: var(--gray-500);
            text-decoration: none;
            font-size: 0.8rem;
            margin-bottom: 0.5rem;
            transition: color 0.15s;
        }
        .footer-links a:hover {
            color: var(--gold-500);
        }

        .footer-bottom {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 2rem;
            border-top: 1px solid var(--gray-200);
            font-size: 0.75rem;
            color: var(--gray-500);
        }

        .social {
            display: flex;
            gap: 1rem;
        }
        .social a {
            color: var(--gray-400);
            transition: color 0.15s;
        }
        .social a:hover {
            color: var(--gold-500);
        }

        /* ----- session toast refined ----- */
        .session-toast {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            background: var(--white);
            border-radius: 60px;
            padding: 0.7rem 1.5rem 0.7rem 1rem;
            box-shadow: var(--shadow-lg), 0 0 0 1px rgba(180, 139, 66, 0.2);
            z-index: 200;
            animation: toast-in 0.25s ease;
        }
        @keyframes toast-in {
            0% { opacity: 0; transform: translateY(20px); }
            100% { opacity: 1; transform: translateY(0); }
        }
        .toast-row {
            display: flex;
            align-items: center;
            gap: 0.8rem;
        }
        .toast-check {
            width: 28px;
            height: 28px;
            background: var(--navy-50);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .toast-check i {
            color: var(--navy-700);
            font-size: 0.8rem;
        }
        .toast-msg {
            font-size: 0.85rem;
        }
        .toast-msg strong {
            color: var(--navy-800);
        }
        .toast-msg span {
            color: var(--gray-500);
            font-size: 0.7rem;
        }

        /* responsive */
        @media (max-width: 1100px) {
            .portal-grid { grid-template-columns: repeat(2, 1fr); }
            .features-grid { grid-template-columns: repeat(2, 1fr); }
            .footer-grid { grid-template-columns: 1fr 1fr; }
        }
        @media (max-width: 750px) {
            .portal-grid, .info-split, .features-grid { grid-template-columns: 1fr; }
            .top-flex { flex-direction: column; align-items: start; gap: 0.3rem; }
            .header-container { flex-direction: column; gap: 1rem; }
            .hero-card h2 { font-size: 2.3rem; }
            .quick-panel { flex-direction: column; align-items: start; gap: 1rem; }
        }
    </style>
</head>
<body>
    <!-- top bar (very subtle) -->
    <div class="top-bar">
        <div class="layout top-flex">
            <div class="info-badge">
                <i class="fas fa-circle"></i>
                <span>PAASCU ACCREDITED · ACADEMIC YEAR 2024–2025</span>
            </div>
            <div class="contact-row">
                <span><i class="fas fa-phone-alt"></i> +63 2 8532 1001</span>
                <span><i class="fas fa-envelope"></i> registrar@olshco.edu.ph</span>
            </div>
        </div>
    </div>

    <!-- header -->
    <header class="header">
        <div class="layout header-container">
            <div class="brand">
                <div class="brand-symbol">
                    <i class="fas fa-scroll"></i>
                </div>
                <div class="brand-text">
                    <h1>NEXUS</h1>
                    <p>Our Lady of Sacred Heart College</p>
                </div>
            </div>
            <nav class="nav-links">
                <a href="#" class="nav-item active">Home</a>
                <a href="#" class="nav-item">Calendar</a>
                <a href="#" class="nav-item">Bulletin</a>
                <a href="#" class="nav-item">Directory</a>
            </nav>
        </div>
    </header>

    <main class="layout">
        <!-- hero section -->
        <section class="hero">
            <div class="hero-card">
                <div class="hero-tagline">
                    <i class="fas fa-certificate"></i>   founded 1965 · manila
                </div>
                <h2>Integrated academic environment <br>for excellence & integrity</h2>
                <p>One platform for grades, records, faculty collaboration, and student progress — designed around the way institutions work.</p>
                <div class="hero-stats">
                    <div class="stat"><span class="stat-num">2,740</span><span class="stat-label">students</span></div>
                    <div class="stat"><span class="stat-num">168</span><span class="stat-label">faculty</span></div>
                    <div class="stat"><span class="stat-num">96%</span><span class="stat-label">retention</span></div>
                </div>
            </div>
        </section>

        <!-- portal cards (three roles) -->
        <section>
            <div class="section-title" style="margin-bottom: 0.4rem;">Role-based portals</div>
            <div class="section-desc" style="margin-bottom: 2rem;">Secure access tailored to your function</div>

            <div class="portal-grid">
                <a href="secret_admin/index.php" class="portal-card">
                    <div class="portal-icon"><i class="fas fa-shield-halded"></i></div>
                    <h3>Admin</h3>
                    <p>System oversight, user provisioning, analytics & configuration.</p>
                    <div class="portal-arrow"><span>enter</span> <i class="fas fa-arrow-right"></i></div>
                </a>
                <a href="faculty/index.php" class="portal-card">
                    <div class="portal-icon"><i class="fas fa-chalkboard-user"></i></div>
                    <h3>Faculty</h3>
                    <p>Class lists, grade submission, attendance & student insights.</p>
                    <div class="portal-arrow"><span>enter</span> <i class="fas fa-arrow-right"></i></div>
                </a>
                <a href="students/pre-login.php" class="portal-card">
                    <div class="portal-icon"><i class="fas fa-user-graduate"></i></div>
                    <h3>Student</h3>
                    <p>Grades, assignments, announcements & academic records.</p>
                    <div class="portal-arrow"><span>enter</span> <i class="fas fa-arrow-right"></i></div>
                </a>
            </div>
        </section>

        <!-- features (8 pillars) -->
        <section>
            <span class="overline">core capabilities</span>
            <div class="section-title">Everything a modern school needs</div>
            <div class="features-grid">
                <div class="feature-item"><i class="fas fa-id-card"></i><h4>student records</h4><p>centralised profiles</p></div>
                <div class="feature-item"><i class="fas fa-layer-group"></i><h4>course management</h4><p>subjects & sections</p></div>
                <div class="feature-item"><i class="fas fa-file-pen"></i><h4>grade encoding</h4><p>secure & efficient</p></div>
                <div class="feature-item"><i class="fas fa-calendar-week"></i><h4>academic calendar</h4><p>key milestones</p></div>
                <div class="feature-item"><i class="fas fa-chart-pie"></i><h4>analytics</h4><p>trends & reports</p></div>
                <div class="feature-item"><i class="fas fa-bullhorn"></i><h4>announcements</h4><p>real‑time</p></div>
                <div class="feature-item"><i class="fas fa-lock"></i><h4>RBAC</h4><p>granular security</p></div>
                <div class="feature-item"><i class="fas fa-mobile-alt"></i><h4>responsive</h4><p>any device</p></div>
            </div>
        </section>

        <!-- quick links panel (actions) -->
        <div class="quick-panel">
            <h4><i class="fas fa-bolt" style="color: var(--gold-500);"></i> quick actions</h4>
            <div class="quick-links">
                <a href="secret_admin/dashboard.php" class="quick-link"><i class="fas fa-tachometer-alt"></i> admin dash</a>
                <a href="students/dashboard.php" class="quick-link"><i class="fas fa-columns"></i> student dash</a>
                <a href="secret_admin/add_students.php" class="quick-link"><i class="fas fa-user-plus"></i> add student</a>
                <a href="secret_admin/announcement.php" class="quick-link"><i class="fas fa-bullhorn"></i> announcement</a>
                <a href="secret_admin/view_students.php" class="quick-link"><i class="fas fa-users"></i> student list</a>
                <a href="#" class="quick-link"><i class="fas fa-file-pdf"></i> reports</a>
            </div>
        </div>

        <!-- info split (latest updates + campus) -->
        <div class="info-split">
            <div class="info-card">
                <div class="info-header">
                    <div class="info-icon"><i class="fas fa-newspaper"></i></div>
                    <h4>latest updates</h4>
                </div>
                <ul class="info-list">
                    <li><i class="fas fa-circle"></i> 2nd sem enrollment ongoing</li>
                    <li><i class="fas fa-circle"></i> 1st semester grades published</li>
                    <li><i class="fas fa-circle"></i> Foundation day 2025 – Dec 15</li>
                    <li><i class="fas fa-circle"></i> Scholarship apps until Jan 30</li>
                </ul>
            </div>
            <div class="info-card dark-card">
                <div class="info-header">
                    <div class="info-icon"><i class="fas fa-map-pin"></i></div>
                    <h4>campus & contact</h4>
                </div>
                <ul class="info-list">
                    <li><i class="fas fa-location-dot"></i> 1238 Recto Ave, Sampaloc, Manila</li>
                    <li><i class="fas fa-phone"></i> (02) 8532‑1001 | +63 917 123 4567</li>
                    <li><i class="fas fa-clock"></i> Mon–Fri 8:00–17:00, Sat 8:00–12:00</li>
                    <li><i class="fas fa-envelope"></i> registrar@olshco.edu.ph</li>
                </ul>
            </div>
        </div>
    </main>

    <!-- footer -->
    <footer class="footer">
        <div class="layout">
            <div class="footer-grid">
                <div class="footer-about">
                    <div class="brand" style="margin-bottom:0;">
                        <div class="brand-symbol" style="width:40px; height:40px;"><i class="fas fa-scroll" style="font-size:1.2rem;"></i></div>
                        <div class="brand-text"><h2 style="font-size:1.3rem;">ATHENA</h2></div>
                    </div>
                    <p>Academic core system of Our Lady of Sacred Heart College.  Rigorous, secure, human‑centric.</p>
                </div>
                <div class="footer-links">
                    <h5>institution</h5>
                    <a href="#">about OLSHCO</a>
                    <a href="#">academics</a>
                    <a href="#">admissions</a>
                    <a href="#">offices</a>
                </div>
                <div class="footer-links">
                    <h5>support</h5>
                    <a href="#">help desk</a>
                    <a href="#">IT service</a>
                    <a href="#">registrar</a>
                    <a href="#">accounting</a>
                </div>
                <div class="footer-links">
                    <h5>policies</h5>
                    <a href="#">privacy</a>
                    <a href="#">terms</a>
                    <a href="#">data privacy</a>
                    <a href="#">student handbook</a>
                </div>
            </div>
            <div class="footer-bottom">
                <span><i class="fas fa-copyright"></i> 2025 Our Lady of Sacred Heart College. all rights reserved</span>
                <div class="social">
                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                    <a href="#"><i class="fab fa-x-twitter"></i></a>
                    <a href="#"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>
        </div>
    </footer>

    <!-- session toast (professional) -->
    <?php if(isset($_SESSION['username'])): ?>
    <div class="session-toast" id="sessionToast">
        <div class="toast-row">
            <div class="toast-check"><i class="fas fa-check"></i></div>
            <div class="toast-msg">
                <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong> · <?php echo ucfirst($_SESSION['role']); ?><br>
                <span>last login: today</span>
            </div>
        </div>
    </div>
    <script>
        (function(){
            const toast = document.getElementById('sessionToast');
            if(toast) {
                setTimeout(() => {
                    toast.style.transition = 'opacity 0.3s, transform 0.3s';
                    toast.style.opacity = '0';
                    toast.style.transform = 'translateY(10px)';
                    setTimeout(() => toast.remove(), 350);
                }, 4000);
                setTimeout(() => {
                    if(confirm('Return to your dashboard?')) {
                        window.location.href = '<?php echo $_SESSION['role'] === "admin" ? "secret_admin/dashboard.php" : "students/dashboard.php"; ?>';
                    }
                }, 1300);
            }
        })();
    </script>
    <?php endif; ?>
</body>
</html>