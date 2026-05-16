<?php
$statuses = [
    200 => ['OK', 'So\'rov muvaffaqiyatli bajarildi', 'green'],
    201 => ['Created', 'Yangi resurs muvaffaqiyatli yaratildi', 'green'],
    204 => ['No Content', 'Muvaffaqiyatli, lekin kontent yo\'q', 'green'],
    301 => ['Moved Permanently', 'Resurs doimiy ravishda ko\'chirilgan', 'blue'],
    302 => ['Found', 'Resurs vaqtincha boshqa manzilda', 'blue'],
    304 => ['Not Modified', 'Keshdan foydalanish mumkin', 'blue'],
    400 => ['Bad Request', 'Noto\'g\'ri so\'rov – server tushunmadi', 'orange'],
    401 => ['Unauthorized', 'Autentifikatsiya talab etiladi', 'orange'],
    403 => ['Forbidden', 'Ruxsat yo\'q – kirish man etilgan', 'orange'],
    404 => ['Not Found', 'Sahifa yoki resurs topilmadi', 'orange'],
    405 => ['Method Not Allowed', 'Bu HTTP metod ruxsat etilmagan', 'orange'],
    429 => ['Too Many Requests', 'Juda ko\'p so\'rov – rate limit', 'orange'],
    500 => ['Internal Server Error', 'Server ichki xatosi yuz berdi', 'red'],
    502 => ['Bad Gateway', 'Noto\'g\'ri gateway javobi', 'red'],
    503 => ['Service Unavailable', 'Server hozir ishlamayapti', 'red'],
];

$code = isset($_GET['status']) ? (int)$_GET['status'] : 200;
if (!isset($statuses[$code])) $code = 200;

http_response_code($code);

$info = $statuses[$code];
$colors = [
    'green'  => ['#2e7d32', '#e8f5e9'],
    'blue'   => ['#0d47a1', '#e3f2fd'],
    'orange' => ['#e65100', '#fff3e0'],
    'red'    => ['#c62828', '#ffebee'],
];
$clr = $colors[$info[2]];

$categories = [
    '2xx Muvaffaqiyat' => [200, 201, 204],
    '3xx Yo\'naltirish' => [301, 302, 304],
    '4xx Mijoz xatosi' => [400, 401, 403, 404, 405, 429],
    '5xx Server xatosi' => [500, 502, 503],
];
?>
<!DOCTYPE html>
<html lang="uz">
<head>
<meta charset="UTF-8">
<title>HTTP <?= $code ?> – <?= htmlspecialchars($info[0]) ?></title>
<style>
    body { font-family: Arial; max-width: 820px; margin: 40px auto; padding: 0 16px; }
    h1 { color: #1F5C99; }
    .big-code { font-size: 7rem; font-weight: bold; text-align: center; color: <?= $clr[0] ?>; background: <?= $clr[1] ?>; border-radius: 16px; padding: 30px; margin: 20px 0; line-height: 1; }
    .name { text-align: center; font-size: 1.6rem; font-weight: bold; color: <?= $clr[0] ?>; }
    .desc { text-align: center; color: #555; font-size: 1rem; margin: 8px 0 28px; }
    .cats { display: flex; gap: 16px; flex-wrap: wrap; margin-top: 24px; }
    .cat { flex: 1; min-width: 170px; background: #f5f8ff; border-radius: 8px; padding: 14px; }
    .cat h3 { margin: 0 0 10px; font-size: 14px; color: #1F5C99; }
    .cat a { display: block; padding: 5px 8px; border-radius: 4px; text-decoration: none; color: #333; font-size: 13px; margin-bottom: 4px; }
    .cat a:hover { background: #e3f2fd; }
    .cat a.active { background: #1F5C99; color: #fff; font-weight: bold; }
</style>
</head>
<body>
<h1>HTTP Status Kodlar</h1>

<div class="big-code"><?= $code ?></div>
<div class="name"><?= htmlspecialchars($info[0]) ?></div>
<div class="desc"><?= htmlspecialchars($info[1]) ?></div>

<div class="cats">
    <?php foreach ($categories as $catName => $codes): ?>
    <div class="cat">
        <h3><?= $catName ?></h3>
        <?php foreach ($codes as $c): ?>
        <a href="?status=<?= $c ?>" class="<?= $c === $code ? 'active' : '' ?>">
            <?= $c ?> <?= htmlspecialchars($statuses[$c][0]) ?>
        </a>
        <?php endforeach; ?>
    </div>
    <?php endforeach; ?>
</div>

</body>
</html>
