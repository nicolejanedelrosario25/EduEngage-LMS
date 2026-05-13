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
                        <p>Student</p>
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
                    <h2>12 Posts</h2>
                    <span>Total participations</span>
                </div>
            </div>

            <div class="discussion-summary-card">
                <div class="round-icon green-round">
                    <i class="fa-solid fa-reply"></i>
                </div>

                <div>
                    <p>Replies</p>
                    <h2>18 Replies</h2>
                    <span>Student interactions</span>
                </div>
            </div>

            <div class="discussion-summary-card">
                <div class="round-icon orange-round">
                    <i class="fa-solid fa-fire"></i>
                </div>

                <div>
                    <p>Most Active Day</p>
                    <h2>Friday</h2>
                    <span>Highest participation</span>
                </div>
            </div>

            <div class="discussion-summary-card">
                <div class="round-icon purple-round">
                    <i class="fa-solid fa-chart-line"></i>
                </div>

                <div>
                    <p>Engagement</p>
                    <h2>92%</h2>
                    <span>Discussion activity</span>
                </div>
            </div>

        </section>

        <section class="discussion-page-grid">

            <div class="discussion-feed">

                <div class="create-discussion-card">
                    <div class="create-discussion-top">
                        <div class="profile-avatar">
                            <i class="fa-solid fa-user"></i>
                        </div>

                        <div>
                            <h3>Create Discussion</h3>
                            <p>Share ideas with your classmates</p>
                        </div>
                    </div>

                    <textarea placeholder="Start a discussion or ask a question..."></textarea>

                    <div class="discussion-actions">
                        <button class="attach-clean"><i class="fa-solid fa-paperclip"></i> Attach</button>
                        <button class="post-clean"><i class="fa-solid fa-paper-plane"></i> Post Discussion</button>
                    </div>
                </div>

                <div class="discussion-card-clean">
                    <div class="discussion-card-header">
                        <div class="discussion-user-clean">
                            <div class="discussion-avatar green-user">ND</div>

                            <div>
                                <h4>Nicole Jane Del Rosario</h4>
                                <p>2 hours ago</p>
                            </div>
                        </div>

                        <span class="topic-pill">UI Design</span>
                    </div>

                    <h2>How can UI design improve student engagement?</h2>

                    <p class="discussion-text">
                        A clean dashboard can help students easily find assignments,
                        deadlines, announcements, and course materials without confusion.
                    </p>

                    <div class="discussion-footer-clean">
                        <span><i class="fa-regular fa-heart"></i> 24 Likes</span>
                        <span><i class="fa-regular fa-comment"></i> 5 Replies</span>
                        <span><i class="fa-solid fa-share"></i> Share</span>
                    </div>
                </div>

                <div class="discussion-card-clean">
                    <div class="discussion-card-header">
                        <div class="discussion-user-clean">
                            <div class="discussion-avatar blue-user">NP</div>

                            <div>
                                <h4>Nathalie Faye Peter</h4>
                                <p>Yesterday</p>
                            </div>
                        </div>

                        <span class="topic-pill blue-topic">LMS Features</span>
                    </div>

                    <h2>Which LMS feature is most useful for students?</h2>

                    <p class="discussion-text">
                        I think progress tracking is one of the best features because students
                        can monitor their completion and performance in real time.
                    </p>

                    <div class="discussion-footer-clean">
                        <span><i class="fa-regular fa-heart"></i> 18 Likes</span>
                        <span><i class="fa-regular fa-comment"></i> 3 Replies</span>
                        <span><i class="fa-solid fa-share"></i> Share</span>
                    </div>
                </div>

            </div>

            <div class="discussion-side-clean">

                <h2>Participation Summary</h2>

                <div class="summary-clean-row">
                    <span>Total Posts</span>
                    <strong>12</strong>
                </div>

                <div class="summary-clean-row">
                    <span>Total Replies</span>
                    <strong>18</strong>
                </div>

                <div class="summary-clean-row">
                    <span>Participation Status</span>
                    <strong>Active</strong>
                </div>

                <div class="summary-clean-row">
                    <span>Weekly Engagement</span>
                    <strong>92%</strong>
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