<?php
session_start();

if (!isset($_SESSION['student_name'])) {
    header("Location: index.php");
    exit();
}

require 'db.php';

$user_id = $_SESSION['user_id'];
$name = $_SESSION['student_name'];

$assignment_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$stmt = $db->prepare("SELECT a.assignment_id, a.title, a.description, a.assignment_type, c.title AS course_title FROM assignments a JOIN enrollments e ON e.course_id = a.course_id JOIN courses c ON c.course_id = a.course_id WHERE a.assignment_id = ? AND e.user_id = ? AND a.assignment_type = 'quiz'");
$stmt->execute([$assignment_id, $user_id]);
$quiz = $stmt->fetch();

if (!$quiz) {
    header("Location: assignments.php");
    exit();
}

$result_score = null;
$result_total = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $answers = $_POST['q'] ?? [];

    $stmt = $db->prepare("SELECT q.question_id, q.points, o.option_id, o.is_correct FROM quiz_questions q JOIN quiz_options o ON o.question_id = q.question_id WHERE q.assignment_id = ?");
    $stmt->execute([$assignment_id]);
    $rows = $stmt->fetchAll();

    $correct_by_question = [];
    $points_by_question = [];
    foreach ($rows as $r) {
        $points_by_question[$r['question_id']] = (int)$r['points'];
        if ($r['is_correct']) {
            $correct_by_question[$r['question_id']] = (int)$r['option_id'];
        }
    }

    $total_points = array_sum($points_by_question);
    $earned = 0;

    $db->beginTransaction();

    $stmt = $db->prepare("INSERT INTO quiz_attempts (assignment_id, user_id, started_at) VALUES (?, ?, NOW())");
    $stmt->execute([$assignment_id, $user_id]);
    $attempt_id = (int)$db->lastInsertId();

    $ins = $db->prepare("INSERT INTO quiz_attempt_answers (attempt_id, question_id, selected_option_id, is_correct) VALUES (?, ?, ?, ?)");

    foreach ($points_by_question as $qid => $pts) {
        $selected = isset($answers[$qid]) ? (int)$answers[$qid] : null;
        $is_correct = ($selected !== null && isset($correct_by_question[$qid]) && $selected === $correct_by_question[$qid]) ? 1 : 0;
        if ($is_correct) {
            $earned += $pts;
        }
        $ins->execute([$attempt_id, $qid, $selected, $is_correct]);
    }

    $score = $total_points > 0 ? round(($earned / $total_points) * 100, 2) : 0;

    $stmt = $db->prepare("UPDATE quiz_attempts SET submitted_at = NOW(), score = ? WHERE attempt_id = ?");
    $stmt->execute([$score, $attempt_id]);

    $stmt = $db->prepare("INSERT INTO assignment_submissions (assignment_id, user_id, attempt_number, is_latest, status, grade, submitted_at, graded_at) VALUES (?, ?, 1, TRUE, 'graded', ?, NOW(), NOW()) ON DUPLICATE KEY UPDATE status = 'graded', grade = VALUES(grade), submitted_at = NOW(), graded_at = NOW(), is_latest = TRUE");
    $stmt->execute([$assignment_id, $user_id, $score]);

    $db->commit();

    $result_score = $score;
    $result_total = $total_points;
}

$stmt = $db->prepare("SELECT question_id, question_text, points FROM quiz_questions WHERE assignment_id = ? ORDER BY sort_order ASC");
$stmt->execute([$assignment_id]);
$questions = $stmt->fetchAll();

$options_by_question = [];
if ($questions) {
    $question_ids = array_column($questions, 'question_id');
    $placeholders = implode(',', array_fill(0, count($question_ids), '?'));
    $stmt = $db->prepare("SELECT option_id, question_id, option_text FROM quiz_options WHERE question_id IN ($placeholders) ORDER BY sort_order ASC");
    $stmt->execute($question_ids);
    foreach ($stmt->fetchAll() as $o) {
        $options_by_question[$o['question_id']][] = $o;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title><?php echo htmlspecialchars($quiz['title']); ?> - EduEngage LMS</title>
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
                <h1><?php echo htmlspecialchars($quiz['title']); ?></h1>
                <p><?php echo htmlspecialchars($quiz['course_title']); ?></p>
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

        <?php if ($result_score !== null): ?>

            <section class="quiz-result-card">
                <div class="round-icon green-round">
                    <i class="fa-solid fa-circle-check"></i>
                </div>

                <h2>Quiz Submitted</h2>
                <p>You scored</p>
                <h1><?php echo (int)$result_score; ?>%</h1>
                <span><?php echo htmlspecialchars($quiz['title']); ?></span>

                <a href="assignments.php" class="quiz-back-btn">Back to Assignments</a>
            </section>

        <?php else: ?>

            <form method="POST" class="quiz-form">

                <p class="quiz-description"><?php echo htmlspecialchars($quiz['description']); ?></p>

                <?php foreach ($questions as $i => $q): ?>
                    <div class="quiz-question-card">
                        <h3><?php echo ($i + 1) . '. ' . htmlspecialchars($q['question_text']); ?></h3>

                        <?php foreach ($options_by_question[$q['question_id']] ?? [] as $o): ?>
                            <label class="quiz-option">
                                <input type="radio" name="q[<?php echo (int)$q['question_id']; ?>]" value="<?php echo (int)$o['option_id']; ?>" required>
                                <span><?php echo htmlspecialchars($o['option_text']); ?></span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                <?php endforeach; ?>

                <div class="quiz-submit-row">
                    <a href="assignments.php" class="quiz-cancel-btn">Cancel</a>
                    <button type="submit" class="quiz-submit-btn"><i class="fa-solid fa-paper-plane"></i> Submit Quiz</button>
                </div>

            </form>

        <?php endif; ?>

    </main>

</div>

</body>
</html>
