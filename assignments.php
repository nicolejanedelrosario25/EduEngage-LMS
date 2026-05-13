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
                        <p>Student</p>
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
                    <h2>3 Tasks</h2>
                    <span>Due soon</span>
                </div>
            </div>

            <div class="assignment-summary-card">
                <div class="round-icon green-round">
                    <i class="fa-solid fa-circle-check"></i>
                </div>

                <div>
                    <p>Submitted</p>
                    <h2>8 Tasks</h2>
                    <span>Good progress</span>
                </div>
            </div>

            <div class="assignment-summary-card">
                <div class="round-icon blue-round">
                    <i class="fa-solid fa-calendar-days"></i>
                </div>

                <div>
                    <p>This Week</p>
                    <h2>5 Tasks</h2>
                    <span>Scheduled activities</span>
                </div>
            </div>

            <div class="assignment-summary-card">
                <div class="round-icon purple-round">
                    <i class="fa-solid fa-chart-pie"></i>
                </div>

                <div>
                    <p>Completion</p>
                    <h2>80%</h2>
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

                <div class="assignment-card-clean">
                    <div class="assignment-left-clean">
                        <div class="round-icon orange-round">
                            <i class="fa-solid fa-file-pen"></i>
                        </div>

                        <div>
                            <h3>Research Proposal</h3>
                            <p>Systems Integration</p>
                            <span>Create the initial proposal for the LMS A/B Testing research study.</span>
                        </div>
                    </div>

                    <div class="assignment-right-clean">
                        <small class="status-pill pending-pill">Pending</small>
                        <p><i class="fa-regular fa-calendar"></i> May 18, 2026</p>
                        <a href="#">Submit</a>
                    </div>
                </div>

                <div class="assignment-card-clean">
                    <div class="assignment-left-clean">
                        <div class="round-icon green-round">
                            <i class="fa-solid fa-check-double"></i>
                        </div>

                        <div>
                            <h3>UI Wireframe Design</h3>
                            <p>Web Systems</p>
                            <span>Design the improved LMS dashboard wireframe using Figma.</span>
                        </div>
                    </div>

                    <div class="assignment-right-clean">
                        <small class="status-pill submitted-pill">Submitted</small>
                        <p><i class="fa-regular fa-calendar"></i> May 20, 2026</p>
                        <a href="#" class="gray-link">View</a>
                    </div>
                </div>

                <div class="assignment-card-clean">
                    <div class="assignment-left-clean">
                        <div class="round-icon blue-round">
                            <i class="fa-solid fa-list-check"></i>
                        </div>

                        <div>
                            <h3>Database Quiz</h3>
                            <p>Database Management</p>
                            <span>Answer SQL, tables, relationships, and normalization questions.</span>
                        </div>
                    </div>

                    <div class="assignment-right-clean">
                        <small class="status-pill pending-pill">Pending</small>
                        <p><i class="fa-regular fa-calendar"></i> May 22, 2026</p>
                        <a href="#">Start Quiz</a>
                    </div>
                </div>

                <div class="assignment-card-clean">
                    <div class="assignment-left-clean">
                        <div class="round-icon purple-round">
                            <i class="fa-solid fa-code"></i>
                        </div>

                        <div>
                            <h3>PHP Prototype Activity</h3>
                            <p>Web Systems</p>
                            <span>Create a PHP LMS prototype based on the research improvement.</span>
                        </div>
                    </div>

                    <div class="assignment-right-clean">
                        <small class="status-pill submitted-pill">Submitted</small>
                        <p><i class="fa-regular fa-calendar"></i> May 24, 2026</p>
                        <a href="#" class="gray-link">View</a>
                    </div>
                </div>

            </div>

            <div class="assignment-side-card">

                <h2>Upcoming Deadlines</h2>

                <div class="deadline-clean">
                    <div>
                        <h4>Research Proposal</h4>
                        <p>Due May 18</p>
                    </div>
                    <span class="urgent-pill">Urgent</span>
                </div>

                <div class="deadline-clean">
                    <div>
                        <h4>Database Quiz</h4>
                        <p>Due May 22</p>
                    </div>
                    <span class="soon-pill">Soon</span>
                </div>

                <hr>

                <h2>Submission Progress</h2>

                <p class="progress-label-clean">Overall assignment completion</p>

                <div class="course-progress-line">
                    <div style="width:80%"></div>
                </div>

                <div class="assignment-percent-text">
                    <h1>80%</h1>
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