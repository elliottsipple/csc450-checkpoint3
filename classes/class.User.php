<?php

class User {
    public $username;
    public $conn;
    
    function __construct($userID, $conn) {
        $sql = file_get_contents('sql/getUser.sql');
        $params = array(
            ':user_id' => $_SESSION['userID']
        );
        $stmt = $conn->prepare($sql);
        $stmt->execute($params);
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $user = $users[0];
        
        $this->username = $user['username'];
    }
}