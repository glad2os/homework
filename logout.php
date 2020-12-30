<?php
unset($_COOKIE['id']);
unset($_COOKIE['login']);
unset($_COOKIE['token']);
setcookie('id', null, -1, '/');
setcookie('login', null, -1, '/');
setcookie('token', null, -1, '/');
header("Location: /"); /* Перенаправление браузера */