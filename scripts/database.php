<?php
$mysql_host = "localhost";
$mysql_user = "root";
$mysql_password = "";
$mysql_database = "clonedb";

$connect = mysql_connect($mysql_host,$mysql_user,$mysql_password) or die(mysql_error());
mysql_select_db($mysql_database);
?>