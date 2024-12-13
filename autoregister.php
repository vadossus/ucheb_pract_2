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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $login = trim(strip_tags($_POST['login']));
    $password = trim(strip_tags($_POST['password']));

    if (!empty($login) && !empty($password)) {
        $sql = "SELECT * FROM users WHERE Login = '$login'";
        $result = mysqli_query($connect, $sql);

        if (mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result);
            if (password_verify($password, $user['Password'])) {
                $_SESSION['user_login'] = $login;
                echo json_encode(['status' => 'success', 'message' => 'Авторизация успешна']);
                exit;
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Неверный пароль']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Пользователь не найден']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Пожалуйста, заполните все поля']);
    }
}

mysqli_close($connect);
?>


