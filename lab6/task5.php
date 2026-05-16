<?php
require_once 'config.php';

$pdo->exec("CREATE TABLE IF NOT EXISTS products (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    name       VARCHAR(150) NOT NULL,
    price      DECIMAL(12,2) NOT NULL,
    stock      INT UNSIGNED DEFAULT 0,
    category   VARCHAR(50) DEFAULT '',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB");

$msg = ''; $msgType = 'ok';

if (isset($_POST['add'])) {
    $name     = trim($_POST['name']     ?? '');
    $price    = floatval($_POST['price']    ?? 0);
    $stock    = intval($_POST['stock']    ?? 0);
    $category = trim($_POST['category'] ?? '');

    if (strlen($name) >= 2 && $price > 0) {
        $stmt = $pdo->prepare('INSERT INTO products (name, price, stock, category) VALUES (:name, :price, :stock, :cat)');
        $stmt->execute([':name' => $name, ':price' => $price, ':stock' => $stock, ':cat' => $category]);
        $msg = '"' . htmlspecialchars($name) . '" qo\'shildi (ID: ' . $pdo->lastInsertId() . ')';
    } else {
        $msg = 'Xato: nomi (kamida 2 harf) va narxi to\'ldiring.';
        $msgType = 'err';
    }
}

if (isset($_POST['edit'])) {
    $id    = intval($_POST['edit_id']    ?? 0);
    $price = floatval($_POST['edit_price'] ?? 0);
    $stock = intval($_POST['edit_stock']  ?? 0);
    if ($id > 0 && $price > 0) {
        $stmt = $pdo->prepare('UPDATE products SET price = :price, stock = :stock WHERE id = :id');
        $stmt->execute([':price' => $price, ':stock' => $stock, ':id' => $id]);
        $msg = "ID=$id yangilandi.";
    }
}

if (isset($_POST['del'])) {
    $id = intval($_POST['del_id'] ?? 0);
    if ($id > 0) {
        $pdo->prepare('DELETE FROM products WHERE id = :id')->execute([':id' => $id]);
        $msg = "ID=$id o'chirildi.";
    }
}

$products = $pdo->query('SELECT *, (price * stock) AS total FROM products ORDER BY id DESC')->fetchAll();
$stats    = $pdo->query('SELECT COUNT(*) n, SUM(price*stock) boylik, AVG(price) avg_price FROM products')->fetch();
?>
<!DOCTYPE html>
<html lang="uz">
<head>
<meta charset="UTF-8">
<title>Lab6 – Task 5 – Mahsulotlar</title>
<style>
    body { font-family: Arial; max-width: 1050px; margin: 30px auto; padding: 0 16px; }
    h1 { color: #1F5C99; }
    .ok  { background: #e8f5e9; color: #2e7d32; padding: 10px 14px; border-radius: 4px; margin-bottom: 16px; }
    .err { background: #ffebee; color: #c62828; padding: 10px 14px; border-radius: 4px; margin-bottom: 16px; }
    .form-row { display: flex; gap: 10px; flex-wrap: wrap; align-items: flex-end; margin-bottom: 20px; }
    .form-row .fg { flex: 1; min-width: 140px; }
    .form-row label { display: block; font-size: 13px; font-weight: bold; margin-bottom: 4px; }
    .form-row input, .form-row select { width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
    .btn { padding: 9px 16px; border: none; border-radius: 4px; cursor: pointer; font-size: 13px; }
    .btn-add  { background: #1F5C99; color: #fff; padding: 10px 20px; }
    .btn-save { background: #e3f2fd; color: #1F5C99; }
    .btn-del  { background: #ffebee; color: #c62828; }
    table { width: 100%; border-collapse: collapse; }
    th { background: #1F5C99; color: #fff; padding: 10px; text-align: left; }
    td { padding: 8px 10px; border-bottom: 1px solid #eee; vertical-align: middle; }
    tr:hover td { background: #f5f8ff; }
    .stat { display: flex; gap: 16px; margin-top: 20px; flex-wrap: wrap; }
    .sbox { background: #e3f2fd; border-radius: 8px; padding: 12px 20px; flex: 1; min-width: 160px; }
    .sbox b { display: block; font-size: 20px; color: #1F5C99; }
    .inline { display: flex; gap: 4px; align-items: center; }
    .inline input { padding: 5px; width: 90px; }
</style>
</head>
<body>
<h1>Mahsulotlar boshqaruv tizimi</h1>

<?php if ($msg): ?>
    <div class="<?= $msgType ?>"><?= htmlspecialchars($msg) ?></div>
<?php endif; ?>

<form method="POST">
<div class="form-row">
    <div class="fg">
        <label>Mahsulot nomi</label>
        <input type="text" name="name" placeholder="Noutbuk" required>
    </div>
    <div class="fg">
        <label>Narxi (so'm)</label>
        <input type="number" name="price" placeholder="5000000" step="0.01" required>
    </div>
    <div class="fg">
        <label>Miqdori</label>
        <input type="number" name="stock" placeholder="10" value="0">
    </div>
    <div class="fg">
        <label>Kategoriya</label>
        <select name="category">
            <option value="">– Tanlang –</option>
            <option>Elektronika</option>
            <option>Kiyim</option>
            <option>Oziq-ovqat</option>
            <option>Sport</option>
            <option>Kitoblar</option>
        </select>
    </div>
    <div>
        <label>&nbsp;</label>
        <button name="add" class="btn btn-add">+ Qo'shish</button>
    </div>
</div>
</form>

<table>
    <tr>
        <th>ID</th><th>Nomi</th><th>Narxi (so'm)</th>
        <th>Miqdori</th><th>Kategoriya</th><th>Jami (so'm)</th><th>Amallar</th>
    </tr>
    <?php foreach ($products as $pr): ?>
    <tr>
        <td><?= $pr['id'] ?></td>
        <td><?= htmlspecialchars($pr['name']) ?></td>
        <td><?= number_format($pr['price'], 0, '.', ' ') ?></td>
        <td><?= $pr['stock'] ?></td>
        <td><?= htmlspecialchars($pr['category']) ?></td>
        <td><?= number_format($pr['total'], 0, '.', ' ') ?></td>
        <td>
            <form method="POST" class="inline">
                <input type="hidden" name="edit_id" value="<?= $pr['id'] ?>">
                <input type="number" name="edit_price" value="<?= $pr['price'] ?>" step="0.01">
                <input type="number" name="edit_stock" value="<?= $pr['stock'] ?>">
                <button name="edit" class="btn btn-save">Saqlash</button>
            </form>
            &nbsp;
            <form method="POST" style="display:inline" onsubmit="return confirm('O\'chirasizmi?')">
                <input type="hidden" name="del_id" value="<?= $pr['id'] ?>">
                <button name="del" class="btn btn-del">O'chirish</button>
            </form>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

<div class="stat">
    <div class="sbox"><b><?= $stats['n'] ?></b> ta mahsulot</div>
    <div class="sbox"><b><?= number_format($stats['boylik'] ?? 0, 0, '.', ' ') ?></b> Umumiy boylik (so'm)</div>
    <div class="sbox"><b><?= number_format($stats['avg_price'] ?? 0, 0, '.', ' ') ?></b> O'rtacha narx (so'm)</div>
</div>

</body>
</html>
