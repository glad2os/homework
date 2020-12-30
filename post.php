<?php
include_once __DIR__ . "/backend/sql/sql.php";
$sql = new sql();

if (
    (isset($_POST['title']) && !empty($_POST['title'])) &&
    (isset($_POST['text']) && !empty($_POST['text']))
) {
    $sql->addThread($_POST['title'], $_POST['text']);
    $_POST = array();
    echo "Статья добавлена! <br> <a href='/'>Вернуться</a>";
    die();
}
if (!$sql->authenticationNoMd5($_COOKIE['login'], $_COOKIE['token'])) {
    echo <<<HTML
<h1>403</h1>
<p>Forbidden</p>
HTML;

    die();
}

echo <<<HTML
<form action="post.php" method="post">
    <input type="text" placeholder="Заголовок" name="title"> <br>
    <textarea name="text">
        Текст
    </textarea> <br>
    <input type="submit">
</form>
HTML;
