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
                        <p>Student</p>
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
                    <h2>5 Events</h2>
                    <span>This month</span>
                </div>
            </div>

            <div class="calendar-summary-card">
                <div class="round-icon green-round">
                    <i class="fa-solid fa-file-pen"></i>
                </div>
                <div>
                    <p>Deadlines</p>
                    <h2>3 Tasks</h2>
                    <span>Need attention</span>
                </div>
            </div>

            <div class="calendar-summary-card">
                <div class="round-icon blue-round">
                    <i class="fa-solid fa-clock"></i>
                </div>
                <div>
                    <p>Today</p>
                    <h2>May 13</h2>
                    <span>Current schedule</span>
                </div>
            </div>

            <div class="calendar-summary-card">
                <div class="round-icon purple-round">
                    <i class="fa-solid fa-bell"></i>
                </div>
                <div>
                    <p>Reminders</p>
                    <h2>2 Alerts</h2>
                    <span>Due soon</span>
                </div>
            </div>

        </section>

        <section class="calendar-page-grid">

            <div class="calendar-main-panel">

                <div class="calendar-header-clean">
                    <h2>May 2026</h2>

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

                    <div class="calendar-clean-cell muted">26</div>
                    <div class="calendar-clean-cell muted">27</div>
                    <div class="calendar-clean-cell muted">28</div>
                    <div class="calendar-clean-cell muted">29</div>
                    <div class="calendar-clean-cell muted">30</div>
                    <div class="calendar-clean-cell">1</div>
                    <div class="calendar-clean-cell">2</div>

                    <div class="calendar-clean-cell">3</div>
                    <div class="calendar-clean-cell">4</div>
                    <div class="calendar-clean-cell">5</div>
                    <div class="calendar-clean-cell">6</div>
                    <div class="calendar-clean-cell">7</div>
                    <div class="calendar-clean-cell">8</div>
                    <div class="calendar-clean-cell">9</div>

                    <div class="calendar-clean-cell">10</div>
                    <div class="calendar-clean-cell">11</div>
                    <div class="calendar-clean-cell event">12 <small>Presentation</small></div>
                    <div class="calendar-clean-cell today">13 <small>Today</small></div>
                    <div class="calendar-clean-cell">14</div>
                    <div class="calendar-clean-cell event">15 <small>Discussion</small></div>
                    <div class="calendar-clean-cell">16</div>

                    <div class="calendar-clean-cell">17</div>
                    <div class="calendar-clean-cell event">18 <small>Proposal</small></div>
                    <div class="calendar-clean-cell">19</div>
                    <div class="calendar-clean-cell event">20 <small>Wireframe</small></div>
                    <div class="calendar-clean-cell">21</div>
                    <div class="calendar-clean-cell event">22 <small>DB Quiz</small></div>
                    <div class="calendar-clean-cell">23</div>

                    <div class="calendar-clean-cell">24</div>
                    <div class="calendar-clean-cell">25</div>
                    <div class="calendar-clean-cell">26</div>
                    <div class="calendar-clean-cell">27</div>
                    <div class="calendar-clean-cell">28</div>
                    <div class="calendar-clean-cell">29</div>
                    <div class="calendar-clean-cell">30</div>

                </div>

            </div>

            <div class="calendar-side-panel">

                <h2>Upcoming Events</h2>

                <div class="calendar-event-clean">
                    <div class="round-icon orange-round">
                        <i class="fa-solid fa-display"></i>
                    </div>

                    <div>
                        <h4>Midterm Presentation</h4>
                        <p>May 12 · 9:00 AM</p>
                    </div>
                </div>

                <div class="calendar-event-clean">
                    <div class="round-icon green-round">
                        <i class="fa-solid fa-file-pen"></i>
                    </div>

                    <div>
                        <h4>Research Proposal</h4>
                        <p>May 18 · 11:59 PM</p>
                    </div>
                </div>

                <div class="calendar-event-clean">
                    <div class="round-icon blue-round">
                        <i class="fa-solid fa-pencil-ruler"></i>
                    </div>

                    <div>
                        <h4>UI Wireframe</h4>
                        <p>May 20 · 11:59 PM</p>
                    </div>
                </div>

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