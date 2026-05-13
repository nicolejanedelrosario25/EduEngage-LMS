<?php
session_start();

if (!isset($_SESSION['student_name'])) {
    header("Location: index.php");
    exit();
}

$name = $_SESSION['student_name'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard - EduEngage LMS</title>
    <link rel="stylesheet" href="style.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head>

<body class="course-ui-body">

<div class="course-ui-wrapper">

    <aside class="course-ui-sidebar">

        <div class="course-ui-logo">
            <div class="course-logo-icon">
                <i class="fa-solid fa-graduation-cap"></i>
            </div>

            <h2>EduEngage</h2>
            <p>LMS Prototype</p>
        </div>

        <nav class="course-ui-menu">
            <a class="active" href="dashboard.php"><i class="fa-solid fa-table-columns"></i> Dashboard</a>
            <a href="courses.php"><i class="fa-solid fa-book"></i> Courses</a>
            <a href="assignments.php"><i class="fa-solid fa-clipboard-list"></i> Assignments</a>
            <a href="discussion.php"><i class="fa-regular fa-comments"></i> Discussion</a>
            <a href="progress.php"><i class="fa-solid fa-chart-line"></i> Progress</a>
            <a href="calendar.php"><i class="fa-regular fa-calendar"></i> Calendar</a>
            <a href="messages.php"><i class="fa-regular fa-envelope"></i> Messages</a>
        </nav>

        <div class="course-ui-bottom">
            <a href="#"><i class="fa-regular fa-circle-question"></i> Help</a>
            <a href="logout.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
        </div>

    </aside>

    <main class="course-ui-main">

        <header class="course-ui-topbar">

            <div>
                <h1>Welcome back, <?php echo htmlspecialchars($name); ?> 👋</h1>
                <p>Here's what's happening with your learning today.</p>
            </div>

            <div class="course-ui-actions">
                <button><i class="fa-regular fa-bell"></i></button>
                <button><i class="fa-solid fa-magnifying-glass"></i></button>

                <div class="course-ui-profile">
                    <div class="profile-avatar">
                        <i class="fa-solid fa-user"></i>
                    </div>

                    <div>
                        <h4><?php echo htmlspecialchars($name); ?></h4>
                        <p>Student</p>
                    </div>

                    <i class="fa-solid fa-caret-down"></i>
                </div>
            </div>

        </header>

        <section class="dashboard-soft-grid">

            <div class="dash-mini-card">
                <div class="round-icon green-round">
                    <i class="fa-solid fa-book-open"></i>
                </div>

                <div>
                    <p>Courses</p>
                    <h2>5 Active Courses</h2>
                </div>
            </div>

            <div class="dash-mini-card">
                <div class="round-icon orange-round">
                    <i class="fa-solid fa-clipboard-list"></i>
                </div>

                <div>
                    <p>Assignments</p>
                    <h2>3 Pending Tasks</h2>
                </div>
            </div>

            <div class="dash-mini-card">
                <div class="round-icon blue-round">
                    <i class="fa-regular fa-comments"></i>
                </div>

                <div>
                    <p>Discussion Posts</p>
                    <h2>12 Participations</h2>
                </div>
            </div>

            <div class="dash-mini-card">
                <div class="round-icon purple-round">
                    <i class="fa-solid fa-chart-line"></i>
                </div>

                <div>
                    <p>Progress</p>
                    <h2>78% Completed</h2>
                </div>
            </div>

        </section>

        <section class="dashboard-main-grid">

            <div class="dashboard-course-panel">

                <div class="dash-panel-header">
                    <h2>My Courses</h2>
                    <a href="courses.php">View all</a>
                </div>

                <div class="dash-course-item">
                    <div class="round-icon orange-round">
                        <i class="fa-solid fa-code"></i>
                    </div>

                    <div class="dash-course-info">
                        <h3>Web Application Development</h3>
                        <p>78% Completed · 12 / 15 Modules</p>

                        <div class="course-progress-line">
                            <div style="width:78%"></div>
                        </div>
                    </div>

                    <span>78%</span>
                </div>

                <div class="dash-course-item">
                    <div class="round-icon purple-round">
                        <i class="fa-solid fa-server"></i>
                    </div>

                    <div class="dash-course-info">
                        <h3>Systems Integration</h3>
                        <p>65% Completed · 9 / 14 Modules</p>

                        <div class="course-progress-line">
                            <div style="width:65%"></div>
                        </div>
                    </div>

                    <span>65%</span>
                </div>

                <div class="dash-course-item">
                    <div class="round-icon blue-round">
                        <i class="fa-solid fa-globe"></i>
                    </div>

                    <div class="dash-course-info">
                        <h3>Web Systems</h3>
                        <p>90% Completed · 18 / 20 Modules</p>

                        <div class="course-progress-line">
                            <div style="width:90%"></div>
                        </div>
                    </div>

                    <span>90%</span>
                </div>

            </div>

            <div class="dashboard-side-panel">

                <h2>Student Activity</h2>

                <div class="dash-activity-item">
                    <div class="activity-dot-icon green-bg">
                        <i class="fa-solid fa-right-to-bracket"></i>
                    </div>

                    <div>
                        <h4>Logged in today</h4>
                        <p>Time spent today: 3 hrs</p>
                    </div>
                </div>

                <div class="dash-activity-item">
                    <div class="activity-dot-icon yellow-bg">
                        <i class="fa-solid fa-file-circle-check"></i>
                    </div>

                    <div>
                        <h4>Submitted 2 assignments</h4>
                        <p>Assignment monitoring</p>
                    </div>
                </div>

                <div class="dash-activity-item">
                    <div class="activity-dot-icon blue-bg">
                        <i class="fa-solid fa-comment-dots"></i>
                    </div>

                    <div>
                        <h4>Participated in discussion</h4>
                        <p>Learning interaction</p>
                    </div>
                </div>

                <hr>

                <h2>Announcements</h2>

                <div class="dash-announcement">
                    <i class="fa-regular fa-calendar"></i>
                    <div>
                        <h4>Midterm presentation schedule has been posted.</h4>
                        <p>May 12</p>
                    </div>
                </div>

                <div class="dash-announcement">
                    <i class="fa-regular fa-file-lines"></i>
                    <div>
                        <h4>Submit UI wireframes before Friday.</h4>
                        <p>May 16</p>
                    </div>
                </div>

            </div>

        </section>

    </main>

</div>

</body>
</html>