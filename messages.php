<?php
session_start();

if (!isset($_SESSION['student_name'])) {
    header("Location: index.php");
    exit();
}

require 'db.php';

$user_id = $_SESSION['user_id'];
$name = $_SESSION['student_name'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['body'], $_POST['conversation_id'])) {
    $conv_id = (int)$_POST['conversation_id'];
    $body = trim($_POST['body']);

    if ($body !== '' && $conv_id > 0) {
        $stmt = $db->prepare("SELECT 1 FROM conversation_participants WHERE conversation_id = ? AND user_id = ?");
        $stmt->execute([$conv_id, $user_id]);

        if ($stmt->fetchColumn()) {
            $stmt = $db->prepare("INSERT INTO messages (conversation_id, sender_id, body) VALUES (?, ?, ?)");
            $stmt->execute([$conv_id, $user_id, $body]);
        }
    }

    header("Location: messages.php?conv=" . $conv_id);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['recipient_id'])) {
    $recipient_id = (int)$_POST['recipient_id'];

    if ($recipient_id > 0 && $recipient_id !== $user_id) {
        $stmt = $db->prepare("SELECT cp1.conversation_id FROM conversation_participants cp1 JOIN conversation_participants cp2 ON cp1.conversation_id = cp2.conversation_id WHERE cp1.user_id = ? AND cp2.user_id = ? LIMIT 1");
        $stmt->execute([$user_id, $recipient_id]);
        $existing = $stmt->fetchColumn();

        if ($existing) {
            header("Location: messages.php?conv=" . $existing);
            exit();
        }

        $db->beginTransaction();
        $db->exec("INSERT INTO conversations () VALUES ()");
        $new_conv = (int)$db->lastInsertId();
        $stmt = $db->prepare("INSERT INTO conversation_participants (conversation_id, user_id) VALUES (?, ?), (?, ?)");
        $stmt->execute([$new_conv, $user_id, $new_conv, $recipient_id]);
        $db->commit();

        header("Location: messages.php?conv=" . $new_conv);
        exit();
    }

    header("Location: messages.php");
    exit();
}

$compose_mode = isset($_GET['compose']);
$available_users = [];
if ($compose_mode) {
    $stmt = $db->prepare("SELECT user_id, first_name, last_name, avatar_initials FROM users WHERE user_id != ? AND is_active = TRUE ORDER BY first_name ASC");
    $stmt->execute([$user_id]);
    $available_users = $stmt->fetchAll();
}

$stmt = $db->prepare("SELECT COUNT(*) FROM messages m JOIN conversation_participants cp ON cp.conversation_id = m.conversation_id WHERE cp.user_id = ? AND m.sender_id != ? AND m.is_read = FALSE");
$stmt->execute([$user_id, $user_id]);
$inbox_count = $stmt->fetchColumn();

$stmt = $db->prepare("SELECT COUNT(*) FROM messages WHERE sender_id = ?");
$stmt->execute([$user_id]);
$sent_count = $stmt->fetchColumn();

$stmt = $db->prepare("SELECT COUNT(DISTINCT cp2.user_id) FROM conversation_participants cp1 JOIN conversation_participants cp2 ON cp1.conversation_id = cp2.conversation_id WHERE cp1.user_id = ? AND cp2.user_id != ?");
$stmt->execute([$user_id, $user_id]);
$contacts_count = $stmt->fetchColumn();

$engagement = $sent_count > 0 ? min(100, $sent_count * 10 + 20) : 0;

$stmt = $db->prepare("SELECT c.conversation_id, u.first_name, u.last_name, u.avatar_initials, u.user_id AS other_id, (SELECT body FROM messages WHERE conversation_id = c.conversation_id ORDER BY sent_at DESC LIMIT 1) AS last_body, (SELECT sent_at FROM messages WHERE conversation_id = c.conversation_id ORDER BY sent_at DESC LIMIT 1) AS last_sent FROM conversations c JOIN conversation_participants cp ON cp.conversation_id = c.conversation_id JOIN conversation_participants ocp ON ocp.conversation_id = c.conversation_id AND ocp.user_id != cp.user_id JOIN users u ON u.user_id = ocp.user_id WHERE cp.user_id = ? ORDER BY last_sent DESC");
$stmt->execute([$user_id]);
$conversations = $stmt->fetchAll();

$active_conv_id = isset($_GET['conv']) ? (int)$_GET['conv'] : ($conversations[0]['conversation_id'] ?? null);
$active_conv = null;
$messages = [];

if ($active_conv_id) {
    foreach ($conversations as $c) {
        if ($c['conversation_id'] == $active_conv_id) {
            $active_conv = $c;
            break;
        }
    }

    if ($active_conv) {
        $stmt = $db->prepare("UPDATE messages SET is_read = TRUE WHERE conversation_id = ? AND sender_id != ? AND is_read = FALSE");
        $stmt->execute([$active_conv_id, $user_id]);

        $stmt = $db->prepare("SELECT sender_id, body, sent_at FROM messages WHERE conversation_id = ? ORDER BY sent_at ASC");
        $stmt->execute([$active_conv_id]);
        $messages = $stmt->fetchAll();
    }
}

function chat_time($datetime) {
    $diff = time() - strtotime($datetime);
    if ($diff < 86400) return date('g:i A', strtotime($datetime));
    if ($diff < 172800) return 'Yesterday';
    return date('M j', strtotime($datetime));
}

$user_colors = ['green-user', 'blue-user', 'purple-user'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Messages - EduEngage LMS</title>

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
            <a href="calendar.php"><i class="fa-regular fa-calendar"></i> Calendar</a>
            <a class="active" href="messages.php"><i class="fa-regular fa-envelope"></i> Messages</a>
        </nav>

        <div class="course-ui-bottom">
            <a href="#"><i class="fa-regular fa-circle-question"></i> Help</a>
            <a href="logout.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
        </div>

    </aside>

    <main class="course-ui-main">

        <header class="course-ui-topbar">

            <div>
                <h1>Messages</h1>
                <p>Communicate with instructors and classmates.</p>
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

        <section class="messages-summary-grid">

            <div class="messages-summary-card">
                <div class="round-icon green-round">
                    <i class="fa-solid fa-envelope-open-text"></i>
                </div>

                <div>
                    <p>Inbox</p>
                    <h2><?php echo (int)$inbox_count; ?> Messages</h2>
                    <span>Unread conversations</span>
                </div>
            </div>

            <div class="messages-summary-card">
                <div class="round-icon blue-round">
                    <i class="fa-solid fa-paper-plane"></i>
                </div>

                <div>
                    <p>Sent</p>
                    <h2><?php echo (int)$sent_count; ?> Messages</h2>
                    <span>Communication activity</span>
                </div>
            </div>

            <div class="messages-summary-card">
                <div class="round-icon orange-round">
                    <i class="fa-solid fa-user-group"></i>
                </div>

                <div>
                    <p>Contacts</p>
                    <h2><?php echo (int)$contacts_count; ?> People</h2>
                    <span>Class interactions</span>
                </div>
            </div>

            <div class="messages-summary-card">
                <div class="round-icon purple-round">
                    <i class="fa-solid fa-comments"></i>
                </div>

                <div>
                    <p>Engagement</p>
                    <h2><?php echo (int)$engagement; ?>%</h2>
                    <span>Communication rate</span>
                </div>
            </div>

        </section>

        <section class="messages-page-grid">

            <div class="messages-list-panel">

                <div class="messages-panel-header">
                    <h2>Inbox</h2>

                    <a href="?compose=1" class="compose-clean" style="text-decoration:none;display:inline-flex;align-items:center;gap:8px;">
                        <i class="fa-solid fa-pen"></i> Compose
                    </a>
                </div>

                <?php foreach ($conversations as $c): ?>
                    <?php
                    $is_active = $c['conversation_id'] == $active_conv_id;
                    $avatar_color = $user_colors[$c['other_id'] % count($user_colors)];
                    ?>
                    <a href="?conv=<?php echo (int)$c['conversation_id']; ?>" class="message-item-clean <?php echo $is_active ? 'active-message-clean' : ''; ?>" style="text-decoration:none;color:inherit;display:flex;">

                        <div class="message-avatar <?php echo $avatar_color; ?>"><?php echo htmlspecialchars($c['avatar_initials']); ?></div>

                        <div class="message-content-clean">
                            <div class="message-top-row">
                                <h3><?php echo htmlspecialchars($c['first_name'] . ' ' . $c['last_name']); ?></h3>
                                <span><?php echo chat_time($c['last_sent']); ?></span>
                            </div>

                            <p>
                                <?php echo htmlspecialchars($c['last_body']); ?>
                            </p>
                        </div>

                    </a>
                <?php endforeach; ?>

            </div>

            <div class="chat-panel-clean">

                <?php if ($compose_mode): ?>

                    <div class="chat-header-clean">

                        <div class="chat-user-clean">

                            <div class="message-avatar green-user"><i class="fa-solid fa-pen"></i></div>

                            <div>
                                <h3>New Conversation</h3>
                                <p>Select a recipient</p>
                            </div>

                        </div>

                        <a href="messages.php" class="chat-more-btn" style="text-decoration:none;color:inherit;">
                            <i class="fa-solid fa-xmark"></i>
                        </a>

                    </div>

                    <div class="chat-box-clean">

                        <?php foreach ($available_users as $u): ?>
                            <?php $avatar_color = $user_colors[$u['user_id'] % count($user_colors)]; ?>
                            <form method="POST" class="compose-recipient-row">
                                <input type="hidden" name="recipient_id" value="<?php echo (int)$u['user_id']; ?>">

                                <div class="message-avatar <?php echo $avatar_color; ?>"><?php echo htmlspecialchars($u['avatar_initials']); ?></div>

                                <div class="compose-recipient-name">
                                    <h3><?php echo htmlspecialchars($u['first_name'] . ' ' . $u['last_name']); ?></h3>
                                </div>

                                <button class="send-clean-btn" type="submit">
                                    <i class="fa-solid fa-paper-plane"></i>
                                </button>
                            </form>
                        <?php endforeach; ?>

                    </div>

                <?php elseif ($active_conv): ?>
                    <?php $avatar_color = $user_colors[$active_conv['other_id'] % count($user_colors)]; ?>

                    <div class="chat-header-clean">

                        <div class="chat-user-clean">

                            <div class="message-avatar <?php echo $avatar_color; ?>"><?php echo htmlspecialchars($active_conv['avatar_initials']); ?></div>

                            <div>
                                <h3><?php echo htmlspecialchars($active_conv['first_name'] . ' ' . $active_conv['last_name']); ?></h3>
                                <p>Online</p>
                            </div>

                        </div>

                        <button class="chat-more-btn">
                            <i class="fa-solid fa-ellipsis"></i>
                        </button>

                    </div>

                    <div class="chat-box-clean">

                        <?php foreach ($messages as $m): ?>
                            <?php $bubble_class = $m['sender_id'] == $user_id ? 'sent-clean' : 'received-clean'; ?>
                            <div class="chat-bubble-clean <?php echo $bubble_class; ?>">
                                <?php echo htmlspecialchars($m['body']); ?>
                            </div>
                        <?php endforeach; ?>

                    </div>

                    <form method="POST" class="chat-input-clean">
                        <input type="hidden" name="conversation_id" value="<?php echo (int)$active_conv_id; ?>">

                        <button type="button">
                            <i class="fa-solid fa-paperclip"></i>
                        </button>

                        <input type="text" name="body" placeholder="Type your message..." required autocomplete="off">

                        <button class="send-clean-btn" type="submit">
                            <i class="fa-solid fa-paper-plane"></i>
                        </button>

                    </form>
                <?php endif; ?>

            </div>

        </section>

    </main>

</div>

</body>
</html>
