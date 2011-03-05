<?php
require('config.php');//for database settings

$connect = mysql_connect($mysql_host,$mysql_user,$mysql_password) or die(mysql_error());
mysql_select_db($mysql_database);
?>