<?php
require_once 'config.php';

$msg = '';

if (isset($_POST['update'])) {
    $id  = intval($_POST['id']  ?? 0);
    $age = intval($_POST['age'] ?? 0);
    if ($id > 0 && $age >= 14 && $age <= 100) {
        $stmt = $pdo->prepare('UPDATE users SET age = :age WHERE id = :id');
        $stmt->execute([':age' => $age, ':id' => $id]);
        $msg = $stmt->rowCount() > 0
            ? "ID=$id yosh $age ga yangilandi."
            : 'Bunday ID topilmadi.';
    } else {
        $msg = 'Noto\'g\'ri ID yoki yosh qiymati.';
    }
}

if (isset($_POST['delete'])) {
    $id = intval($_POST['del_id'] ?? 0);
    if ($id > 0) {
        $stmt = $pdo->prepare('DELETE FROM users WHERE id = :id');
        $stmt->execute([':id' => $id]);
        $msg = $stmt->rowCount() > 0 ? "ID=$id o'chirildi." : 'Topilmadi.';
    }
}

$results = [];
if (isset($_GET['q']) && trim($_GET['q']) !== '') {
    $keyword = '%' . trim($_GET['q']) . '%';
    $stmt = $pdo->prepare('SELECT * FROM users WHERE name LIKE :q OR email LIKE :q ORDER BY name');
    $stmt->execute([':q' => $keyword]);
    $results = $stmt->fetchAll();
    $msg = count($results) . ' ta natija topildi.';
}

$users = $pdo->query('SELECT * FROM users ORDER BY id')->fetchAll();
?>
<!DOCTYPE html>
<html lang="uz">
<head>
<meta charset="UTF-8">
<title>Lab6 – Task 3</title>
<style>
    body { font-family: Arial; max-width: 960px; margin: 40px auto; padding: 0 16px; }
    .cards { display: flex; gap: 20px; flex-wrap: wrap; margin-bottom: 24px; }
    .card { flex: 1; min-width: 260px; background: #f5f8ff; padding: 16px; border-radius: 8px; }
    input { width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; margin-bottom: 8px; }
    .btn { padding: 9px 18px; border: none; border-radius: 4px; cursor: pointer; width: 100%; }
    .btn-blue  { background: #1F5C99; color: #fff; }
    .btn-red   { background: #c62828; color: #fff; }
    .btn-green { background: #2e7d32; color: #fff; }
    .msg { background: #e3f2fd; padding: 10px 14px; border-radius: 4px; margin-bottom: 16px; }
    table { width: 100%; border-collapse: collapse; margin-top: 16px; }
    th { background: #1F5C99; color: #fff; padding: 10px; }
    td { padding: 9px 10px; border-bottom: 1px solid #eee; }
</style>
</head>
<body>

<?php if ($msg): ?>
    <div class="msg"><?= htmlspecialchars($msg) ?></div>
<?php endif; ?>

<div class="cards">
    <div class="card">
        <h3>Yosh yangilash</h3>
        <form method="POST">
            <label>Foydalanuvchi ID:</label>
            <input type="number" name="id" min="1" placeholder="1" required>
            <label>Yangi yosh:</label>
            <input type="number" name="age" min="14" max="100" placeholder="25" required>
            <button name="update" class="btn btn-blue">Yangilash</button>
        </form>
    </div>

    <div class="card">
        <h3>O'chirish</h3>
        <form method="POST" onsubmit="return confirm('Haqiqatan o\'chirasizmi?')">
            <label>O'chirish uchun ID:</label>
            <input type="number" name="del_id" min="1" placeholder="2" required>
            <button name="delete" class="btn btn-red">O'chirish</button>
        </form>
    </div>

    <div class="card">
        <h3>Qidiruv</h3>
        <form method="GET">
            <label>Ism yoki email:</label>
            <input type="search" name="q"
                   value="<?= htmlspecialchars($_GET['q'] ?? '') ?>"
                   placeholder="masalan: Ali">
            <button type="submit" class="btn btn-green">Qidirish</button>
        </form>
        <?php if (isset($_GET['q']) && count($results) > 0): ?>
            <?php foreach ($results as $r): ?>
            <div style="margin-top:8px; padding:6px; background:#fff; border-radius:4px;">
                <b><?= htmlspecialchars($r['name']) ?></b> – <?= htmlspecialchars($r['email']) ?>
            </div>
            <?php endforeach; ?>
        <?php elseif (isset($_GET['q'])): ?>
            <p style="color:#999">Natija topilmadi.</p>
        <?php endif; ?>
    </div>
</div>

<h3>Barcha foydalanuvchilar (<?= count($users) ?> ta)</h3>
<table>
    <tr><th>ID</th><th>Ism</th><th>Email</th><th>Yosh</th></tr>
    <?php foreach ($users as $u): ?>
    <tr>
        <td><?= $u['id'] ?></td>
        <td><?= htmlspecialchars($u['name']) ?></td>
        <td><?= htmlspecialchars($u['email']) ?></td>
        <td><?= $u['age'] ?></td>
    </tr>
    <?php endforeach; ?>
</table>

</body>
</html>
