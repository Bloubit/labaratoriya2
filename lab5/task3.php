<?php
if (isset($_GET['set_lang'])) {
    $allowed = ['uz', 'ru', 'en'];
    $lang_set = in_array($_GET['set_lang'], $allowed) ? $_GET['set_lang'] : 'uz';
    setcookie('lang', $lang_set, time() + 86400 * 30, '/');
    header('Location: ' . strtok($_SERVER['REQUEST_URI'], '?'));
    exit;
}

if (isset($_GET['clear_lang'])) {
    setcookie('lang', '', time() - 3600, '/');
    header('Location: ' . strtok($_SERVER['REQUEST_URI'], '?'));
    exit;
}

$lang = $_COOKIE['lang'] ?? 'uz';

$greetings = [
    'uz' => ['Assalomu alaykum!', 'O\'zbek', 'uz'],
    'ru' => ['Добро пожаловать!', 'Русский', 'ru'],
    'en' => ['Welcome!', 'English', 'en'],
];

$current = $greetings[$lang] ?? $greetings['uz'];
$expire_days = 30;
?>
<!DOCTYPE html>
<html lang="uz">
<head>
<meta charset="UTF-8">
<title>Lab5 – Task 3 – Cookie Til Tanlovi</title>
<style>
    body { font-family: Arial; max-width: 520px; margin: 60px auto; padding: 0 16px; text-align: center; }
    h2 { color: #1F5C99; }
    .greeting { font-size: 2rem; font-weight: bold; color: #1F5C99; margin: 24px 0; padding: 20px; background: #e3f2fd; border-radius: 10px; }
    .lang-btns { display: flex; gap: 12px; justify-content: center; margin-bottom: 24px; flex-wrap: wrap; }
    .lang-btn { padding: 10px 22px; border: 2px solid #1F5C99; border-radius: 6px; text-decoration: none; color: #1F5C99; font-weight: bold; background: #fff; }
    .lang-btn.active { background: #1F5C99; color: #fff; }
    .info-box { background: #f5f8ff; padding: 14px 18px; border-radius: 8px; text-align: left; margin-bottom: 16px; }
    .info-box p { margin: 6px 0; }
    .btn-clear { background: #ffebee; color: #c62828; border: none; padding: 9px 20px; border-radius: 4px; cursor: pointer; font-size: 14px; }
</style>
</head>
<body>

<h2>Til Tanlovi – Cookie</h2>

<div class="lang-btns">
    <a href="?set_lang=uz" class="lang-btn <?= $lang === 'uz' ? 'active' : '' ?>">O'zbek</a>
    <a href="?set_lang=ru" class="lang-btn <?= $lang === 'ru' ? 'active' : '' ?>">Русский</a>
    <a href="?set_lang=en" class="lang-btn <?= $lang === 'en' ? 'active' : '' ?>">English</a>
</div>

<div class="greeting"><?= htmlspecialchars($current[0]) ?></div>

<div class="info-box">
    <p><b>Saqlangan til:</b> <?= htmlspecialchars($current[1]) ?> (<?= htmlspecialchars($current[2]) ?>)</p>
    <p><b>Cookie muddati:</b> <?= $expire_days ?> kun</p>
    <p><b>Cookie nomi:</b> lang</p>
</div>

<form method="GET" action="?clear_lang=1">
    <button type="submit" class="btn-clear">Cookie o'chirish</button>
</form>

</body>
</html>
