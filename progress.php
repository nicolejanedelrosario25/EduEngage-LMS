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
                        <p>Student</p>
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
                    <h2>78%</h2>
                    <span>Course completion</span>
                </div>
            </div>

            <div class="progress-summary-card">
                <div class="round-icon blue-round">
                    <i class="fa-solid fa-clock"></i>
                </div>

                <div>
                    <p>Learning Hours</p>
                    <h2>32 hrs</h2>
                    <span>Total study time</span>
                </div>
            </div>

            <div class="progress-summary-card">
                <div class="round-icon orange-round">
                    <i class="fa-solid fa-file-circle-check"></i>
                </div>

                <div>
                    <p>Assignments</p>
                    <h2>80%</h2>
                    <span>Submission rate</span>
                </div>
            </div>

            <div class="progress-summary-card">
                <div class="round-icon purple-round">
                    <i class="fa-solid fa-comments"></i>
                </div>

                <div>
                    <p>Participation</p>
                    <h2>92%</h2>
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

                <div class="progress-course-item">
                    <div class="round-icon orange-round">
                        <i class="fa-solid fa-code"></i>
                    </div>

                    <div class="progress-course-info">
                        <h3>Object Oriented Programming</h3>
                        <p>12 / 15 Modules Completed</p>

                        <div class="course-progress-line">
                            <div style="width:78%"></div>
                        </div>
                    </div>

                    <span>78%</span>
                </div>

                <div class="progress-course-item">
                    <div class="round-icon purple-round">
                        <i class="fa-solid fa-server"></i>
                    </div>

                    <div class="progress-course-info">
                        <h3>Systems Integration</h3>
                        <p>9 / 14 Modules Completed</p>

                        <div class="course-progress-line">
                            <div style="width:65%"></div>
                        </div>
                    </div>

                    <span>65%</span>
                </div>

                <div class="progress-course-item">
                    <div class="round-icon blue-round">
                        <i class="fa-solid fa-globe"></i>
                    </div>

                    <div class="progress-course-info">
                        <h3>Web Systems</h3>
                        <p>18 / 20 Modules Completed</p>

                        <div class="course-progress-line">
                            <div style="width:90%"></div>
                        </div>
                    </div>

                    <span>90%</span>
                </div>

                <div class="progress-course-item">
                    <div class="round-icon green-round">
                        <i class="fa-solid fa-database"></i>
                    </div>

                    <div class="progress-course-info">
                        <h3>Database Management</h3>
                        <p>10 / 14 Modules Completed</p>

                        <div class="course-progress-line">
                            <div style="width:72%"></div>
                        </div>
                    </div>

                    <span>72%</span>
                </div>

            </div>

            <div class="progress-side-panel">

                <h2>Engagement Overview</h2>

                <div class="progress-circle-wrap">
                    <div class="progress-circle-clean">
                        <h1>88%</h1>
                        <span>Engaged</span>
                    </div>
                </div>

                <div class="engagement-clean-list">

                    <div class="engagement-clean-item">
                        <span>Login Frequency</span>
                        <strong>14 Times</strong>
                    </div>

                    <div class="engagement-clean-item">
                        <span>Time Spent</span>
                        <strong>32 hrs</strong>
                    </div>

                    <div class="engagement-clean-item">
                        <span>Discussion Posts</span>
                        <strong>12 Posts</strong>
                    </div>

                    <div class="engagement-clean-item">
                        <span>Assignment Completion</span>
                        <strong>80%</strong>
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