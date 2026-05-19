<?php
session_start();

if (!isset($_SESSION['student_name'])) {
    header("Location: index.php");
    exit();
}

require 'db.php';

$user_id = $_SESSION['user_id'];
$name = $_SESSION['student_name'];

$stmt = $db->prepare("SELECT COUNT(*) FROM enrollments WHERE user_id = ?");
$stmt->execute([$user_id]);
$course_count = $stmt->fetchColumn();

$stmt = $db->prepare("SELECT COUNT(*) FROM assignment_submissions WHERE user_id = ? AND status = 'pending'");
$stmt->execute([$user_id]);
$pending_count = $stmt->fetchColumn();

$stmt = $db->prepare("SELECT COUNT(*) FROM discussion_posts WHERE user_id = ?");
$stmt->execute([$user_id]);
$discussion_count = $stmt->fetchColumn();

$stmt = $db->prepare("SELECT ROUND(AVG(progress_percent)) FROM enrollments WHERE user_id = ?");
$stmt->execute([$user_id]);
$overall_progress = $stmt->fetchColumn() ?: 0;

$stmt = $db->prepare("SELECT c.title, c.icon_class, c.color_theme, c.module_count, e.progress_percent, e.modules_completed FROM enrollments e JOIN courses c ON c.course_id = e.course_id WHERE e.user_id = ? ORDER BY e.progress_percent DESC LIMIT 3");
$stmt->execute([$user_id]);
$courses = $stmt->fetchAll();

$stmt = $db->prepare("SELECT activity_type, logged_at FROM activity_logs WHERE user_id = ? ORDER BY logged_at DESC LIMIT 3");
$stmt->execute([$user_id]);
$activity = $stmt->fetchAll();

$activity_meta = [
    'login'             => ['green-bg',  'fa-right-to-bracket',  'Logged in'],
    'logout'            => ['green-bg',  'fa-right-from-bracket','Logged out'],
    'course_view'       => ['blue-bg',   'fa-book-open',         'Viewed a course'],
    'assignment_submit' => ['yellow-bg', 'fa-file-circle-check', 'Submitted an assignment'],
    'discussion_post'   => ['blue-bg',   'fa-comment-dots',      'Posted in discussion'],
    'discussion_reply'  => ['blue-bg',   'fa-comment',           'Replied to discussion'],
    'message_sent'      => ['blue-bg',   'fa-paper-plane',       'Sent a message'],
    'module_complete'   => ['green-bg',  'fa-circle-check',      'Completed a module'],
];

$stmt = $db->query("SELECT title, posted_at FROM announcements ORDER BY posted_at DESC LIMIT 2");
$announcements = $stmt->fetchAll();
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
                        <p><?php echo htmlspecialchars(ucfirst($_SESSION['role'])); ?></p>
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
                    <h2><?php echo (int)$course_count; ?> Active Courses</h2>
                </div>
            </div>

            <div class="dash-mini-card">
                <div class="round-icon orange-round">
                    <i class="fa-solid fa-clipboard-list"></i>
                </div>

                <div>
                    <p>Assignments</p>
                    <h2><?php echo (int)$pending_count; ?> Pending Tasks</h2>
                </div>
            </div>

            <div class="dash-mini-card">
                <div class="round-icon blue-round">
                    <i class="fa-regular fa-comments"></i>
                </div>

                <div>
                    <p>Discussion Posts</p>
                    <h2><?php echo (int)$discussion_count; ?> Participations</h2>
                </div>
            </div>

            <div class="dash-mini-card">
                <div class="round-icon purple-round">
                    <i class="fa-solid fa-chart-line"></i>
                </div>

                <div>
                    <p>Progress</p>
                    <h2><?php echo (int)$overall_progress; ?>% Completed</h2>
                </div>
            </div>

        </section>

        <section class="dashboard-main-grid">

            <div class="dashboard-course-panel">

                <div class="dash-panel-header">
                    <h2>My Courses</h2>
                    <a href="courses.php">View all</a>
                </div>

                <?php foreach ($courses as $c): ?>
                    <div class="dash-course-item">
                        <div class="round-icon <?php echo htmlspecialchars($c['color_theme']); ?>">
                            <i class="fa-solid <?php echo htmlspecialchars($c['icon_class']); ?>"></i>
                        </div>

                        <div class="dash-course-info">
                            <h3><?php echo htmlspecialchars($c['title']); ?></h3>
                            <p><?php echo (int)$c['progress_percent']; ?>% Completed · <?php echo (int)$c['modules_completed']; ?> / <?php echo (int)$c['module_count']; ?> Modules</p>

                            <div class="course-progress-line">
                                <div style="width:<?php echo (int)$c['progress_percent']; ?>%"></div>
                            </div>
                        </div>

                        <span><?php echo (int)$c['progress_percent']; ?>%</span>
                    </div>
                <?php endforeach; ?>

            </div>

            <div class="dashboard-side-panel">

                <h2>Student Activity</h2>

                <?php foreach ($activity as $a): ?>
                    <?php $m = $activity_meta[$a['activity_type']] ?? ['blue-bg', 'fa-circle', ucfirst(str_replace('_', ' ', $a['activity_type']))]; ?>
                    <div class="dash-activity-item">
                        <div class="activity-dot-icon <?php echo $m[0]; ?>">
                            <i class="fa-solid <?php echo $m[1]; ?>"></i>
                        </div>

                        <div>
                            <h4><?php echo htmlspecialchars($m[2]); ?></h4>
                            <p><?php echo date('M j, g:i a', strtotime($a['logged_at'])); ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>

                <hr>

                <h2>Announcements</h2>

                <?php foreach ($announcements as $ann): ?>
                    <div class="dash-announcement">
                        <i class="fa-regular fa-calendar"></i>
                        <div>
                            <h4><?php echo htmlspecialchars($ann['title']); ?></h4>
                            <p><?php echo date('M j', strtotime($ann['posted_at'])); ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>

            </div>

        </section>

    </main>

</div>

</body>
</html>
