<?php
require_once 'config.php';

$pdo->exec("CREATE TABLE IF NOT EXISTS users (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    name       VARCHAR(100) NOT NULL,
    email      VARCHAR(150) UNIQUE NOT NULL,
    age        TINYINT UNSIGNED,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

$pdo->exec("INSERT INTO users (name, email, age) VALUES
    ('Ali Valiyev',    'ali@mail.com',    22),
    ('Malika Rahimova','malika@mail.com', 25),
    ('Sardor Karimov', 'sardor@mail.com', 19),
    ('Dilnoza Aliyeva','dilnoza@mail.com',28)
    ON DUPLICATE KEY UPDATE name=VALUES(name)");

$users   = $pdo->query('SELECT * FROM users ORDER BY created_at DESC')->fetchAll();
$jami    = $pdo->query('SELECT COUNT(*) FROM users')->fetchColumn();
$ortacha = $pdo->query('SELECT AVG(age) FROM users')->fetchColumn();
$max_yosh= $pdo->query('SELECT MAX(age) FROM users')->fetchColumn();
?>
<!DOCTYPE html>
<html lang="uz">
<head>
<meta charset="UTF-8">
<title>Lab6 – Task 1</title>
<style>
    body { font-family: Arial; max-width: 850px; margin: 40px auto; padding: 0 16px; }
    table { width: 100%; border-collapse: collapse; margin-top: 16px; }
    th { background: #1F5C99; color: #fff; padding: 10px; }
    td { padding: 9px 10px; border-bottom: 1px solid #ddd; }
    tr:hover td { background: #f0f7ff; }
    .stat { background: #e8f4fd; padding: 12px 16px; border-radius: 6px; margin-top: 20px; }
</style>
</head>
<body>
<h2>Foydalanuvchilar ro'yxati</h2>
<table>
    <tr><th>ID</th><th>Ism</th><th>Email</th><th>Yosh</th><th>Qo'shilgan</th></tr>
    <?php foreach ($users as $u): ?>
    <tr>
        <td><?= $u['id'] ?></td>
        <td><?= htmlspecialchars($u['name']) ?></td>
        <td><?= htmlspecialchars($u['email']) ?></td>
        <td><?= $u['age'] ?></td>
        <td><?= $u['created_at'] ?></td>
    </tr>
    <?php endforeach; ?>
</table>
<div class="stat">
    Jami: <b><?= $jami ?></b> ta &nbsp;|&nbsp;
    O'rtacha yosh: <b><?= round($ortacha, 1) ?></b> &nbsp;|&nbsp;
    Eng katta: <b><?= $max_yosh ?></b>
</div>
</body>
</html>
