<?php
$post_data = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($_POST as $key => $value) {
        $post_data[htmlspecialchars($key)] = htmlspecialchars($value);
    }
}
?>
<!DOCTYPE html>
<html lang="uz">
<head>
<meta charset="UTF-8">
<title>Lab5 – Task 1</title>
<style>
    body { font-family: Arial; max-width: 900px; margin: 40px auto; padding: 0 16px; }
    h2 { color: #1F5C99; margin-top: 28px; }
    table { width: 100%; border-collapse: collapse; margin-top: 10px; }
    th { background: #1F5C99; color: #fff; padding: 10px; text-align: left; }
    td { padding: 9px 10px; border-bottom: 1px solid #ddd; }
    td:first-child { font-weight: bold; color: #555; width: 35%; }
    tr:hover td { background: #f0f7ff; }
    .form-box { background: #f9f9f9; padding: 16px; border-radius: 8px; margin-top: 20px; }
    input, textarea { width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; margin-bottom: 8px; }
    button { background: #1F5C99; color: #fff; padding: 9px 20px; border: none; border-radius: 4px; cursor: pointer; }
    .tag { background: #e3f2fd; color: #1F5C99; padding: 3px 8px; border-radius: 4px; font-weight: bold; }
</style>
</head>
<body>
<h1>HTTP Protokol – Server Ma'lumotlari</h1>

<h2>1. Server Ma'lumotlari</h2>
<table>
    <tr><th>Parametr</th><th>Qiymat</th></tr>
    <tr><td>Server dasturi</td><td><?= htmlspecialchars($_SERVER['SERVER_SOFTWARE'] ?? 'N/A') ?></td></tr>
    <tr><td>Server nomi</td><td><?= htmlspecialchars($_SERVER['SERVER_NAME'] ?? 'N/A') ?></td></tr>
    <tr><td>Protokol</td><td><?= htmlspecialchars($_SERVER['SERVER_PROTOCOL'] ?? 'N/A') ?></td></tr>
    <tr><td>Port</td><td><?= htmlspecialchars($_SERVER['SERVER_PORT'] ?? 'N/A') ?></td></tr>
</table>

<h2>2. HTTP So'rov Ma'lumotlari</h2>
<table>
    <tr><th>Parametr</th><th>Qiymat</th></tr>
    <tr><td>HTTP Metod</td><td><span class="tag"><?= $_SERVER['REQUEST_METHOD'] ?></span></td></tr>
    <tr><td>Request URI</td><td><?= htmlspecialchars($_SERVER['REQUEST_URI']) ?></td></tr>
    <tr><td>Query String</td><td><?= htmlspecialchars($_SERVER['QUERY_STRING'] ?: 'Yo\'q') ?></td></tr>
    <tr><td>Mijoz IP</td><td><?= htmlspecialchars($_SERVER['REMOTE_ADDR']) ?></td></tr>
    <tr><td>User-Agent</td><td><?= htmlspecialchars($_SERVER['HTTP_USER_AGENT'] ?? 'N/A') ?></td></tr>
    <tr><td>Referer</td><td><?= htmlspecialchars($_SERVER['HTTP_REFERER'] ?? 'To\'g\'ridan-to\'g\'ri') ?></td></tr>
</table>

<?php if (!empty($_GET)): ?>
<h2>3. GET Parametrlar</h2>
<table>
    <tr><th>Kalit</th><th>Qiymat</th></tr>
    <?php foreach ($_GET as $k => $v): ?>
    <tr>
        <td><?= htmlspecialchars($k) ?></td>
        <td><?= htmlspecialchars($v) ?></td>
    </tr>
    <?php endforeach; ?>
</table>
<?php endif; ?>

<?php if (!empty($post_data)): ?>
<h2>4. POST Ma'lumotlar</h2>
<table>
    <tr><th>Kalit</th><th>Qiymat</th></tr>
    <?php foreach ($post_data as $k => $v): ?>
    <tr>
        <td><?= $k ?></td>
        <td><?= $v ?></td>
    </tr>
    <?php endforeach; ?>
</table>
<?php endif; ?>

<div style="display:flex; gap:20px; flex-wrap:wrap; margin-top:24px;">
    <div class="form-box" style="flex:1; min-width:260px;">
        <h3>GET So'rovi</h3>
        <form method="GET">
            <label>Ism:</label>
            <input type="text" name="ism" placeholder="Ali" value="<?= htmlspecialchars($_GET['ism'] ?? '') ?>">
            <label>Yosh:</label>
            <input type="number" name="yosh" placeholder="22" value="<?= htmlspecialchars($_GET['yosh'] ?? '') ?>">
            <button type="submit">GET yuborish</button>
        </form>
    </div>
    <div class="form-box" style="flex:1; min-width:260px;">
        <h3>POST So'rovi</h3>
        <form method="POST">
            <label>Xabar:</label>
            <textarea name="xabar" rows="3" placeholder="Salom!"></textarea>
            <label>Telefon:</label>
            <input type="text" name="telefon" placeholder="+998901234567">
            <button type="submit">POST yuborish</button>
        </form>
    </div>
</div>
</body>
</html>
