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
				if ($db->updateTitle($_COOKIE["gsUUID"], $title)) {
					$response = new HttpResponse("SUCCEEDED");
					echo $response->toString();
				}
				else {
					$response = new HttpResponse("FAILED");
					echo $response->toString();
				}
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
	else if ($action == "postEndorsement" && $_POST["subject"] !== NULL && $_POST["text"] !== NULL) {
		$subject = $_POST["subject"];
		$text = $_POST["text"];
		
		$db = new GsDatabase();
		if ($db->gsConnect()) {
			if (sessionValid($db)) {
				if ($db->createEndorsement($_COOKIE["gsUUID"], $subject, $text)) {
					$response = new HttpResponse("SUCCEEDED");
					echo $response->toString();
				}
				else {
					$response = new HttpResponse("FAILED");
					echo $response->toString();
				}
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