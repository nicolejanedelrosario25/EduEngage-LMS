<?php
session_start();

if (!isset($_SESSION['student_name'])) {
    header("Location: index.php");
    exit();
}

require 'db.php';

$user_id = $_SESSION['user_id'];
$name = $_SESSION['student_name'];

$stmt = $db->prepare("SELECT ROUND(AVG(progress_percent)) FROM enrollments WHERE user_id = ?");
$stmt->execute([$user_id]);
$overall_progress = $stmt->fetchColumn() ?: 0;

$stmt = $db->prepare("SELECT COALESCE(ROUND(SUM(duration_mins) / 60), 0) FROM study_sessions WHERE user_id = ?");
$stmt->execute([$user_id]);
$learning_hours = $stmt->fetchColumn();

$stmt = $db->prepare("SELECT COUNT(*) FROM assignment_submissions WHERE user_id = ?");
$stmt->execute([$user_id]);
$total_submissions = (int)$stmt->fetchColumn();

$stmt = $db->prepare("SELECT COUNT(*) FROM assignment_submissions WHERE user_id = ? AND status IN ('submitted', 'graded', 'late')");
$stmt->execute([$user_id]);
$submitted_count = (int)$stmt->fetchColumn();
$assignment_percent = $total_submissions > 0 ? round(($submitted_count / $total_submissions) * 100) : 0;

$stmt = $db->prepare("SELECT COUNT(*) FROM discussion_posts WHERE user_id = ?");
$stmt->execute([$user_id]);
$my_posts = (int)$stmt->fetchColumn();

$stmt = $db->prepare("SELECT COUNT(*) FROM discussion_replies WHERE user_id = ?");
$stmt->execute([$user_id]);
$my_replies = (int)$stmt->fetchColumn();

$participation_percent = min(100, ($my_posts + $my_replies) * 10);
$engagement_percent = round(($overall_progress + $assignment_percent + $participation_percent) / 3);

$stmt = $db->prepare("SELECT COUNT(*) FROM activity_logs WHERE user_id = ? AND activity_type = 'login'");
$stmt->execute([$user_id]);
$login_count = $stmt->fetchColumn();

$stmt = $db->prepare("SELECT c.title, c.icon_class, c.color_theme, c.module_count, e.progress_percent, e.modules_completed FROM enrollments e JOIN courses c ON c.course_id = e.course_id WHERE e.user_id = ? ORDER BY e.progress_percent DESC");
$stmt->execute([$user_id]);
$courses = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Progress - EduEngage LMS</title>

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
            <a href="dashboard.php"><i class="fa-solid fa-table-columns"></i> Dashboard</a>
            <a href="courses.php"><i class="fa-solid fa-book"></i> Courses</a>
            <a href="assignments.php"><i class="fa-solid fa-clipboard-list"></i> Assignments</a>
            <a href="discussion.php"><i class="fa-regular fa-comments"></i> Discussion</a>
            <a class="active" href="progress.php"><i class="fa-solid fa-chart-line"></i> Progress</a>
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
                <h1>Learning Progress</h1>
                <p>Track course completion, activity, and learning engagement.</p>
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

        <section class="progress-summary-grid">

            <div class="progress-summary-card">
                <div class="round-icon green-round">
                    <i class="fa-solid fa-chart-line"></i>
                </div>

                <div>
                    <p>Overall Progress</p>
                    <h2><?php echo (int)$overall_progress; ?>%</h2>
                    <span>Course completion</span>
                </div>
            </div>

            <div class="progress-summary-card">
                <div class="round-icon blue-round">
                    <i class="fa-solid fa-clock"></i>
                </div>

                <div>
                    <p>Learning Hours</p>
                    <h2><?php echo (int)$learning_hours; ?> hrs</h2>
                    <span>Total study time</span>
                </div>
            </div>

            <div class="progress-summary-card">
                <div class="round-icon orange-round">
                    <i class="fa-solid fa-file-circle-check"></i>
                </div>

                <div>
                    <p>Assignments</p>
                    <h2><?php echo (int)$assignment_percent; ?>%</h2>
                    <span>Submission rate</span>
                </div>
            </div>

            <div class="progress-summary-card">
                <div class="round-icon purple-round">
                    <i class="fa-solid fa-comments"></i>
                </div>

                <div>
                    <p>Participation</p>
                    <h2><?php echo (int)$participation_percent; ?>%</h2>
                    <span>Discussion activity</span>
                </div>
            </div>

        </section>

        <section class="progress-page-grid">

            <div class="progress-main-panel">

                <div class="progress-panel-header">
                    <h2>Course Completion</h2>
                    <a href="courses.php">View courses</a>
                </div>

                <?php foreach ($courses as $c): ?>
                    <div class="progress-course-item">
                        <div class="round-icon <?php echo htmlspecialchars($c['color_theme']); ?>">
                            <i class="fa-solid <?php echo htmlspecialchars($c['icon_class']); ?>"></i>
                        </div>

                        <div class="progress-course-info">
                            <h3><?php echo htmlspecialchars($c['title']); ?></h3>
                            <p><?php echo (int)$c['modules_completed']; ?> / <?php echo (int)$c['module_count']; ?> Modules Completed</p>

                            <div class="course-progress-line">
                                <div style="width:<?php echo (int)$c['progress_percent']; ?>%"></div>
                            </div>
                        </div>

                        <span><?php echo (int)$c['progress_percent']; ?>%</span>
                    </div>
                <?php endforeach; ?>

            </div>

            <div class="progress-side-panel">

                <h2>Engagement Overview</h2>

                <div class="progress-circle-wrap">
                    <div class="progress-circle-clean">
                        <h1><?php echo (int)$engagement_percent; ?>%</h1>
                        <span>Engaged</span>
                    </div>
                </div>

                <div class="engagement-clean-list">

                    <div class="engagement-clean-item">
                        <span>Login Frequency</span>
                        <strong><?php echo (int)$login_count; ?> Times</strong>
                    </div>

                    <div class="engagement-clean-item">
                        <span>Time Spent</span>
                        <strong><?php echo (int)$learning_hours; ?> hrs</strong>
                    </div>

                    <div class="engagement-clean-item">
                        <span>Discussion Posts</span>
                        <strong><?php echo (int)$my_posts; ?> Posts</strong>
                    </div>

                    <div class="engagement-clean-item">
                        <span>Assignment Completion</span>
                        <strong><?php echo (int)$assignment_percent; ?>%</strong>
                    </div>

                </div>

                <hr>

                <h2>Activity Chart</h2>

                <div class="progress-chart-clean">
                    <div style="height:45%"></div>
                    <div style="height:65%"></div>
                    <div style="height:55%"></div>
                    <div style="height:80%"></div>
                    <div style="height:72%"></div>
                    <div style="height:60%"></div>
                </div>

                <p class="research-note-clean">
                    This page measures learning interaction, completion rate,
                    and student engagement for the LMS study.
                </p>

            </div>

        </section>

    </main>

</div>

</body>
</html>
