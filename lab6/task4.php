<?php
require_once 'config.php';

$pdo->exec("CREATE TABLE IF NOT EXISTS accounts (
    id      INT AUTO_INCREMENT PRIMARY KEY,
    owner   VARCHAR(100) NOT NULL,
    balance DECIMAL(12,2) DEFAULT 0.00
) ENGINE=InnoDB");

$pdo->exec("INSERT INTO accounts (id, owner, balance) VALUES
    (1, 'Ali Valiyev',  5000.00),
    (2, 'Vali Aliyev',  3000.00)
    ON DUPLICATE KEY UPDATE owner=VALUES(owner)");

$msg = ''; $msgType = 'info';

if (isset($_POST['transfer'])) {
    $from   = intval($_POST['from_id'] ?? 0);
    $to     = intval($_POST['to_id']   ?? 0);
    $amount = floatval($_POST['amount'] ?? 0);

    if ($from > 0 && $to > 0 && $amount > 0 && $from !== $to) {
        try {
            $pdo->beginTransaction();

            $check = $pdo->prepare('SELECT balance FROM accounts WHERE id = :id');
            $check->execute([':id' => $from]);
            $row = $check->fetch();

            if (!$row)
                throw new Exception('Yuboruvchi hisob topilmadi.');
            if ((float)$row['balance'] < $amount)
                throw new Exception('Mablag\' yetarli emas.');

            $stmt = $pdo->prepare('UPDATE accounts SET balance = balance - :a WHERE id = :id');
            $stmt->execute([':a' => $amount, ':id' => $from]);

            $stmt = $pdo->prepare('UPDATE accounts SET balance = balance + :a WHERE id = :id');
            $stmt->execute([':a' => $amount, ':id' => $to]);

            $pdo->commit();
            $msg = number_format($amount, 2) . " so'm muvaffaqiyatli o'tkazildi!";
            $msgType = 'ok';
        } catch (Exception $e) {
            $pdo->rollBack();
            $msg = 'O\'tkazma bekor qilindi: ' . $e->getMessage();
            $msgType = 'err';
        }
    } else {
        $msg = 'Noto\'g\'ri ma\'lumotlar. Iltimos tekshiring.';
        $msgType = 'err';
    }
}

$accounts = $pdo->query('SELECT * FROM accounts ORDER BY id')->fetchAll();
?>
<!DOCTYPE html>
<html lang="uz">
<head>
<meta charset="UTF-8">
<title>Lab6 – Task 4 – Bank O'tkazmasi</title>
<style>
    body { font-family: Arial; max-width: 600px; margin: 40px auto; padding: 0 16px; }
    table { width: 100%; border-collapse: collapse; margin-bottom: 24px; }
    th { background: #1F5C99; color: #fff; padding: 10px; }
    td { padding: 9px 10px; border-bottom: 1px solid #eee; }
    input { width: 100%; padding: 9px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; margin-bottom: 10px; }
    .ok   { background: #e8f5e9; padding: 12px; border-radius: 4px; color: #2e7d32; margin-bottom: 16px; }
    .err  { background: #ffebee; padding: 12px; border-radius: 4px; color: #c62828; margin-bottom: 16px; }
    .info { background: #e3f2fd; padding: 12px; border-radius: 4px; margin-bottom: 16px; }
    button { background: #1F5C99; color: #fff; width: 100%; padding: 11px; border: none; border-radius: 4px; cursor: pointer; font-size: 15px; }
</style>
</head>
<body>
<h2>Bank hisoblar</h2>

<table>
    <tr><th>ID</th><th>Egasi</th><th>Balans (so'm)</th></tr>
    <?php foreach ($accounts as $a): ?>
    <tr>
        <td><?= $a['id'] ?></td>
        <td><?= htmlspecialchars($a['owner']) ?></td>
        <td><b><?= number_format($a['balance'], 2, '.', ' ') ?></b></td>
    </tr>
    <?php endforeach; ?>
</table>

<?php if ($msg): ?>
    <div class="<?= $msgType ?>"><?= htmlspecialchars($msg) ?></div>
<?php endif; ?>

<h3>O'tkazma amalga oshirish</h3>
<form method="POST">
    <label>Yuboruvchi ID (from):</label>
    <input type="number" name="from_id" min="1" placeholder="1" required>
    <label>Qabul qiluvchi ID (to):</label>
    <input type="number" name="to_id" min="1" placeholder="2" required>
    <label>Summa (so'm):</label>
    <input type="number" name="amount" min="1" step="0.01" placeholder="1000" required>
    <button name="transfer">O'tkazish</button>
</form>
</body>
</html>
