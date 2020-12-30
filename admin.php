<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);


if (!($_GET['login'] == "admin" && $_GET['password'] == "123")) {
    echo <<<HTML
<form action="admin.php" method="get">
    <input type="text" name="login" placeholder="login"><input type="password" name="password" placeholder="password">
    <input type="submit">
</form>
HTML;

    die();
}

include_once __DIR__ . "/backend/sql/sql.php";

$sql = new sql();

if (!empty(json_decode(file_get_contents("php://input"), true))) {
    $content = json_decode(file_get_contents("php://input"),true);
    $sql->moderatePost($content['id']);
    die();
}


$getAllThreadsWithnNoModerated = $sql->getAllThreadsWithnNoModerated();
echo "<h1>Ожидают модерацию</h1> <br>";

$token = md5($_GET['password']); //PASSWD

foreach ($getAllThreadsWithnNoModerated as $item) {

    print "Автор: " . $sql->getAuthorsOfThread($item['id'])["login"] . "<br>";
    print <<<HTML
    <textarea name="text">
        {$item['text']}
    </textarea> <br>
    
HTML;

    print $item['time'] . "<br> <hr>";
    print <<<HTML
<a href="#" onclick="applypost({$item['id']});">Разрешить публикацию</a> <br>
HTML;

}

?>

<script>
    function request(target, body, callback = nop, fallbackCallback = e => alert(e["issueMessage"])) {
        const request = new XMLHttpRequest();
        request.open("POST", `/` + target, true);
        request.setRequestHeader('Content-Type', 'application/json; charset=UTF-8');
        request.responseType = "json";
        request.onreadystatechange = () => {
            if (request.readyState === XMLHttpRequest.DONE) {
                if (request.status === 200) callback(request.response);
                if (request.status === 204) callback(request.response);
                else fallbackCallback(request.response);
            }
        };
        request.send(JSON.stringify(body));
    }

    function applypost(id) {
        request('admin.php?login=admin&password=123', {
            "id": id
        }, () => {
            document.location.reload();
        });
    }
</script>
