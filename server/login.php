<?php

ob_start();

include('GsDatabase.php');
include('HttpResponse.php');

$action = $_POST["action"];

if ($action !== NULL) {
	if ($action == "login" && $_POST["user"] !== NULL && $_POST["pass"] !== NULL) {
		$user = $_POST["user"];
		$pass = $_POST["pass"];
		$passHash = md5(md5($pass).$passSalt);
		
		$db = new GsDatabase();
		if ($db->gsConnect()) {
			$row = $db->fetchAccountByName($user);
			if ($row) {
				$passHashDb = $row["password_hash"];
				if ($passHashDb == $passHash) {
					$uuid = $row["uuid"];
					if (sessionValid($db)) {
						$response = new HttpResponse("ALREADY_LOGGED_IN");
						echo $response->toString();
					}
					else {
						$days = 1;
						$expire = time() + 60*60*24*$days;
						$sessionID = $db->createSession($uuid);
						setcookie("gsUUID", $uuid, $expire, "/");
						setcookie("gsSessionID", $sessionID, $expire, "/");
						$response = new HttpResponse("LOGIN_SUCCEEDED");
						echo $response->toString();
					}
				}
				else {
					$response = new HttpResponse("PASSWORD_INCORRECT");
					echo $response->toString();
				}
			}
			else {
				$response = new HttpResponse("USERNAME_NOT_FOUND");
				echo $response->toString();
			}
			$db->close();
		}
		else {
			$response = new HttpResponse("DATABASE_UNAVAILABLE");
			echo $response->toString();
		}
	}
	else if ($action == "sessionLogin") {
		$db = new GsDatabase();
		if ($db->gsConnect()) {
			if (sessionValid($db)) {
				$response = new HttpResponse("SESSION_LOGIN_SUCCEEDED");
				echo $response->toString();
			}
			else {
				invalidateSession($db);
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
	else if ($action == "logout") {
		$uuid = $_COOKIE["gsUUID"];
		$session = $_COOKIE["gsSessionID"];
		
		$db = new GsDatabase();
		if ($db->gsConnect()) {
			if (sessionValid($db)) {
				$db->removeSession($session);
				$response = new HttpResponse("LOGOUT_SUCCEEDED");
				echo $response->toString();
			}
			$db->close();
		}
		else {
			$response = new HttpResponse("DATABASE_UNAVAILABLE");
			echo $response->toString();
		}
		setcookie("gsUUID", NULL, time()-1, "/");
		setcookie("gsSessionID", NULL, time()-1, "/");
	}
	else {
		$response = new HttpResponse();
		echo $response->toString();
	}
}

ob_flush();

?>