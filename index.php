<?php include('config.php'); ?>
<?php include('server/GsDatabase.php'); ?>
<?php include('server/Template.php'); ?>

<!DOCTYPE html>

<html>
<head>
	<?php include($STYLE_PATH."/stylesheets.html"); ?>
	<script language="javascript" src="utils.js"></script>
	<script language="javascript" src="server/ajax.js"></script>
	<script language="javascript" src="server/login.js"></script>
</head>
<body>
	<?php include('shortcutBar.html'); ?>
	<span class="siteFrame">
		<?php include("getPage.php"); ?>
	</span>
</body>
</html>