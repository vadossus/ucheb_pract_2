<?php
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

$sql = "SELECT id, Name, Description, Price, Image, Purchased FROM shop";
$result = mysqli_query($connect, $sql);

$items = [];
while ($row = mysqli_fetch_assoc($result)) {
    $items[] = $row;
}

echo json_encode($items, JSON_UNESCAPED_UNICODE);

mysqli_close($connect);
?>
