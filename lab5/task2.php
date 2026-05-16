<?php
session_start();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($username === 'admin' && $password === '123456') {
        $_SESSION['user_id'] = 1;
        $_SESSION['username'] = $username;
        $_SESSION['login_time'] = date('Y-m-d H:i:s');
        $_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];
    } else {
        $error = 'Login yoki parol noto\'g\'ri!';
    }
}

if (isset($_POST['logout'])) {
    session_destroy();
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}
?>
<!DOCTYPE html>
<html lang="uz">
<head>
<meta charset="UTF-8">
<title>Lab5 – Task 2 – Session Login</title>
<style>
    body { font-family: Arial; max-width: 500px; margin: 60px auto; padding: 0 16px; }
    h2 { color: #1F5C99; }
    input { width: 100%; padding: 9px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; margin-bottom: 10px; }
    button { background: #1F5C99; color: #fff; width: 100%; padding: 11px; border: none; border-radius: 4px; cursor: pointer; font-size: 15px; }
    .btn-red { background: #c62828; margin-top: 12px; }
    .ok { background: #e8f5e9; padding: 14px; border-radius: 6px; color: #2e7d32; margin-bottom: 16px; }
    .err { background: #ffebee; padding: 12px; border-radius: 6px; color: #c62828; margin-bottom: 14px; }
    .hint { background: #e3f2fd; padding: 10px; border-radius: 4px; color: #1F5C99; margin-bottom: 14px; font-size: 14px; }
    table { width: 100%; border-collapse: collapse; margin-top: 14px; }
    th { background: #1F5C99; color: #fff; padding: 9px; text-align: left; }
    td { padding: 9px 10px; border-bottom: 1px solid #eee; }
    td:first-child { font-weight: bold; color: #555; width: 40%; }
    .sid { font-size: 12px; color: #777; word-break: break-all; margin-top: 10px; background: #f5f5f5; padding: 8px; border-radius: 4px; }
</style>
</head>
<body>

<?php if (isset($_SESSION['user_id'])): ?>
    <div class="ok">Xush kelibsiz, <b><?= htmlspecialchars($_SESSION['username']) ?></b>!</div>
    <table>
        <tr><th>Parametr</th><th>Qiymat</th></tr>
        <tr><td>User ID</td><td><?= $_SESSION['user_id'] ?></td></tr>
        <tr><td>Foydalanuvchi</td><td><?= htmlspecialchars($_SESSION['username']) ?></td></tr>
        <tr><td>Kirish vaqti</td><td><?= $_SESSION['login_time'] ?></td></tr>
        <tr><td>IP manzil</td><td><?= htmlspecialchars($_SESSION['ip']) ?></td></tr>
    </table>
    <div class="sid">Session ID: <?= session_id() ?></div>
    <form method="POST">
        <button name="logout" class="btn-red">Chiqish</button>
    </form>

<?php else: ?>
    <h2>Tizimga kirish</h2>
    <div class="hint">Demo: <b>admin</b> / <b>123456</b></div>
    <?php if ($error): ?>
        <div class="err"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form method="POST">
        <label>Username:</label>
        <input type="text" name="username" placeholder="admin" value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
        <label>Parol:</label>
        <input type="password" name="password" placeholder="123456">
        <button name="login">Kirish</button>
    </form>
<?php endif; ?>

</body>
</html>
