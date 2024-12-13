<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

$db_server = "127.0.0.1";
$db_user = "root";
$db_pass = "";
$db_name = "data_users";

$connect = mysqli_connect($db_server, $db_user, $db_pass, $db_name);

if (!$connect) {
    echo json_encode(['status' => 'error', 'message' => 'Connection failed: ' . mysqli_connect_error()]);
    exit;
}

mysqli_set_charset($connect, "utf8mb4");

$data = json_decode(file_get_contents('php://input'), true);
$itemId = $data['id'];
$userLogin = $_SESSION['user_login']; 

if (!$userLogin) {
    echo json_encode(['status' => 'error', 'message' => 'Пользователь не авторизован']);
    exit;
}

$check_user_sql = "SELECT id FROM users WHERE Login = '$userLogin'";
$user_result = mysqli_query($connect, $check_user_sql);

if (mysqli_num_rows($user_result) == 0) {
    echo json_encode(['status' => 'error', 'message' => 'Пользователь не найден']);
    exit;
}

$user_id = mysqli_fetch_assoc($user_result)['id'];

$check_item_sql = "SELECT id FROM shop WHERE id = $itemId";
$item_result = mysqli_query($connect, $check_item_sql);

if (mysqli_num_rows($item_result) == 0) {
    echo json_encode(['status' => 'error', 'message' => 'Товар не найден']);
    exit;
}

$check_purchase_sql = "SELECT id FROM purchases WHERE user_id = $user_id AND item_id = $itemId";
$purchase_result = mysqli_query($connect, $check_purchase_sql);

if (mysqli_num_rows($purchase_result) > 0) {
    echo json_encode(['status' => 'error', 'message' => 'Этот товар уже куплен']);
    exit;
}

$insert_purchase_sql = "INSERT INTO purchases (user_id, item_id) VALUES ($user_id, $itemId)";
if (mysqli_query($connect, $insert_purchase_sql)) {
    echo json_encode(['status' => 'success', 'message' => 'Товар успешно куплен']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Ошибка при покупке товара: ' . mysqli_error($connect)]);
}

mysqli_close($connect);
?>


