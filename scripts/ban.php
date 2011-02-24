<?php
require('database.php');
session_start();

if($_SESSION["admin"]) {
	$ip = $_GET['ip'];
	$ip = mysql_real_escape_string($ip);
	
	$query = mysql_query("SELECT * FROM banned WHERE user_ip=\"$ip\"") or die( mysql_error());
	$rows = mysql_fetch_assoc($query);
	if($rows > 0)
		echo "<h6>ALREADY BANNED LOL!</h6>";
	else {
		mysql_query("INSERT INTO banned (user_ip) VALUES ('$ip')");
		echo "<h1>U WIN A COOKIE LOL!</h1>";
	}
}
else
	echo "<h6>U NO ADMIN LOL!</h6>";



?>