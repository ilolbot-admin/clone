<?php
session_start();
ob_start("ob_gzhandler");
require('database.php');

header ("content-type: text/xml");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Cache-Control: no-cache");

//to send your "online status"
$user_name = $_SESSION['user_name'];
$user_name = mysql_real_escape_string($user_name);//input sanitization, horray!

$loctime = date("Y-m-d H:i:s");
$query = mysql_query("SELECT * FROM people_online WHERE user_name=\"$user_name\"") or die( mysql_error());
$rows = mysql_fetch_assoc($query);
if($rows == 0)
	mysql_query("INSERT INTO people_online (user_name, time) VALUES (\"$user_name\", '$loctime')") or die( "0\t" . mysql_error());
else
	mysql_query("UPDATE people_online SET time='$loctime' WHERE user_name=\"$user_name\"") or die( "1\t" . mysql_error());

//to get the people online:
$query = mysql_query("SELECT * FROM people_online") or die( mysql_error());
$currentTime = date("Y-m-d H:i:s");
$nCurrentTime = strtotime($currentTime);

$xml = '<?xml version="1.0" ?><r>';
while($row = mysql_fetch_assoc($query))
{
	$time = $row['time'];
	$nTime = strtotime($time);

	if (($nTime + 7) > $nCurrentTime) {
		if($row['user_name'] == $_SESSION['user_name'])
			$xml=$xml.'<u><![CDATA[<b>'.$row['user_name'].'</b>]]></u>';
		else
			$xml=$xml.'<u><![CDATA['.$row['user_name'].']]></u>';
	}
	else
		mysql_query("DELETE FROM people_online WHERE user_id=". $row['user_id'] . " LIMIT 1") or die(mysql_error());
}
$xml=$xml.'</r>';
echo $xml;
?>