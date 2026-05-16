<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

session_start();

if (!isset($_SESSION['users'])) {
    $_SESSION['users'] = [
        ['id' => 1, 'name' => 'Ali Valiyev',    'email' => 'ali@mail.com',    'age' => 22],
        ['id' => 2, 'name' => 'Malika Rahimova', 'email' => 'malika@mail.com', 'age' => 25],
        ['id' => 3, 'name' => 'Sardor Karimov',  'email' => 'sardor@mail.com', 'age' => 19],
    ];
    $_SESSION['next_id'] = 4;
}

$method = $_SERVER['REQUEST_METHOD'];
$id = isset($_GET['id']) ? (int)$_GET['id'] : null;

switch ($method) {
    case 'GET':
        if ($id !== null) {
            $found = null;
            foreach ($_SESSION['users'] as $u) {
                if ($u['id'] === $id) { $found = $u; break; }
            }
            if ($found) {
                echo json_encode(['success' => true, 'data' => $found]);
            } else {
                http_response_code(404);
                echo json_encode(['success' => false, 'message' => "ID=$id topilmadi"]);
            }
        } else {
            echo json_encode(['success' => true, 'data' => array_values($_SESSION['users']), 'count' => count($_SESSION['users'])]);
        }
        break;

    case 'POST':
        $body = json_decode(file_get_contents('php://input'), true);
        $name  = trim($body['name']  ?? '');
        $email = trim($body['email'] ?? '');
        $age   = (int)($body['age'] ?? 0);

        if (strlen($name) < 2 || !filter_var($email, FILTER_VALIDATE_EMAIL) || $age < 1) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Noto\'g\'ri ma\'lumotlar. name (min 2), email, age kerak.']);
            break;
        }

        $new = ['id' => $_SESSION['next_id']++, 'name' => $name, 'email' => $email, 'age' => $age];
        $_SESSION['users'][] = $new;
        http_response_code(201);
        echo json_encode(['success' => true, 'message' => 'Foydalanuvchi qo\'shildi', 'data' => $new]);
        break;

    case 'DELETE':
        if ($id === null) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'ID ko\'rsating: ?id=X']);
            break;
        }
        $found = false;
        $_SESSION['users'] = array_values(array_filter($_SESSION['users'], function($u) use ($id, &$found) {
            if ($u['id'] === $id) { $found = true; return false; }
            return true;
        }));
        if ($found) {
            echo json_encode(['success' => true, 'message' => "ID=$id o'chirildi"]);
        } else {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => "ID=$id topilmadi"]);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Method Not Allowed']);
}
