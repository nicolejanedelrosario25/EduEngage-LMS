<?php
session_start();

if (!isset($_SESSION['student_name'])) {
    header("Location: index.php");
    exit();
}

require 'db.php';

$user_id = $_SESSION['user_id'];
$name = $_SESSION['student_name'];

$year = (int)date('Y');
$month = (int)date('n');
$today = (int)date('j');
$month_name = date('F Y');

$first_of_month = mktime(0, 0, 0, $month, 1, $year);
$first_dow = (int)date('w', $first_of_month);
$days_in_month = (int)date('t', $first_of_month);
$prev_month_days = (int)date('t', mktime(0, 0, 0, $month - 1, 1, $year));

$stmt = $db->prepare("SELECT DAY(starts_at) AS d, title, event_type FROM calendar_events WHERE YEAR(starts_at) = ? AND MONTH(starts_at) = ?");
$stmt->execute([$year, $month]);
$events_by_day = [];
foreach ($stmt->fetchAll() as $e) {
    $events_by_day[(int)$e['d']] = $e;
}

$upcoming_count = $db->query("SELECT COUNT(*) FROM calendar_events WHERE starts_at >= NOW() AND starts_at < DATE_ADD(NOW(), INTERVAL 1 MONTH)")->fetchColumn();
$deadline_count = $db->query("SELECT COUNT(*) FROM calendar_events WHERE event_type = 'deadline' AND starts_at >= NOW()")->fetchColumn();

$stmt = $db->prepare("SELECT COUNT(*) FROM notifications WHERE user_id = ? AND is_read = FALSE");
$stmt->execute([$user_id]);
$reminder_count = $stmt->fetchColumn();

$upcoming = $db->query("SELECT title, starts_at, event_type FROM calendar_events WHERE starts_at >= NOW() ORDER BY starts_at ASC LIMIT 3")->fetchAll();

$event_style = [
    'presentation' => ['orange-round', 'fa-display'],
    'deadline'     => ['green-round',  'fa-file-pen'],
    'discussion'   => ['blue-round',   'fa-comments'],
    'class'        => ['purple-round', 'fa-chalkboard-user'],
    'reminder'     => ['orange-round', 'fa-bell'],
    'other'        => ['blue-round',   'fa-pencil-ruler'],
];

$total_cells = ceil(($first_dow + $days_in_month) / 7) * 7;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Calendar - EduEngage LMS</title>

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
            <a href="progress.php"><i class="fa-solid fa-chart-line"></i> Progress</a>
            <a class="active" href="calendar.php"><i class="fa-regular fa-calendar"></i> Calendar</a>
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
                <h1>Calendar</h1>
                <p>View deadlines, class schedules, and upcoming LMS activities.</p>
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

        <section class="calendar-summary-grid">

            <div class="calendar-summary-card">
                <div class="round-icon orange-round">
                    <i class="fa-solid fa-calendar-check"></i>
                </div>
                <div>
                    <p>Upcoming Events</p>
                    <h2><?php echo (int)$upcoming_count; ?> Events</h2>
                    <span>This month</span>
                </div>
            </div>

            <div class="calendar-summary-card">
                <div class="round-icon green-round">
                    <i class="fa-solid fa-file-pen"></i>
                </div>
                <div>
                    <p>Deadlines</p>
                    <h2><?php echo (int)$deadline_count; ?> Tasks</h2>
                    <span>Need attention</span>
                </div>
            </div>

            <div class="calendar-summary-card">
                <div class="round-icon blue-round">
                    <i class="fa-solid fa-clock"></i>
                </div>
                <div>
                    <p>Today</p>
                    <h2><?php echo date('M j'); ?></h2>
                    <span>Current schedule</span>
                </div>
            </div>

            <div class="calendar-summary-card">
                <div class="round-icon purple-round">
                    <i class="fa-solid fa-bell"></i>
                </div>
                <div>
                    <p>Reminders</p>
                    <h2><?php echo (int)$reminder_count; ?> Alerts</h2>
                    <span>Due soon</span>
                </div>
            </div>

        </section>

        <section class="calendar-page-grid">

            <div class="calendar-main-panel">

                <div class="calendar-header-clean">
                    <h2><?php echo $month_name; ?></h2>

                    <div class="calendar-buttons">
                        <button><i class="fa-solid fa-chevron-left"></i></button>
                        <button class="today-btn">Today</button>
                        <button><i class="fa-solid fa-chevron-right"></i></button>
                    </div>
                </div>

                <div class="calendar-weekdays">
                    <span>Sun</span>
                    <span>Mon</span>
                    <span>Tue</span>
                    <span>Wed</span>
                    <span>Thu</span>
                    <span>Fri</span>
                    <span>Sat</span>
                </div>

                <div class="calendar-clean-grid">

                    <?php for ($i = 0; $i < $total_cells; $i++): ?>
                        <?php
                        $day_num = $i - $first_dow + 1;
                        if ($day_num < 1) {
                            $display = $prev_month_days + $day_num;
                            echo '<div class="calendar-clean-cell muted">' . $display . '</div>';
                        } elseif ($day_num > $days_in_month) {
                            $display = $day_num - $days_in_month;
                            echo '<div class="calendar-clean-cell muted">' . $display . '</div>';
                        } elseif ($day_num === $today) {
                            echo '<div class="calendar-clean-cell today">' . $day_num . ' <small>Today</small></div>';
                        } elseif (isset($events_by_day[$day_num])) {
                            $ev = $events_by_day[$day_num];
                            $label = strlen($ev['title']) > 10 ? substr($ev['title'], 0, 10) : $ev['title'];
                            echo '<div class="calendar-clean-cell event">' . $day_num . ' <small>' . htmlspecialchars($label) . '</small></div>';
                        } else {
                            echo '<div class="calendar-clean-cell">' . $day_num . '</div>';
                        }
                        ?>
                    <?php endfor; ?>

                </div>

            </div>

            <div class="calendar-side-panel">

                <h2>Upcoming Events</h2>

                <?php foreach ($upcoming as $u): ?>
                    <?php [$color, $icon] = $event_style[$u['event_type']] ?? $event_style['other']; ?>
                    <div class="calendar-event-clean">
                        <div class="round-icon <?php echo $color; ?>">
                            <i class="fa-solid <?php echo $icon; ?>"></i>
                        </div>

                        <div>
                            <h4><?php echo htmlspecialchars($u['title']); ?></h4>
                            <p><?php echo date('M j · g:i A', strtotime($u['starts_at'])); ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>

                <hr>

                <h2>Research Purpose</h2>

                <p class="research-note-clean">
                    This page helps students track deadlines clearly, improving task awareness and LMS usability.
                </p>

            </div>

        </section>

    </main>

</div>

</body>
</html>
