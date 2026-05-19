<?php
session_start();

if (!isset($_SESSION['student_name'])) {
    header("Location: index.php");
    exit();
}

require 'db.php';

$user_id = $_SESSION['user_id'];
$name = $_SESSION['student_name'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['title'], $_POST['body'])) {
    $title = trim($_POST['title']);
    $body = trim($_POST['body']);
    $topic_id = !empty($_POST['topic_id']) ? (int)$_POST['topic_id'] : null;

    if ($title !== '' && $body !== '') {
        $stmt = $db->prepare("INSERT INTO discussion_posts (user_id, topic_id, title, body) VALUES (?, ?, ?, ?)");
        $stmt->execute([$user_id, $topic_id, $title, $body]);
    }

    header("Location: discussion.php");
    exit();
}

$topics = $db->query("SELECT topic_id, name FROM discussion_topics ORDER BY name ASC")->fetchAll();

$total_posts = $db->query("SELECT COUNT(*) FROM discussion_posts")->fetchColumn();
$total_replies = $db->query("SELECT COUNT(*) FROM discussion_replies")->fetchColumn();

$stmt = $db->query("SELECT DAYNAME(created_at) AS day, COUNT(*) AS c FROM discussion_posts GROUP BY day ORDER BY c DESC LIMIT 1");
$most_active = $stmt->fetch();
$most_active_day = $most_active ? $most_active['day'] : '—';

$stmt = $db->prepare("SELECT COUNT(*) FROM discussion_posts WHERE user_id = ?");
$stmt->execute([$user_id]);
$my_posts = $stmt->fetchColumn();

$stmt = $db->prepare("SELECT COUNT(*) FROM discussion_replies WHERE user_id = ?");
$stmt->execute([$user_id]);
$my_replies = $stmt->fetchColumn();

$engagement = min(100, ($my_posts + $my_replies) * 10);
$participation_status = ($my_posts + $my_replies) > 0 ? 'Active' : 'Inactive';

$posts = $db->query("SELECT p.title, p.body, p.like_count, p.reply_count, p.created_at, p.user_id, u.first_name, u.last_name, u.avatar_initials, t.name AS topic_name, t.color_class AS topic_color FROM discussion_posts p JOIN users u ON u.user_id = p.user_id LEFT JOIN discussion_topics t ON t.topic_id = p.topic_id ORDER BY p.created_at DESC")->fetchAll();

function time_ago($datetime) {
    $diff = time() - strtotime($datetime);
    if ($diff < 3600) return floor($diff / 60) . ' minutes ago';
    if ($diff < 86400) return floor($diff / 3600) . ' hours ago';
    if ($diff < 172800) return 'Yesterday';
    return floor($diff / 86400) . ' days ago';
}

$user_colors = ['green-user', 'blue-user', 'purple-user'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Discussion - EduEngage LMS</title>
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
            <a class="active" href="discussion.php"><i class="fa-regular fa-comments"></i> Discussion</a>
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
                <h1>Discussion Forum</h1>
                <p>Collaborate, ask questions, and participate in learning discussions.</p>
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

        <section class="discussion-summary-grid">

            <div class="discussion-summary-card">
                <div class="round-icon blue-round">
                    <i class="fa-solid fa-comments"></i>
                </div>

                <div>
                    <p>Discussion Posts</p>
                    <h2><?php echo (int)$total_posts; ?> Posts</h2>
                    <span>Total participations</span>
                </div>
            </div>

            <div class="discussion-summary-card">
                <div class="round-icon green-round">
                    <i class="fa-solid fa-reply"></i>
                </div>

                <div>
                    <p>Replies</p>
                    <h2><?php echo (int)$total_replies; ?> Replies</h2>
                    <span>Student interactions</span>
                </div>
            </div>

            <div class="discussion-summary-card">
                <div class="round-icon orange-round">
                    <i class="fa-solid fa-fire"></i>
                </div>

                <div>
                    <p>Most Active Day</p>
                    <h2><?php echo htmlspecialchars($most_active_day); ?></h2>
                    <span>Highest participation</span>
                </div>
            </div>

            <div class="discussion-summary-card">
                <div class="round-icon purple-round">
                    <i class="fa-solid fa-chart-line"></i>
                </div>

                <div>
                    <p>Engagement</p>
                    <h2><?php echo (int)$engagement; ?>%</h2>
                    <span>Discussion activity</span>
                </div>
            </div>

        </section>

        <section class="discussion-page-grid">

            <div class="discussion-feed">

                <form method="POST" class="create-discussion-card">
                    <div class="create-discussion-top">
                        <div class="profile-avatar">
                            <i class="fa-solid fa-user"></i>
                        </div>

                        <div>
                            <h3>Create Discussion</h3>
                            <p>Share ideas with your classmates</p>
                        </div>
                    </div>

                    <input type="text" name="title" placeholder="Discussion title..." class="discussion-title-input" required>

                    <textarea name="body" placeholder="Start a discussion or ask a question..." required></textarea>

                    <div class="discussion-actions">
                        <select name="topic_id" class="discussion-topic-select">
                            <option value="">No topic</option>
                            <?php foreach ($topics as $t): ?>
                                <option value="<?php echo (int)$t['topic_id']; ?>"><?php echo htmlspecialchars($t['name']); ?></option>
                            <?php endforeach; ?>
                        </select>

                        <button type="button" class="attach-clean"><i class="fa-solid fa-paperclip"></i> Attach</button>
                        <button type="submit" class="post-clean"><i class="fa-solid fa-paper-plane"></i> Post Discussion</button>
                    </div>
                </form>

                <?php foreach ($posts as $p): ?>
                    <?php
                    $avatar_color = $user_colors[$p['user_id'] % count($user_colors)];
                    $topic_color = $p['topic_color'] ?: 'green-topic';
                    ?>
                    <div class="discussion-card-clean">
                        <div class="discussion-card-header">
                            <div class="discussion-user-clean">
                                <div class="discussion-avatar <?php echo $avatar_color; ?>"><?php echo htmlspecialchars($p['avatar_initials']); ?></div>

                                <div>
                                    <h4><?php echo htmlspecialchars($p['first_name'] . ' ' . $p['last_name']); ?></h4>
                                    <p><?php echo time_ago($p['created_at']); ?></p>
                                </div>
                            </div>

                            <?php if ($p['topic_name']): ?>
                                <span class="topic-pill <?php echo htmlspecialchars($topic_color); ?>"><?php echo htmlspecialchars($p['topic_name']); ?></span>
                            <?php endif; ?>
                        </div>

                        <h2><?php echo htmlspecialchars($p['title']); ?></h2>

                        <p class="discussion-text">
                            <?php echo htmlspecialchars($p['body']); ?>
                        </p>

                        <div class="discussion-footer-clean">
                            <span><i class="fa-regular fa-heart"></i> <?php echo (int)$p['like_count']; ?> Likes</span>
                            <span><i class="fa-regular fa-comment"></i> <?php echo (int)$p['reply_count']; ?> Replies</span>
                            <span><i class="fa-solid fa-share"></i> Share</span>
                        </div>
                    </div>
                <?php endforeach; ?>

            </div>

            <div class="discussion-side-clean">

                <h2>Participation Summary</h2>

                <div class="summary-clean-row">
                    <span>Total Posts</span>
                    <strong><?php echo (int)$my_posts; ?></strong>
                </div>

                <div class="summary-clean-row">
                    <span>Total Replies</span>
                    <strong><?php echo (int)$my_replies; ?></strong>
                </div>

                <div class="summary-clean-row">
                    <span>Participation Status</span>
                    <strong><?php echo $participation_status; ?></strong>
                </div>

                <div class="summary-clean-row">
                    <span>Weekly Engagement</span>
                    <strong><?php echo (int)$engagement; ?>%</strong>
                </div>

                <hr>

                <h2>Research Purpose</h2>

                <p class="research-note-clean">
                    This page supports discussion participation tracking for the
                    engagement study and measures learning interaction among students.
                </p>

                <div class="discussion-mini-chart">
                    <div style="height:45%"></div>
                    <div style="height:65%"></div>
                    <div style="height:50%"></div>
                    <div style="height:80%"></div>
                    <div style="height:70%"></div>
                </div>

            </div>

        </section>

    </main>

</div>

</body>
</html>
