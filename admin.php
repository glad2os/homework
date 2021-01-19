<?php

if (!(isset($_GET['login']) or isset($_GET['password']))) {
    echo <<<HTML
<form action="admin.php" method="get">
<input type="text" name="login" value="admin"><input type="password"  value="" name="password"><input type="submit">
</form>
HTML;

    die();
}

if (!($_GET['login'] == "admin" and $_GET['password'] == "")) {
    echo "Пароль неверный";
    die;
}

include_once __DIR__ . "/backend/sql/sql.php";

$sql = new sql();

if (!isset($_GET['id'])) {

    $getAllThreadsNoModerated = $sql->getAllThreadsNoModerated();
    echo "Вы вошли как администратор <br>";
    echo "<a href='logout.php'>Выйти</a> <br>";

    echo "<h1>Все посты требуемые проверку:</h1>";

    foreach ($getAllThreadsNoModerated as $item) {

        print "Автор: " . $sql->getAuthorsOfThread($item['id'])["login"] . " | " . $item["time"] . "<br>";
        print "<h2>{$item['title']}</h2>";
        print <<<HTML
    <p>
        {$item['text']}
    </p> <br>
    <hr style="width: 50%">
    <a href="?login=admin&password=&id={$item['id']}">Разрешить публикацию</a>
HTML;

    }
} else {
    echo "Пост опубликован";
    $sql->moderatePost($_GET['id']);
    header("Location: /admin.php?login=admin&password=");
}