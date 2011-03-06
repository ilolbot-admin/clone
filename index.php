<?php
ob_start("ob_gzhandler");//compresses page
session_start();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>CloneDev</title>
<style type="text/css">
a.link:link {color:#EEE;}
a.link:visited {color:#800;}
a.link:hover {color:#FFF;}
a.link:active {color:#FFF;}
body{
	color: #BBB;
	font-size: 100%;
	font-family: Arial;
	background-color: #333;
	/*overflow: hidden;*/
}
#divlogin{
	background-color: #444;
	width:300px;
	-moz-border-radius: 15px;
	border-radius: 15px;
	padding:25px;
}
#container{
	font-size:0.875em;
}
.button {
  color: #BBB;
  background: #444;
  border: 1px solid #444;
}
.button:hover {
  background: #3b3b3b;
  cursor: pointer;
}
.post{
	display:block;
	width:40%;
    -moz-border-radius: 15px;
    border-radius: 15px;
    background-color: #444;
	text-align:left;
    padding: 15px;
}
</style>
<script type="text/javascript">
	var sendLogin=getXmlHttpRequestObject();
	var sendReq=getXmlHttpRequestObject();
	var sendOn=getXmlHttpRequestObject();
	var receiveReq=getXmlHttpRequestObject();
	
	var lastMessageId = 0;
	
	var getPostTimer;
	var onlineTimer;
	
	function getXmlHttpRequestObject(){if(window.XMLHttpRequest)return new XMLHttpRequest();else if(window.ActiveXObject)return new ActiveXObject("Microsoft.XMLHTTP");else{alert('Cound not create XmlHttpRequest Object. Please consider upgrading your browser.');return null;}}
	function login() {
		document.getElementById('divloginwrapper').style.display = "none";
		document.getElementById('divchat').style.display = "inline";
		
		var username = document.getElementById('username').value;
		
		sendReq.open("POST","scripts/login.php",true);
		sendReq.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		sendReq.send('username=' + encodeURIComponent(username));
		
		document.getElementById('textareabox').focus();
		
		online();
		getChatText();
	}
	function sendChatText()//Add a message to the chat server.
	{
		document.getElementById('textareabox').value = "";
		document.getElementById("textareabox").focus();
	}
	function getChatText()//Gets the current messages from the server
	{
		receiveReq.open("GET",'scripts/getChat.php?lastMessageId='+lastMessageId,true);
		receiveReq.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		receiveReq.onreadystatechange=handleGetChat;
		receiveReq.send(null);
	}
	function sortAsc(x,y){
		var xId=parseInt(x.getAttribute('id'));
		var yId=parseInt(y.getAttribute('id'));
		return yId-xId;
	}
	function createOut(list, admin)
	{
		var nMessages = list.length;
		var out = "";

		for (var i = 0; i < nMessages; i++) {//print the sorted array
			var msg=list[i];
			var ip = msg.getAttribute('ip');
			var user_name=msg.getElementsByTagName("u");
			var message=msg.getElementsByTagName("t");

			out += "<div class='post'><div style='float:left'>";
			if(ip != "")
				out += "<a class='link' href='scripts/ban.php?ip=" + ip + "'target='_blank'>" +"<b>"+user_name[0].firstChild.nodeValue+"</b></a>";
			else
				out += "<b>"+user_name[0].firstChild.nodeValue+"</b>";
			out += "</div><div style='float:right;color:#888'>" +msg.getAttribute('time');
			
			if(admin == "true")
				out +='<a class="link"style="text-decoration: none;" href="scripts/delete.php?id='+msg.getAttribute('id')+'"target="_blank"> X</a>';
				
			out +="</div><br><br>" +message[0].firstChild.nodeValue+ "</div><br>";
			
			if(parseInt(msg.getAttribute('id'))>lastMessageId)//sets the last message id
				lastMessageId=parseInt(msg.getAttribute('id'));
		}
		return out;
	}
	function handleGetChat()
	{
		if (receiveReq.readyState==4){
			var xmldoc=receiveReq.responseXML;//put xml into var
			
			if(xmldoc == null) {
				//no new messages in all browser but firefox!
				getPostTimer=setTimeout('getChatText();',3000);
				return;
			}
			try {
				//temp fix for firefox's faggotry
				var admin = xmldoc.getElementsByTagName("adm")[0].firstChild.nodeValue;
			}
			catch(err) {
				//no new messages in firefox
				getPostTimer=setTimeout('getChatText();',3000);
				return;
			}

			var divmsg=document.getElementById('divmessages');
			var messages=xmldoc.getElementsByTagName("m");
			var nMessages=messages.length;//amount of messages

			var list=Array();

			for(var i=0;i<nMessages;i++) {
				list[i]=messages[i];
			}//put messages into array
			
			list.sort(sortAsc);//sort the array
			
			var out = createOut(list, admin);

			divmsg.innerHTML = out + divmsg.innerHTML;
			getPostTimer=setTimeout('getChatText();',3000);
		}
	}
	function online()
	{
		sendOn.open("GET",'scripts/online.php',true);
		sendOn.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		sendOn.onreadystatechange=handleOnline;
		sendOn.send(null);
	}
	function handleOnline()
	{
		if (sendOn.readyState==4)
		{
			var xmldoc=sendOn.responseXML;
			var names=xmldoc.getElementsByTagName("u");
			var n_names=names.length;//amount of names
			
			var out="Unique online users: " + n_names + "<br><br>";
			for (var i=0;i<n_names;i++){
				
				var user_name=names[i].childNodes[0].nodeValue;
				out+=user_name+"<br>";
			}
			document.getElementById('divonline').innerHTML=out;
			
			onlineTimer = setTimeout('online();',4000);
		}
	}
	function initUpload()
	{
		document.getElementById('file_upload_form').onsubmit = function()
		{
			document.getElementById('file_upload_form').target = 'upload_target'; //'upload_target' is the name of the iframe
		}
	}
	function refreshPosts()
	{
		//reset everything
		lastMessageId = 0;
		clearTimeout(getPostTimer);
		document.getElementById('divmessages').innerHTML = "";
		
		clearTimeout(onlineTimer);
		document.getElementById('divonline').innerHTML = "";
		
		//load messages
		getChatText();
		online();
	}
		
</script>
</head>

<body onload="initUpload();document.getElementById('username').focus();">
	
	<div id="container">
	<div id="divloginwrapper">
		<center>
			<div id="divlogin">
				<a style="font-size:1.5em;">All U.S. laws apply.</a><br><a href="https://github.com/ilolbot-admin/clone/" class="link" target="_blank">Source</a><br><br>
				Username:&nbsp;<input type="text" id="username" maxlength="15" style="border:0;background-color: #333;color: #BBB">&nbsp;
				<input type="button" value="Ok" class="button" onClick="login();">
			</div>
		</center>
	</div>
	
	<div id="divchat" style="display:none">
		<div style="float: right; text-align: right;display:inline;">
		<input type="button" value="Force refresh" class="button" onclick="refreshPosts()"/><br><br><br>
		<div id="divonline"></div>
		</div>
		
		<div id="divsend">
			<form id="file_upload_form" method="post" enctype="multipart/form-data" action="scripts/sendChat.php">
				<textarea maxlength="1000" rows="10" cols="40" id="textareabox" name="chat_message" style="border:0;background-color: #333;color: #BBB;border: 1px solid #444;overflow:auto;"></textarea><br>
				<input name="file" id="file" size="27" type="file" /><br>
				<input type="submit" name="action" value="Post" class="button" /><br>
				
				<iframe id="upload_target" name="upload_target" src="" style="width:0;height:0;border:0;"></iframe>
			</form>
		</div>
		
		<center>
			<div id="divmessages"></div>
		</center>
	</div>
	</div>
</body>
</html>
