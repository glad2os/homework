<?php
include_once __DIR__ . "/sql/sql.php";
include_once __DIR__ . "/auth.php";
$sql = new sql();

header('Content-Type: application/json');
$request = json_decode(file_get_contents("php://input"), true);

$login = $request['login'];
$passwd = $request['password'];

if (empty($login) && strlen($login) < 3) http_response_code(403);
if (empty($passwd) && strlen($login) < 3) http_response_code(403);

if ($sql->getUserId($login) == NULL) {
    $sql->registerUser($login, $passwd);
    http_response_code(201);
} else {
    if ($sql->authentication($login, $passwd)) {
        http_response_code(204);
        setcookie('id', $sql->getUserId($login), time() + 86400, '/');
        setcookie('login', $login, time() + 86400, '/');
        setcookie('token', md5($passwd), time() + 86400, '/');
    } else {
        http_response_code(403);
        print json_encode(['message' => 'Логин или пароль неверный']);
    }
}