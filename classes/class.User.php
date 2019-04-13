<?php

class User {
    public $username;
    public $conn;
    
    function __construct($userID, $conn) {
        $sql = file_get_contents('sql/getUser.sql');
        $params = array(
            'username' => $_SESSION["userID"]
        );
        $statement = $conn->prepare($sql);
        $statement->execute($params);
        $users = $statement->fetchAll(PDO::FETCH_ASSOC);
        $user = $users[0];
        
        $this->username = $user['username'];
    }
}