<?php

// include configuration file with database connection
include('config.php');

// if form is submitted
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    // get username and password from form as variables
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query records that have usernames and passwords that match those in users table
    $sql = file_get_contents('sql/attemptLogin.sql');
    $params = array(
        ':username' => $username,
        ':password' => $password
    );
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);
    $users = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // if users is not empty
    if(!empty($users)) {
        // set $user equal to the first result of $users
        $user = $users[0];
        
        // set a session variable with a key of username equal to the username returned
        $_SESSION['userID'] = $user['username'];

        // redirect to user's index file
        if ($username == 'producer') {
            header('location: producer.php');
        } else if ($username == 'dealer') {
            header('location: dealer.php');
        } else if ($username == 'marketer') {
            header('location: marketer.php');
        } else {
            header('location: customer.php');
        }
    } else {
        echo 'Invalid username or password';
    }
}

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        
        <title>Login</title>
        
        <link rel="stylesheet" href="css/style.css">
    </head>
    <body>
        <div class="page">
            <h1>Login</h1>
            <form method="POST">
                <!--Gather username and password data from user to attemp login-->
                <input type="text" name="username" placeholder="Username" />
                <input type="password" name="password" placeholder="Password" />
                <input type="submit" value="Log In" />
            </form>
        </div>
    </body>
</html>