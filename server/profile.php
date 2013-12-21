<?php

ob_start();

include('GsDatabase.php');
include('HttpResponse.php');

$action = $_POST["action"];

if ($action !== NULL) {
	if ($action == "editTitle" && $_POST["title"] !== NULL) {
		$title = $_POST["title"];
		
		$db = new GsDatabase();
		if ($db->gsConnect()) {
			if (sessionValid($db)) {
				$response = new HttpResponse("SUCCEEDED");
				$db->updateTitle($_COOKIE["gsUUID"], $title);
				echo $response->toString();
			}
			else {
				sessionInvalid($db);
				$response = new HttpResponse("SESSION_INVALID");
				echo $response->toString();
			}
			$db->close();
		}
		else {
			$response = new HttpResponse("DATABASE_UNAVAILABLE");
			echo $response->toString();
		}
	}
}

ob_flush();

?>