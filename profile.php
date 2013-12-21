<?php

$db = new GsDatabase();
if ($db->gsConnect()) {
	$loggedin = sessionValid($db);
	if ($loggedin)
	{
		$uuid = $_COOKIE["gsUUID"];
		if ($_GET['user'])
			$uuid = $_GET['user'];
		
		$user = $db->fetchAccount($uuid);
		
		$query = $db->executeQuery("
		SELECT * FROM tags WHERE uuid = (
			SELECT tag_id FROM profile_tags
			WHERE account_id = '$uuid')
		LIMIT 10");
		$tags = $db->fetchAll($query);
		$db->freeQuery($query);

		$query = $db->executeQuery("
		SELECT * FROM projects
		WHERE poster_id = '$uuid'");
		$postedProjects = $db->fetchAll($query);
		$db->freeQuery($query);

	/*	$query = $db->executeQuery("
		SELECT * FROM projects
		WHERE taker_id = '$uuid'");
		$respondedProjects = $db->fetchAll($query);
		$db->freeQuery($query);*/
		
		$query = $db->executeQuery("
		SELECT * FROM projects_archived
		WHERE (poster_id = '$uuid')");
		$satisfiedProjects = $db->fetchAll($query);
		$db->freeQuery($query);
		
	/*	$query = $db->executeQuery("
		SELECT * FROM projects_archived
		WHERE (completer_id = '$uuid')");
		$completedProjects = $db->fetchAll($query);
		$db->freeQuery($query);*/
		
		$query = $db->executeQuery("
		SELECT * FROM endorsements
		WHERE (poster_id = '$uuid')");
		$postedEndorsements = $db->fetchAll($query);
		$db->freeQuery($query);
		
		$query = $db->executeQuery("
		SELECT * FROM endorsements
		WHERE (subject_id = '$uuid')");
		$recievedEndorsements = $db->fetchAll($query);
		$db->freeQuery($query);
		
		include("profile.html");
		
		$db->close();
	}
	else {
		echo 'You are not logged in.';
	}
}

?>

