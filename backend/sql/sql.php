<?php


class sql extends mysqli
{

    /**
     * sql constructor.
     */
    public function __construct($host = "localhost", $username = "forum", $password = "123", $database = "forum", $port = null, $socket = null)
    {
        parent::__construct($host, $username, $password, $database, $port, $socket); // TODO: Change the autogenerated stub
        $this->set_charset('utf8');
    }

    public function registerUser($login, $password)
    {
        $stmt = $this->prepare("insert into users (login, passwd) value (?, md5(?))");
        $stmt->bind_param("ss", $login, $password);
        $stmt->execute();
        if ($stmt->errno != 0) throw new RuntimeException($stmt->error, $stmt->errno);
        $result = $stmt->insert_id;
        $stmt->close();
        return $result;
    }

    public function getUserId($login)
    {
        $stmt = $this->prepare("select id from users where login = ?");
        $stmt->bind_param("s", $login);
        $stmt->execute();
        if ($stmt->errno != 0) throw new RuntimeException($stmt->error, $stmt->errno);
        $result = $stmt->get_result()->fetch_array(MYSQLI_NUM)[0];
        $stmt->close();
        return $result;
    }

    public function getUserByID($id)
    {
        $stmt = $this->prepare("select login from users where id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        if ($stmt->errno != 0) throw new RuntimeException($stmt->error, $stmt->errno);
        $result = $stmt->get_result()->fetch_array(MYSQLI_NUM)[0];
        $stmt->close();
        return $result;
    }

    public function authentication($login, $password)
    {
        $stmt = $this->prepare("select count(*) from users where login = ? and passwd = md5(?)");
        $stmt->bind_param("ss", $login, $password);
        $stmt->execute();
        if ($stmt->errno != 0) throw new RuntimeException($stmt->error, $stmt->errno);
        $result = (bool)$stmt->get_result()->fetch_array(MYSQLI_NUM)[0];
        $stmt->close();
        return $result;
    }

    public function authenticationNoMd5($login, $password)
    {
        $stmt = $this->prepare("select count(*) from users where login = ? and passwd = ?");
        $stmt->bind_param("ss", $login, $password);
        $stmt->execute();
        if ($stmt->errno != 0) throw new RuntimeException($stmt->error, $stmt->errno);
        $result = (bool)$stmt->get_result()->fetch_array(MYSQLI_NUM)[0];
        $stmt->close();
        return $result;
    }

    public function getAllThreadsWithnNoModerated()
    {
        $stmt = $this->prepare("SELECT * FROM threads where moderated = 0 ORDER BY time DESC");
        $stmt->execute();
        if ($stmt->errno != 0) throw new RuntimeException($stmt->error, $stmt->errno);
        $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $result;
    }

    public function getAllThreadsModerated()
    {
        $stmt = $this->prepare("SELECT * FROM threads where moderated = 1 ORDER BY time DESC");
        $stmt->execute();
        if ($stmt->errno != 0) throw new RuntimeException($stmt->error, $stmt->errno);
        $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $result;
    }

    public function addThread($title, $text)
    {
        $stmt = $this->prepare("INSERT INTO threads (title, text, owner_id) VALUES (?,?,?)");
        $stmt->bind_param("ssi", $title, $text, $_COOKIE['id']);
        $stmt->execute();
        if ($stmt->errno != 0) throw new RuntimeException($stmt->error, $stmt->errno);
        $output = $stmt->insert_id;
        $stmt->close();
        return $output;
    }

    public function getAuthorsOfThread($threadId)
    {
        $stmt = $this->prepare("select login from users u left join threads t on u.id = t.owner_id where t.id = ?");
        $stmt->bind_param("i", $threadId);
        $stmt->execute();
        if ($stmt->errno != 0) throw new RuntimeException($stmt->error, $stmt->errno);
        $result = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $result;
    }

    public function moderatePost($id)
    {
        $stmt = $this->prepare("UPDATE `threads` SET `moderated`= 1 WHERE `id`= ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        if ($stmt->errno != 0) throw new RuntimeException($stmt->error, $stmt->errno);
        $stmt->close();
    }
}