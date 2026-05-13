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
                        <p>Student</p>
                    </div>

                    <i class="fa-solid fa-caret-down"></i>
                </div>
            </div>

        </header>

        <section class="course-tools">

            <div class="course-search">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" placeholder="Search courses...">
            </div>

            <div class="course-sort">
                <button><i class="fa-solid fa-filter"></i></button>

                <select>
                    <option>All Categories</option>
                    <option>Programming</option>
                    <option>Database</option>
                    <option>Research</option>
                </select>

                <button class="active-view"><i class="fa-solid fa-grip"></i></button>
            </div>

        </section>

        <section class="course-card-grid">

            <div class="learning-card">
                <div class="course-card-header">
                    <div class="round-icon orange-round">
                        <i class="fa-solid fa-code"></i>
                    </div>
                    <i class="fa-solid fa-ellipsis"></i>
                </div>

                <h3>Web Application Development </h3>
                <p class="instructor">Instructor: Roderick Bandalan</p>

                <div class="course-details">
                    <span><i class="fa-regular fa-bookmark"></i> 15 Lessons</span>
                    <span><i class="fa-regular fa-clock"></i> 40 Hours</span>
                </div>

                <div class="course-progress-line">
                    <div style="width:78%"></div>
                </div>

                <div class="course-card-footer">
                    <span>78%</span>
                    <small>In Progress</small>
                </div>
            </div>

            <div class="learning-card">
                <div class="course-card-header">
                    <div class="round-icon purple-round">
                        <i class="fa-solid fa-server"></i>
                    </div>
                    <i class="fa-solid fa-ellipsis"></i>
                </div>

                <h3>Systems Integration</h3>
                <p class="instructor">Instructor: Josephine Petralba</p>

                <div class="course-details">
                    <span><i class="fa-regular fa-bookmark"></i> 14 Lessons</span>
                    <span><i class="fa-regular fa-clock"></i> 36 Hours</span>
                </div>

                <div class="course-progress-line">
                    <div style="width:65%"></div>
                </div>

                <div class="course-card-footer">
                    <span>65%</span>
                    <small>In Progress</small>
                </div>
            </div>

            <div class="learning-card">
                <div class="course-card-header">
                    <div class="round-icon blue-round">
                        <i class="fa-solid fa-globe"></i>
                    </div>
                    <i class="fa-solid fa-ellipsis"></i>
                </div>

                <h3>Fundamentals of Data Analytics</h3>
                <p class="instructor">Instructor: Josephine Petralba</p>

                <div class="course-details">
                    <span><i class="fa-regular fa-bookmark"></i> 20 Lessons</span>
                    <span><i class="fa-regular fa-clock"></i> 45 Hours</span>
                </div>

                <div class="course-progress-line">
                    <div style="width:90%"></div>
                </div>

                <div class="course-card-footer">
                    <span>90%</span>
                    <small>Almost Done</small>
                </div>
            </div>

            <div class="learning-card">
                <div class="course-card-header">
                    <div class="round-icon green-round">
                        <i class="fa-solid fa-database"></i>
                    </div>
                    <i class="fa-solid fa-ellipsis"></i>
                </div>

                <h3>Database Management</h3>
                <p class="instructor">Instructor: William Joe</p>

                <div class="course-details">
                    <span><i class="fa-regular fa-bookmark"></i> 14 Lessons</span>
                    <span><i class="fa-regular fa-clock"></i> 38 Hours</span>
                </div>

                <div class="course-progress-line">
                    <div style="width:72%"></div>
                </div>

                <div class="course-card-footer">
                    <span>72%</span>
                    <small>In Progress</small>
                </div>
            </div>

            <div class="learning-card">
                <div class="course-card-header">
                    <div class="round-icon teal-round">
                        <i class="fa-solid fa-flask"></i>
                    </div>
                    <i class="fa-solid fa-ellipsis"></i>
                </div>

                <h3>Research Methods</h3>
                <p class="instructor">Instructor: Josephine Petralba</p>

                <div class="course-details">
                    <span><i class="fa-regular fa-bookmark"></i> 12 Lessons</span>
                    <span><i class="fa-regular fa-clock"></i> 30 Hours</span>
                </div>

                <div class="course-progress-line">
                    <div style="width:55%"></div>
                </div>

                <div class="course-card-footer">
                    <span>55%</span>
                    <small>In Progress</small>
                </div>
            </div>

            <div class="learning-card">
                <div class="course-card-header">
                    <div class="round-icon pink-round">
                        <i class="fa-solid fa-palette"></i>
                    </div>
                    <i class="fa-solid fa-ellipsis"></i>
                </div>

                <h3>UI Design Activity</h3>
                <p class="instructor">Instructor: Ana Cruz</p>

                <div class="course-details">
                    <span><i class="fa-regular fa-bookmark"></i> 10 Lessons</span>
                    <span><i class="fa-regular fa-clock"></i> 25 Hours</span>
                </div>

                <div class="course-progress-line">
                    <div style="width:34%"></div>
                </div>

                <div class="course-card-footer">
                    <span>34%</span>
                    <small>New Course</small>
                </div>
            </div>

        </section>

    </main>

</div>

</body>
</html>