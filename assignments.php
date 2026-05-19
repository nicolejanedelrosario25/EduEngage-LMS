<?php
session_start();

if (!isset($_SESSION['student_name'])) {
    header("Location: index.php");
    exit();
}

require 'db.php';

$user_id = $_SESSION['user_id'];
$name = $_SESSION['student_name'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_assignment_id'])) {
    $assignment_id = (int)$_POST['submit_assignment_id'];

    if ($assignment_id > 0) {
        $stmt = $db->prepare("SELECT 1 FROM assignments a JOIN enrollments e ON e.course_id = a.course_id WHERE a.assignment_id = ? AND e.user_id = ?");
        $stmt->execute([$assignment_id, $user_id]);

        if ($stmt->fetchColumn()) {
            $stmt = $db->prepare("INSERT INTO assignment_submissions (assignment_id, user_id, attempt_number, is_latest, status, submitted_at) VALUES (?, ?, 1, TRUE, 'submitted', NOW()) ON DUPLICATE KEY UPDATE status = 'submitted', submitted_at = NOW(), is_latest = TRUE");
            $stmt->execute([$assignment_id, $user_id]);
        }
    }

    header("Location: assignments.php");
    exit();
}

$stmt = $db->prepare("SELECT COUNT(*) FROM assignment_submissions WHERE user_id = ? AND status = 'pending'");
$stmt->execute([$user_id]);
$pending_count = $stmt->fetchColumn();

$stmt = $db->prepare("SELECT COUNT(*) FROM assignment_submissions WHERE user_id = ? AND status IN ('submitted', 'graded', 'late')");
$stmt->execute([$user_id]);
$submitted_count = $stmt->fetchColumn();

$stmt = $db->prepare("SELECT COUNT(*) FROM assignments a JOIN enrollments e ON e.course_id = a.course_id WHERE e.user_id = ? AND a.due_at BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL 7 DAY)");
$stmt->execute([$user_id]);
$this_week_count = $stmt->fetchColumn();

$stmt = $db->prepare("SELECT COUNT(*) FROM assignment_submissions WHERE user_id = ?");
$stmt->execute([$user_id]);
$total_submissions = (int)$stmt->fetchColumn();
$completion_percent = $total_submissions > 0 ? round(($submitted_count / $total_submissions) * 100) : 0;

$stmt = $db->prepare("SELECT a.assignment_id, a.title, a.description, a.due_at, a.assignment_type, c.title AS course_title, s.status FROM assignments a JOIN enrollments e ON e.course_id = a.course_id JOIN courses c ON c.course_id = a.course_id LEFT JOIN assignment_submissions s ON s.assignment_id = a.assignment_id AND s.user_id = e.user_id WHERE e.user_id = ? ORDER BY a.due_at ASC");
$stmt->execute([$user_id]);
$assignments = $stmt->fetchAll();

$stmt = $db->prepare("SELECT a.title, a.due_at FROM assignments a JOIN enrollments e ON e.course_id = a.course_id LEFT JOIN assignment_submissions s ON s.assignment_id = a.assignment_id AND s.user_id = e.user_id WHERE e.user_id = ? AND a.due_at >= NOW() AND (s.status IS NULL OR s.status = 'pending') ORDER BY a.due_at ASC LIMIT 2");
$stmt->execute([$user_id]);
$deadlines = $stmt->fetchAll();

function assignment_style($status, $type) {
    if ($status === 'submitted' || $status === 'graded') {
        return ['green-round', 'fa-check-double'];
    }
    if ($type === 'quiz') {
        return ['blue-round', 'fa-list-check'];
    }
    if ($type === 'project') {
        return ['purple-round', 'fa-code'];
    }
    return ['orange-round', 'fa-file-pen'];
}

function assignment_action($status, $type) {
    if ($status === 'submitted' || $status === 'graded') {
        return ['View', 'gray-link'];
    }
    if ($type === 'quiz') {
        return ['Start Quiz', ''];
    }
    return ['Submit', ''];
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Assignments - EduEngage LMS</title>
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
            <a class="active" href="assignments.php"><i class="fa-solid fa-clipboard-list"></i> Assignments</a>
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
                <h1>Assignments</h1>
                <p>Track your pending, submitted, and upcoming learning tasks.</p>
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

        <section class="assignment-summary-grid">

            <div class="assignment-summary-card">
                <div class="round-icon orange-round">
                    <i class="fa-solid fa-hourglass-half"></i>
                </div>

                <div>
                    <p>Pending</p>
                    <h2><?php echo (int)$pending_count; ?> Tasks</h2>
                    <span>Due soon</span>
                </div>
            </div>

            <div class="assignment-summary-card">
                <div class="round-icon green-round">
                    <i class="fa-solid fa-circle-check"></i>
                </div>

                <div>
                    <p>Submitted</p>
                    <h2><?php echo (int)$submitted_count; ?> Tasks</h2>
                    <span>Good progress</span>
                </div>
            </div>

            <div class="assignment-summary-card">
                <div class="round-icon blue-round">
                    <i class="fa-solid fa-calendar-days"></i>
                </div>

                <div>
                    <p>This Week</p>
                    <h2><?php echo (int)$this_week_count; ?> Tasks</h2>
                    <span>Scheduled activities</span>
                </div>
            </div>

            <div class="assignment-summary-card">
                <div class="round-icon purple-round">
                    <i class="fa-solid fa-chart-pie"></i>
                </div>

                <div>
                    <p>Completion</p>
                    <h2><?php echo (int)$completion_percent; ?>%</h2>
                    <span>Assignment progress</span>
                </div>
            </div>

        </section>

        <section class="assignment-tools">

            <div class="course-search">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" placeholder="Search assignments...">
            </div>

            <div class="assignment-filter">
                <button class="active">All</button>
                <button>Pending</button>
                <button>Submitted</button>
                <button>Missing</button>
            </div>

        </section>

        <section class="assignment-page-grid">

            <div class="assignment-list">

                <?php foreach ($assignments as $a): ?>
                    <?php
                    $status = $a['status'] ?? 'pending';
                    [$icon_color, $icon] = assignment_style($status, $a['assignment_type']);
                    [$action_label, $action_class] = assignment_action($status, $a['assignment_type']);
                    $pill_class = ($status === 'submitted' || $status === 'graded') ? 'submitted-pill' : 'pending-pill';
                    $pill_text = ($status === 'submitted' || $status === 'graded') ? 'Submitted' : 'Pending';
                    ?>
                    <div class="assignment-card-clean">
                        <div class="assignment-left-clean">
                            <div class="round-icon <?php echo $icon_color; ?>">
                                <i class="fa-solid <?php echo $icon; ?>"></i>
                            </div>

                            <div>
                                <h3><?php echo htmlspecialchars($a['title']); ?></h3>
                                <p><?php echo htmlspecialchars($a['course_title']); ?></p>
                                <span><?php echo htmlspecialchars($a['description']); ?></span>
                            </div>
                        </div>

                        <div class="assignment-right-clean">
                            <small class="status-pill <?php echo $pill_class; ?>"><?php echo $pill_text; ?></small>
                            <p><i class="fa-regular fa-calendar"></i> <?php echo date('M j, Y', strtotime($a['due_at'])); ?></p>
                            <?php if ($status === 'submitted' || $status === 'graded'): ?>
                                <a href="#" class="<?php echo $action_class; ?>"><?php echo $action_label; ?></a>
                            <?php elseif ($a['assignment_type'] === 'quiz'): ?>
                                <a href="quiz.php?id=<?php echo (int)$a['assignment_id']; ?>"><?php echo $action_label; ?></a>
                            <?php else: ?>
                                <form method="POST" class="submit-assignment-form">
                                    <input type="hidden" name="submit_assignment_id" value="<?php echo (int)$a['assignment_id']; ?>">
                                    <button type="submit"><?php echo $action_label; ?></button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>

            </div>

            <div class="assignment-side-card">

                <h2>Upcoming Deadlines</h2>

                <?php foreach ($deadlines as $d): ?>
                    <?php
                    $days_left = (strtotime($d['due_at']) - time()) / 86400;
                    $urgency_class = $days_left <= 3 ? 'urgent-pill' : 'soon-pill';
                    $urgency_text = $days_left <= 3 ? 'Urgent' : 'Soon';
                    ?>
                    <div class="deadline-clean">
                        <div>
                            <h4><?php echo htmlspecialchars($d['title']); ?></h4>
                            <p>Due <?php echo date('M j', strtotime($d['due_at'])); ?></p>
                        </div>
                        <span class="<?php echo $urgency_class; ?>"><?php echo $urgency_text; ?></span>
                    </div>
                <?php endforeach; ?>

                <hr>

                <h2>Submission Progress</h2>

                <p class="progress-label-clean">Overall assignment completion</p>

                <div class="course-progress-line">
                    <div style="width:<?php echo (int)$completion_percent; ?>%"></div>
                </div>

                <div class="assignment-percent-text">
                    <h1><?php echo (int)$completion_percent; ?>%</h1>
                    <span>Completed</span>
                </div>

                <p class="research-note-clean">
                    This section helps track assignment submission data for the engagement study.
                </p>

            </div>

        </section>

    </main>

</div>

</body>
</html>
