<?php
session_start();
require('config.php');//for $adminpass


$username = $_POST['username'];

if($username == $adminpass) {
	$_SESSION['admin'] = true;
	$_SESSION['user_name'] = "<b><span style='color:#363'>Admin</a></b>";
}
else if(strlen($username) > 15 || strlen($username) == 0) {
	$_SESSION['admin'] = false;
	$_SESSION['user_name'] = "Anonymous";
}
else {
	$_SESSION['admin'] = false;
	$username = htmlentities($username);
	$_SESSION['user_name'] = $username;
}

echo $_SESSION['user_name'];
?>