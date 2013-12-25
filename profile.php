<?php

$db = new GsDatabase();
if ($db->gsConnect()) {
	$loggedin = sessionValid($db);
	if ($loggedin)
	{
		$uuid = FALSE;
		$user = FALSE;
		
		if ($_GET["user"]) {
			$name = $_GET["user"];
			$user = $db->fetchAccountByName($name);
			$uuid = $user["uuid"];
		}
		else {
			$uuid = $_COOKIE["gsUUID"];
			$user = $db->fetchAccount($uuid);
		}
		
		if ($user && $uuid) {
			$query = $db->executeQuery("
			SELECT * FROM tags WHERE uuid = (
				SELECT tag_id FROM profile_tags
				WHERE account_id = '$uuid')
			LIMIT 10");
			$tags = $db->fetchAll($query);
			$db->freeQuery($query);

			$query = $db->executeQuery("
			SELECT * FROM projects
			WHERE poster_id = '$uuid'
			LIMIT 4");
			$postedProjects = $db->fetchAll($query);
			$db->freeQuery($query);

		/*	$query = $db->executeQuery("
			SELECT * FROM projects
			WHERE taker_id = '$uuid'");
			$respondedProjects = $db->fetchAll($query);
			$db->freeQuery($query);*/
			
			$query = $db->executeQuery("
			SELECT * FROM projects_archived
			WHERE (poster_id = '$uuid')
			LIMIT 4");
			$satisfiedProjects = $db->fetchAll($query);
			$db->freeQuery($query);
			
		/*	$query = $db->executeQuery("
			SELECT * FROM projects_archived
			WHERE (completer_id = '$uuid')");
			$completedProjects = $db->fetchAll($query);
			$db->freeQuery($query);*/
			
			$query = $db->executeQuery("
			SELECT * FROM endorsements
			WHERE (poster_id = '$uuid')
			LIMIT 4");
			$postedEndorsements = $db->fetchAll($query);
			$db->freeQuery($query);
			
			$query = $db->executeQuery("
			SELECT * FROM endorsements
			WHERE (subject_id = '$uuid')
			LIMIT 4");
			$recievedEndorsements = $db->fetchAll($query);
			$db->freeQuery($query);
			
			include("profile.html");
		}
		else {
			echo 'User not found.';
		}
		
		$db->close();
	}
	else {
		echo 'You are not logged in.';
	}
}

?>

