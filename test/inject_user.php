<?php
require '../db.php';

$message = '';
$message_type = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id      = trim($_POST['student_id']) ?: null;
    $username        = trim($_POST['username']);
    $email           = trim($_POST['email']);
    $password        = $_POST['password'];
    $first_name      = trim($_POST['first_name']);
    $last_name       = trim($_POST['last_name']);
    $role            = $_POST['role'];
    $avatar_initials = trim($_POST['avatar_initials']) ?: strtoupper(substr($first_name, 0, 1) . substr($last_name, 0, 1));

    try {
        $hash = password_hash($password, PASSWORD_BCRYPT);

        $stmt = $db->prepare("INSERT INTO users (student_id, username, email, password_hash, first_name, last_name, role, avatar_initials) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$student_id, $username, $email, $hash, $first_name, $last_name, $role, $avatar_initials]);

        $new_id = $db->lastInsertId();
        $message = "User created. user_id = {$new_id}. Login with username '{$username}' and the password you entered.";
        $message_type = 'success';
    } catch (PDOException $e) {
        $message = "Error: " . $e->getMessage();
        $message_type = 'error';
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Inject User - EduEngage Test</title>
    <style>
        body { font-family: 'Inter', sans-serif; max-width: 480px; margin: 40px auto; padding: 0 20px; color: #1f2933; }
        h1 { font-size: 22px; margin: 0 0 18px; }
        label { display: block; margin-top: 12px; font-size: 13px; color: #555; }
        input, select { width: 100%; padding: 8px 10px; box-sizing: border-box; margin-top: 4px; font-size: 14px; border: 1px solid #d4d9e1; border-radius: 6px; }
        button { margin-top: 18px; padding: 10px 18px; background: #2563eb; color: #fff; border: none; border-radius: 6px; cursor: pointer; font-size: 14px; }
        .success { background: #ecfdf5; color: #047857; padding: 10px 14px; border-radius: 8px; margin-bottom: 18px; font-size: 13px; }
        .error { background: #fdecea; color: #c0392b; padding: 10px 14px; border-radius: 8px; margin-bottom: 18px; font-size: 13px; }
        small { color: #8a94a6; }
    </style>
</head>
<body>

<h1>Inject Test User</h1>

<?php if ($message): ?>
    <p class="<?php echo $message_type; ?>"><?php echo htmlspecialchars($message); ?></p>
<?php endif; ?>

<form method="POST">

    <label>Student ID <small>(optional, must be unique)</small></label>
    <input type="text" name="student_id">

    <label>Username *</label>
    <input type="text" name="username" required>

    <label>Email *</label>
    <input type="email" name="email" required>

    <label>Password * <small>(stored as bcrypt hash)</small></label>
    <input type="text" name="password" required>

    <label>First Name *</label>
    <input type="text" name="first_name" required>

    <label>Last Name *</label>
    <input type="text" name="last_name" required>

    <label>Role *</label>
    <select name="role">
        <option value="student">Student</option>
        <option value="instructor">Instructor</option>
        <option value="admin">Admin</option>
    </select>

    <label>Avatar Initials <small>(optional, auto-generated from name if blank)</small></label>
    <input type="text" name="avatar_initials" maxlength="4">

    <button type="submit">Create User</button>

</form>

</body>
</html>
