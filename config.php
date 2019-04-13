<?php

// Connect to the Oracle database
$tns = "
(DESCRIPTION =
  (ADDRESS = (PROTOCOL = TCP)(HOST = CITDB.NKU.EDU)(PORT = 1521))
  (CONNECT_DATA =
    (SERVER = DEDICATED)
    (SERVICE_NAME = csc450.citdb.nku.edu)
  )
)";
$db_username = "SIPPLEE1";
$db_password = "csc684";

try {
  $conn = new PDO("oci:dbname=".$tns,$db_username,$db_password);
  echo 'Successfully connected to Oracle.';
} catch(PDOException $e) {
  echo ($e->getMessage());
}

// autoload classes
function my_autoloader($class) {
		include 'classes/class.' . $class . '.php';
	}
	spl_autoload_register('my_autoloader');

// Start the session
session_start();

$current_url = basename($_SERVER['REQUEST_URI']);

// if username is not set in the session and current URL not login.php redirect to login page
if (!isset($_SESSION["userID"]) && $current_url != 'login.php') {
  header("Location: login.php");
}

// Else if session key username is set get $user from the database
elseif (isset($_SESSION["userID"])) {
	// new User
  $user = new User($_SESSION["userID"], $conn);
}
