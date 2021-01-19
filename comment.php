<?php
include_once __DIR__ . "/backend/sql/sql.php";

$sql = new sql();

if (isset($_POST['text']) and !empty($_POST['text'])) {
    $sql->addReaction($_GET['id'], $_POST['text']);
    header("Location: /index.php");
}

echo <<<HTML
<form action="/comment.php?id={$_GET['id']}" method="post">
<textarea name="text" id="" cols="30" rows="10"></textarea>
<input type="submit">
</form>
HTML;
