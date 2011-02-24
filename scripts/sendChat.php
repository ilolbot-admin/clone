<?php
session_start();
require('database.php');
require('thumbs.php');

if(isset($_SESSION['user_name']))
{
	$user_name = $_SESSION['user_name'];
	$user_name = mysql_real_escape_string($user_name);
	$chat_message = $_POST['chat_message'];
	$user_ip = $_SERVER['REMOTE_ADDR'];
	$time_sent = date("Y-m-d H:i:s");

	$errorLOL = "";

	$query = mysql_query("SELECT * FROM banned WHERE user_ip='$user_ip'");
	if(mysql_fetch_assoc($query) > 0) //Check if user is banned
		$ban = true;

	$file_uploaded = $_FILES['file']['name'];

	if($ban == false) {
	
		$i=0;
		$spam = 0;
		$post_array=array();

		$result = mysql_query("SELECT * FROM message WHERE user_ip='$user_ip'");

		while($row = mysql_fetch_array($result)){
			$postinfo = $row['chat_message'];
			array_push($post_array, $postinfo);
		}

		while(count($post_array)>$i){

			$Post_old = $post_array[$i];
			$Post_new = $post_array[$i+1];

			similar_text($Post_new, $Post_old, $sim);


			if($sim>75){ //iIf they are the same
				$spam+=1;
				if($spam>3){
					mysql_query("INSERT INTO banned (user_ip) VALUES ('$user_ip')");
					$i = count($post_array);
				}
			}
			$i+=1;
		}
	
	
	
		if(!((strlen($chat_message) > 1000) || (strlen($chat_message) == 0))) {
			$chat_message = htmlentities($chat_message);
			$chat_message = str_replace("\n", "<br>", $chat_message);
			
			/*$bb_tags = array('|\[([bi])\]([^[]+)\[/\\1\]|i');
			$bb_replace = array('<\1>\2</\1>');
			$chat_message = preg_replace($bb_tags, $bb_replace, $chat_message);*/
			//preg_replace("/&gt;(.*?)<br>/i", '<span style="color:#002">&gt;$1</span>', $chat_message);
			
			$chat_message = mysql_real_escape_string($chat_message);
			//createthumb("uploads/cat.jpg","uploads/cat.jpg",100,100);
		}
			$file_id ="file";
			if($_FILES[$file_id]['name']) {
				$types = 'jpg,jpeg,png';
				$uploaddir = 'images/';
				
				$file_title = $_FILES[$file_id]['name'];
				//Get file extension
				$ext_arr = split("\.",basename($file_title));
				$ext = strtolower($ext_arr[count($ext_arr)-1]); //Get the last extension

				//Not really uniqe - but for all practical reasons, it is
				//$uniqer = substr(md5(uniqid(rand(),1)),0,5);
				$file_title = md5_file($_FILES['file']['tmp_name']) . "." . $ext;//Get Unique Name
				$uploadfile = $uploaddir . $file_title;
				
				
				$all_types = explode(",",strtolower($types));
				if($types) {
					if(!file_exists("images/" . $file_title)) {
						if(in_array($ext,$all_types)){
							move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile);
							sleep(1);
							createthumb("images/".$file_title,"thumbs/".$file_title,100,100);
							sleep(1);
							mysql_query("INSERT INTO message (user_name, chat_message, image, user_ip, time_sent) VALUES ('$user_name', '$chat_message', '$file_title', '$user_ip', '$time_sent')") or die('<script type="text/javascript">alert("'. mysql_error() .' LOL!");</script>');
						}
						else
							$errorLOL = 'CANNOT UPLOAD FILE TYPE';
					}
					else
						$errorLOL = 'FILE ALREADY EXISTS';
				}
			}
			else if($chat_message)
				mysql_query("INSERT INTO message (user_name, chat_message, user_ip, time_sent) VALUES ('$user_name', '$chat_message', '$user_ip', '$time_sent')");
			else
				$errorLOL = 'YOU MUST POST SOMETHING';

	}
	else
		$errorLOL = "U BANNED";
	if($errorLOL != "")
		echo '<script type="text/javascript">alert("'. $errorLOL .' LOL!");</script>';
}
?>
