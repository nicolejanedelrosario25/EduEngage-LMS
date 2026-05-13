<?php
session_start();

if (isset($_POST['login'])) {
    $_SESSION['student_name'] = $_POST['username'];
    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>EduEngage LMS Login</title>
    <link rel="stylesheet" href="style.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head>

<body class="simple-login-body">

<div class="simple-login-page">

    <div class="simple-login-logo">
        <i class="fa-solid fa-graduation-cap"></i>
    </div>

    <div class="simple-login-card">

        <h1>Login</h1>
        <p>Enter your credentials to access EduEngage LMS.</p>

        <form method="POST">

            <div class="simple-input">
                <i class="fa-regular fa-user"></i>
                <input type="text" name="username" placeholder="Student ID / Username" required>
            </div>

            <div class="simple-input">
                <i class="fa-solid fa-lock"></i>
                <input type="password" name="password" placeholder="Enter your password" required>
            </div>

            <button type="submit" name="login">Sign In</button>

        </form>

    </div>

    <p class="simple-forgot">
        Forgot your password?
        <a href="#">Reset Password</a>
    </p>

</div>

</body>
</html>