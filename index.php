<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

include_once __DIR__ . "/backend/sql/sql.php";

if (!isset($_COOKIE['id']) || !isset($_COOKIE['login']) || !isset($_COOKIE['token'])) {
    echo <<<HTML
<p>Форма регистрации</p>

<form action="" method="post">
    <input type="text" name="login" placeholder="login"><input type="password" name="password" placeholder="passwd">
    <input type="button" value="Отправить запрос" onclick="reg(this.form)">    
</form>

<script src="assets/api.js"></script>
HTML;

    die();
}

$sql = new sql();

$getAllThreadsModerated = $sql->getAllThreadsModerated();
echo "Вы вошли как " . $sql->getUserByID($_COOKIE['id']) . "<br>";
echo "<a href='logout.php'>Выйти</a> <br>";

echo "<h1>Все посты</h1>";

foreach ($getAllThreadsModerated as $item) {

    print "Автор: " . $sql->getAuthorsOfThread($item['id'])["login"] . "<br>";
    print <<<HTML
    <textarea name="text">
        {$item['text']}
    </textarea> <br>
    
HTML;

}
