<?php
session_start();
ob_start("ob_gzhandler");
require('database.php');

header("content-type: text/xml");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Cache-Control: no-cache");

if(isset($_GET['lastMessageId']))
{
	$last_message = $_GET['lastMessageId'];
	$last_message = mysql_real_escape_string($last_message);

	$sql="SELECT * FROM message WHERE message_id > ".$_GET['lastMessageId'];
	$query=mysql_query($sql)or die(mysql_error());
	
	if(mysql_num_rows($query) > 0){
		$xml='<?xml version="1.0" ?><r>';

		if($_SESSION['admin'] == true)
			$xml.='<adm>true</adm>';
		else
			$xml.='<adm>false</adm>';
			
		while($row=mysql_fetch_assoc($query))
		{
			$xml.='<m id="'.$row['message_id'].'" time="'. $row['time_sent'] .'" ';
			if($_SESSION['admin'] == true)
					$xml.=' ip="'.$row['user_ip'].'">';
				else
					$xml.=' ip="">';
			$xml.='<u><![CDATA['.$row['user_name'].']]></u>';
			if($row['image'] != "")
				$xml.='<t><![CDATA[<a href="scripts/images/'.$row['image'].'"target="_blank"><img src="scripts/thumbs/'.$row['image'].'"/ style="border:0;"></a><p>'.$row['chat_message'].'</p>]]></t>';
			else
				$xml.='<t><![CDATA['.$row['chat_message'].']]></t>';
			$xml.='</m>';
		}
		$xml.='</r>';

		echo $xml;
	}
}
else
	echo "wat";
?>