<html>
<head>
	<link rel="stylesheet" type="text/css" href="chat.css"></link>
	<link rel="stylesheet" type="text/css" href="style/main.css"></link>
</head>
<body>
<span class="siteFrame"><center>
<table border="1">
	<tr><th>Config</th></tr>
	<tr>
		<td>
			Name: <input type="text" id="chat_NameText" style="width:256"; />
		</td>
	</tr>
</table>
<br />
<table class="guestChat" border="1">
	<tr><th>Guest Chat</th></tr>
	<tr>
		<td>
			<input type="button" id="chat_Send" value="Send" onClick="ChatMessage();"/>
			<input type="text" id="chat_Message" style="width:512"; />
		</td>
	</tr>
	<tr>
		<td height="512"><div class="chatContent" id="chat_Content"></div></td>
	</tr>
</table>
</center></span>
<script src="ajax.js"></script>
<script src="chat.js"></script>
<script>
InitChat(500);
</script>

</html>