<?php
require_once 'config.php';

$errors  = [];
$success = '';
$old     = ['name' => '', 'email' => '', 'age' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name  = trim($_POST['name']  ?? '');
    $email = trim(strtolower($_POST['email'] ?? ''));
    $age   = intval($_POST['age'] ?? 0);
    $old   = compact('name', 'email', 'age');

    if (strlen($name) < 3)
        $errors['name'] = 'Ism kamida 3 ta harf bo\'lsin.';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL))
        $errors['email'] = 'Email formati noto\'g\'ri.';
    if ($age < 14 || $age > 100)
        $errors['age'] = 'Yosh 14 dan 100 gacha bo\'lsin.';

    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare('INSERT INTO users (name, email, age) VALUES (:name, :email, :age)');
            $stmt->execute([':name' => $name, ':email' => $email, ':age' => $age]);
            $success = 'Foydalanuvchi qo\'shildi (ID: ' . $pdo->lastInsertId() . ')';
            $old = ['name' => '', 'email' => '', 'age' => ''];
        } catch (PDOException $e) {
            if ($e->getCode() === '23000')
                $errors['email'] = 'Bu email allaqachon ro\'yxatda bor.';
            else
                $errors['db'] = 'Xato yuz berdi. Qayta urinib ko\'ring.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="uz">
<head>
<meta charset="UTF-8">
<title>Lab6 – Task 2</title>
<style>
    body { font-family: Arial; max-width: 480px; margin: 40px auto; padding: 0 16px; }
    .fg { margin-bottom: 14px; }
    label { display: block; margin-bottom: 4px; font-weight: bold; }
    input { width: 100%; padding: 9px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
    .err { color: #c62828; font-size: 13px; margin-top: 4px; }
    .is-err { border-color: #c62828; }
    .ok { background: #e8f5e9; padding: 12px; border-radius: 4px; color: #2e7d32; margin-bottom: 16px; }
    .alert { background: #ffebee; padding: 12px; border-radius: 4px; color: #c62828; margin-bottom: 16px; }
    button { background: #1F5C99; color: #fff; width: 100%; padding: 11px; border: none; border-radius: 4px; cursor: pointer; }
</style>
</head>
<body>
<h2>Yangi foydalanuvchi qo'shish</h2>

<?php if ($success): ?>
    <div class="ok"><?= htmlspecialchars($success) ?></div>
<?php endif; ?>
<?php if (isset($errors['db'])): ?>
    <div class="alert"><?= $errors['db'] ?></div>
<?php endif; ?>

<form method="POST" novalidate>
    <div class="fg">
        <label>To'liq ism</label>
        <input type="text" name="name"
               value="<?= htmlspecialchars($old['name']) ?>"
               class="<?= isset($errors['name']) ? 'is-err' : '' ?>">
        <?php if (isset($errors['name'])): ?>
            <div class="err"><?= $errors['name'] ?></div>
        <?php endif; ?>
    </div>
    <div class="fg">
        <label>Email</label>
        <input type="email" name="email"
               value="<?= htmlspecialchars($old['email']) ?>"
               class="<?= isset($errors['email']) ? 'is-err' : '' ?>">
        <?php if (isset($errors['email'])): ?>
            <div class="err"><?= $errors['email'] ?></div>
        <?php endif; ?>
    </div>
    <div class="fg">
        <label>Yosh</label>
        <input type="number" name="age" min="14" max="100"
               value="<?= htmlspecialchars($old['age']) ?>"
               class="<?= isset($errors['age']) ? 'is-err' : '' ?>">
        <?php if (isset($errors['age'])): ?>
            <div class="err"><?= $errors['age'] ?></div>
        <?php endif; ?>
    </div>
    <button type="submit">Saqlash</button>
</form>
</body>
</html>
