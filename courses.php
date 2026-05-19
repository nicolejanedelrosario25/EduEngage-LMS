<?php
session_start();

if (!isset($_SESSION['student_name'])) {
    header("Location: index.php");
    exit();
}

require 'db.php';

$user_id = $_SESSION['user_id'];
$name = $_SESSION['student_name'];

$search = trim($_GET['q'] ?? '');
$category = trim($_GET['category'] ?? '');

$sql = "SELECT c.title, c.lesson_count, c.duration_hours, c.icon_class, c.color_theme, c.status_label, CONCAT(u.first_name, ' ', u.last_name) AS instructor_name, e.progress_percent FROM enrollments e JOIN courses c ON c.course_id = e.course_id JOIN users u ON u.user_id = c.instructor_id LEFT JOIN categories cat ON cat.category_id = c.category_id WHERE e.user_id = ?";
$params = [$user_id];

if ($search !== '') {
    $sql .= " AND c.title LIKE ?";
    $params[] = '%' . $search . '%';
}

if ($category !== '') {
    $sql .= " AND cat.name = ?";
    $params[] = $category;
}

$sql .= " ORDER BY c.course_id ASC";

$stmt = $db->prepare($sql);
$stmt->execute($params);
$courses = $stmt->fetchAll();

$categories = $db->query("SELECT name FROM categories ORDER BY name ASC")->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Courses - EduEngage LMS</title>
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
            <a class="active" href="courses.php"><i class="fa-solid fa-book"></i> Courses</a>
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
                <h1>My Courses</h1>
                <p>View your enrolled courses and learning progress.</p>
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

        <form method="GET" class="course-tools">

            <div class="course-search">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" name="q" placeholder="Search courses..." value="<?php echo htmlspecialchars($search); ?>">
            </div>

            <div class="course-sort">
                <button type="submit"><i class="fa-solid fa-filter"></i></button>

                <select name="category" onchange="this.form.submit()">
                    <option value="">All Categories</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?php echo htmlspecialchars($cat['name']); ?>" <?php echo $category === $cat['name'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($cat['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <button class="active-view" type="submit"><i class="fa-solid fa-grip"></i></button>
            </div>

        </form>

        <section class="course-card-grid">

            <?php if (empty($courses)): ?>
                <p class="course-empty-state">No courses found. Try a different search or category.</p>
            <?php endif; ?>

            <?php foreach ($courses as $c): ?>
                <div class="learning-card">
                    <div class="course-card-header">
                        <div class="round-icon <?php echo htmlspecialchars($c['color_theme']); ?>">
                            <i class="fa-solid <?php echo htmlspecialchars($c['icon_class']); ?>"></i>
                        </div>
                        <i class="fa-solid fa-ellipsis"></i>
                    </div>

                    <h3><?php echo htmlspecialchars($c['title']); ?></h3>
                    <p class="instructor">Instructor: <?php echo htmlspecialchars($c['instructor_name']); ?></p>

                    <div class="course-details">
                        <span><i class="fa-regular fa-bookmark"></i> <?php echo (int)$c['lesson_count']; ?> Lessons</span>
                        <span><i class="fa-regular fa-clock"></i> <?php echo (int)$c['duration_hours']; ?> Hours</span>
                    </div>

                    <div class="course-progress-line">
                        <div style="width:<?php echo (int)$c['progress_percent']; ?>%"></div>
                    </div>

                    <div class="course-card-footer">
                        <span><?php echo (int)$c['progress_percent']; ?>%</span>
                        <small><?php echo htmlspecialchars($c['status_label']); ?></small>
                    </div>
                </div>
            <?php endforeach; ?>

        </section>

    </main>

</div>

</body>
</html>
