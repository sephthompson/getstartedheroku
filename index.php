<?php include('config.php'); ?>
<?php include('api/utils.php'); ?>
<?php include('api/GsDatabase.php'); ?>

<!DOCTYPE html>

<html>
<head>
	<?php include($STYLE_PATH."/stylesheets.php"); ?>
	<script language="javascript" src="api/ajax.js"></script>
	<script language="javascript" src="config.js"></script>
	<script language="javascript" src="utils.js"></script>
	<script language="javascript" src="login.js"></script>
</head>
<body>
	<?php include('shortcutBar.html'); ?>
	<span class="siteFrame">
		<?php include("getPage.php"); ?>
	</span>
</body>
</html>