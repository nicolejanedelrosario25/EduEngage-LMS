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
                        <p>Student</p>
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
                    <h2>12 Messages</h2>
                    <span>Unread conversations</span>
                </div>
            </div>

            <div class="messages-summary-card">
                <div class="round-icon blue-round">
                    <i class="fa-solid fa-paper-plane"></i>
                </div>

                <div>
                    <p>Sent</p>
                    <h2>28 Messages</h2>
                    <span>Communication activity</span>
                </div>
            </div>

            <div class="messages-summary-card">
                <div class="round-icon orange-round">
                    <i class="fa-solid fa-user-group"></i>
                </div>

                <div>
                    <p>Contacts</p>
                    <h2>15 People</h2>
                    <span>Class interactions</span>
                </div>
            </div>

            <div class="messages-summary-card">
                <div class="round-icon purple-round">
                    <i class="fa-solid fa-comments"></i>
                </div>

                <div>
                    <p>Engagement</p>
                    <h2>88%</h2>
                    <span>Communication rate</span>
                </div>
            </div>

        </section>

        <section class="messages-page-grid">

            <div class="messages-list-panel">

                <div class="messages-panel-header">
                    <h2>Inbox</h2>

                    <button class="compose-clean">
                        <i class="fa-solid fa-pen"></i> Compose
                    </button>
                </div>

                <div class="message-item-clean active-message-clean">

                    <div class="message-avatar green-user">T</div>

                    <div class="message-content-clean">
                        <div class="message-top-row">
                            <h3>Josephine Petralba</h3>
                            <span>8:30 AM</span>
                        </div>

                        <p>
                            Please check the updated instruction for your research proposal.
                        </p>
                    </div>

                </div>

                <div class="message-item-clean">

                    <div class="message-avatar blue-user">J</div>

                    <div class="message-content-clean">
                        <div class="message-top-row">
                            <h3>Vincent Jorge Balista</h3>
                            <span>Yesterday</span>
                        </div>

                        <p>
                            Can you share the Figma wireframe link later?
                        </p>
                    </div>

                </div>

                <div class="message-item-clean">

                    <div class="message-avatar purple-user">A</div>

                    <div class="message-content-clean">
                        <div class="message-top-row">
                            <h3>Nathalie Faye Peter</h3>
                            <span>May 12</span>
                        </div>

                        <p>
                            Our discussion topic is already posted in the LMS.
                        </p>
                    </div>

                </div>

            </div>

            <div class="chat-panel-clean">

                <div class="chat-header-clean">

                    <div class="chat-user-clean">

                        <div class="message-avatar green-user">T</div>

                        <div>
                            <h3>Josephine Petralba</h3>
                            <p>Online · Systems Integration</p>
                        </div>

                    </div>

                    <button class="chat-more-btn">
                        <i class="fa-solid fa-ellipsis"></i>
                    </button>

                </div>

                <div class="chat-box-clean">

                    <div class="chat-bubble-clean received-clean">
                        Please check the updated instruction for your research proposal.
                    </div>

                    <div class="chat-bubble-clean sent-clean">
                        Noted, Ma'am. I will review it today.
                    </div>

                    <div class="chat-bubble-clean received-clean">
                        Make sure to include your LMS comparison and prototype improvement.
                    </div>

                </div>

                <div class="chat-input-clean">

                    <button>
                        <i class="fa-solid fa-paperclip"></i>
                    </button>

                    <input type="text" placeholder="Type your message...">

                    <button class="send-clean-btn">
                        <i class="fa-solid fa-paper-plane"></i>
                    </button>

                </div>

            </div>

        </section>

    </main>

</div>

</body>
</html>