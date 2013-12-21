
<span style="padding: 16px;">
<?php

$db = new GsDatabase();
if ($db->gsConnect()) {
	$loggedin = sessionValid($db);
	
	$uuid = NULL;
	$user = NULL;
	
	if ($_GET['user'])
	{
		$user = $db->fetchAccountByName($_GET['user']);
		$uuid = $user["uuid"];
	}
	else if ($loggedin) {
		$uuid = $_COOKIE["gsUUID"];
		$user = $db->fetchAccount($uuid);
	}
	
	if ($uuid && $user) {
		$query = $db->executeQuery("SELECT * FROM sessions WHERE account_id = '$uuid'");
		$sessions = $db->fetchAll($query);
		$db->freeQuery($query);
		
		$size = $sessions ? sizeof($sessions) : 0;
		echo $user["username"].' has '.$size.' recent sessions.<br /><br />';
		if ($sessions) {
			foreach ($sessions as $session) {
				echo '
				<span class="roundBorder">
					Session: '.$session["session_id"].'<br />
					Login Time: '.$session["login_time"].'
				</span><br /><br />';
			}
		}
		
		$db->close();
	}
	else {
		echo 'You are not logged in.';
	}
}

?>
</span>
