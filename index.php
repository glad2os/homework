<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

include_once __DIR__ . "/backend/sql/sql.php";

$sql = new sql();


if (!isset($_COOKIE['id']) || !isset($_COOKIE['login']) || !isset($_COOKIE['token'])) {
    echo <<<HTML
  <h3><a href="/admin.php">Админ панель</a></h3>

<p>Форма регистрации</p>

<form action="" method="post">
    <input type="text" name="login" placeholder="login"><input type="password" name="password" placeholder="passwd">
    <input type="button" value="Отправить запрос" onclick="reg(this.form)">    
</form>
<script src="assets/api.js"></script>



HTML;

    echo "<h1>Все посты</h1>";

    $getAllThreadsModerated = $sql->getAllThreadsModerated();

    foreach ($getAllThreadsModerated as $item) {

        print "Автор: " . $sql->getAuthorsOfThread($item['id'])["login"] . "<br>";
        print "<h2>{$item['title']}</h2>";
        print <<<HTML
    <p>
        {$item['text']}
    </p> 
    <hr style="width: 50%"><br>
    
HTML;

    }

    die();
}


$getAllThreadsModerated = $sql->getAllThreadsModerated();
echo "Вы вошли как " . $sql->getUserByID($_COOKIE['id']) . "<br>";
echo "<a href=\"/post.php\">Написать статью</a> <br>";
echo "<a href='logout.php'>Выйти</a> <br>";

echo "<h1>Все посты</h1>";

foreach ($getAllThreadsModerated as $item) {

    print "Автор: " . $sql->getAuthorsOfThread($item['id'])["login"] . " | {$item['time']}<br>";
    print "<h2>{$item['title']}</h2>";
    print  <<<HTML
    <p>
        {$item['text']}
    </p> 
    <strong>Комментарии</strong> <br>    <br>
    <a href="/comment.php?id={$item['id']}">Написать комментарий</a> <br><br>
HTML;

    foreach ($sql->getAllReactions($item['id']) as $reaction) {
        echo "user: " . $sql->getUserByID($reaction['author_id']) . " | {$reaction['time']} <br>";
        echo $reaction['text'] . "<br><br>";
    }
    echo "<hr>";
}
