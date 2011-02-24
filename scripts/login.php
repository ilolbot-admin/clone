<?php
session_start();

$adminpass = "password";//make it complexe, must be no longer than 15 characters


$username = $_POST['username'];

if($username == $adminpass) {
	$_SESSION['admin'] = true;
	$_SESSION['user_name'] = "<b><a style='color:#363'>Admin</a></b>";
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