<?php
echo "<pre>";
if($_SERVER['REMOTE_ADDR'] == "127.0.0.1") {
	require('database.php');

	$loctime = date("Y-m-d H:i:s");
	$loctime = strtotime($loctime); // string to seconds since 1970 or something(int)
	$loctime = $loctime - 259200; //set it to 3 days ago

	echo "Starting deleting process, local time: " . date("Y-m-d H:i:s") . ".\n\n";

	$sql = "SELECT * FROM message";
	$query = mysql_query($sql) or die(mysql_error());

	while($row = mysql_fetch_assoc($query))
	{
		if($loctime > strtotime($row['time_sent']))
		{
			mysql_query("DELETE FROM message WHERE message_id=". $row['message_id'] . " LIMIT 1") or die("Unable to delete data from the database!: ".mysql_error());
			if($rows['image'] != NULL) {
				unlink("images/" .$rows['image']);
				unlink("thumbs/" .$rows['image']);
			}
			echo $row['message_id'] . ", " . $row['time_sent'] . " - Deleted,\n";
		}
		else
			echo $row['message_id'] . ", " . $row['time_sent'] . " - New enough,\n";
	}
	echo "\nDone.";
}
else
	echo "Must be localhost.";
echo "</pre>";
?>