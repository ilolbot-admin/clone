<?php
require('database.php');
session_start();

if($_SESSION["admin"]) {
	$id = $_GET['id'];
	$id = mysql_real_escape_string($id);
	
	$query = mysql_query("SELECT * FROM message WHERE message_id=$id") or die( mysql_error());
	$rows = mysql_fetch_assoc($query);

	if(mysql_num_rows($query) == 1) {
		mysql_query("DELETE FROM message WHERE message_id='". $rows['message_id'] ."' LIMIT 1") or die(mysql_error());
		if($rows['image'] != NULL) {
			unlink("images/" .$rows['image']);
			unlink("thumbs/" .$rows['image']);
		}
	
	
		echo "<h1>U WIN A COOKIE LOL!</h1>";
	}
	else
		echo "<h6>POST DOESNT EXIST LOL!</h6>";
}
else
	echo "<h6>U NO ADMIN LOL!</h6>";



?>