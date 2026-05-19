<?php
require '../db.php';

$message = '';
$message_type = '';
$updated_users = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_password = $_POST['password'];
    $scope = $_POST['scope'];

    try {
        $hash = password_hash($new_password, PASSWORD_BCRYPT);

        if ($scope === 'all') {
            $stmt = $db->prepare("UPDATE users SET password_hash = ?");
            $stmt->execute([$hash]);
            $count = $stmt->rowCount();
            $message = "Reset {$count} user(s) to password '{$new_password}'.";
        } else {
            $stmt = $db->prepare("UPDATE users SET password_hash = ? WHERE username = ?");
            $stmt->execute([$hash, $scope]);
            $count = $stmt->rowCount();
            $message = $count > 0
                ? "Reset user '{$scope}' to password '{$new_password}'."
                : "No user found with username '{$scope}'.";
        }

        $stmt = $db->query("SELECT user_id, username, first_name, last_name, role FROM users ORDER BY user_id ASC");
        $updated_users = $stmt->fetchAll();
        $message_type = $count > 0 ? 'success' : 'error';
    } catch (PDOException $e) {
        $message = "Error: " . $e->getMessage();
        $message_type = 'error';
    }
}

$all_users = $db->query("SELECT username, first_name, last_name, role FROM users ORDER BY user_id ASC")->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Reset Passwords - EduEngage Test</title>
    <style>
        body { font-family: 'Inter', sans-serif; max-width: 520px; margin: 40px auto; padding: 0 20px; color: #1f2933; }
        h1 { font-size: 22px; margin: 0 0 18px; }
        label { display: block; margin-top: 12px; font-size: 13px; color: #555; }
        input, select { width: 100%; padding: 8px 10px; box-sizing: border-box; margin-top: 4px; font-size: 14px; border: 1px solid #d4d9e1; border-radius: 6px; }
        button { margin-top: 18px; padding: 10px 18px; background: #2563eb; color: #fff; border: none; border-radius: 6px; cursor: pointer; font-size: 14px; }
        .success { background: #ecfdf5; color: #047857; padding: 10px 14px; border-radius: 8px; margin-bottom: 18px; font-size: 13px; }
        .error { background: #fdecea; color: #c0392b; padding: 10px 14px; border-radius: 8px; margin-bottom: 18px; font-size: 13px; }
        small { color: #8a94a6; }
        table { width: 100%; margin-top: 20px; border-collapse: collapse; font-size: 13px; }
        th, td { text-align: left; padding: 6px 10px; border-bottom: 1px solid #eef0f4; }
        th { color: #8a94a6; font-weight: 500; }
    </style>
</head>
<body>

<h1>Reset User Passwords</h1>

<?php if ($message): ?>
    <p class="<?php echo $message_type; ?>"><?php echo htmlspecialchars($message); ?></p>
<?php endif; ?>

<form method="POST">

    <label>New Password *</label>
    <input type="text" name="password" value="password123" required>

    <label>Scope *</label>
    <select name="scope">
        <option value="all">All users</option>
        <?php foreach ($all_users as $u): ?>
            <option value="<?php echo htmlspecialchars($u['username']); ?>">
                <?php echo htmlspecialchars($u['username'] . ' — ' . $u['first_name'] . ' ' . $u['last_name']); ?>
            </option>
        <?php endforeach; ?>
    </select>

    <button type="submit">Reset Password</button>

</form>

<table>
    <thead>
        <tr>
            <th>Username</th>
            <th>Name</th>
            <th>Role</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($all_users as $u): ?>
            <tr>
                <td><?php echo htmlspecialchars($u['username']); ?></td>
                <td><?php echo htmlspecialchars($u['first_name'] . ' ' . $u['last_name']); ?></td>
                <td><?php echo htmlspecialchars($u['role']); ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

</body>
</html>
