<?php

ob_start();

include('GsDatabase.php');
include('HttpResponse.php');

$action = $_POST["action"];

function isValidUsername($user) {
	return preg_match("/^[A-Za-z][A-Za-z0-9_-]{4,31}$/", $user);
}
function isValidPassword($pass) {
	return preg_match("/^.{6,}$/", $user);
}
function isValidEmail($email) {
	return filter_var($email, FILTER_VALIDATE_EMAIL);
}

if ($action !== NULL) {
	if ($action == "register" && $_POST["user"] !== NULL && $_POST["pass1"] !== NULL && $_POST["pass2"] !== NULL && $_POST["email"] !== NULL) {
		$user = $_POST["user"];
		$pass = $_POST["pass1"];
		$pass2 = $_POST["pass2"];
		$email = $_POST["email"];
		$passSalt = (string)time();
		
		$db = new GsDatabase();
		if (!$db->gsConnect()) {
			$response = new HttpResponse("DATABASE_UNAVAILABLE");
			echo $response->toString();
			exit();
		}
		else if ($pass != $pass2) {
			$response = new HttpResponse("PASSWORD_MISMATCH");
			echo $response->toString();
			$db->close();
			exit();
		}
		else if (sessionValid($db)) {
			$response = new HttpResponse("ALREADY_LOGGED_IN");
			echo $response->toString();
			$db->close();
			exit();
		}
		else if ($db->fetchAccountByName($user)) {
			$response = new HttpResponse("USERNAME_TAKEN");
			echo $response->toString();
			$db->close();
			exit();
		}
		else if (!isValidUsername($user)) {
			$response = new HttpResponse("USERNAME_INVALID");
			echo $response->toString();
			$db->close();
			exit();
		}
		else if (!isValidPassword($pass)) {
			$response = new HttpResponse("PASSWORD_INVALID");
			echo $response->toString();
			$db->close();
			exit();
		}
		else if (!isValidEmail($email)) {
			$response = new HttpResponse("EMAIL_INVALID");
			echo $response->toString();
			$db->close();
			exit();
		}
		else {
			$passHash = md5(md5($pass).$passSalt);
			if ($db->createAccount($user, $passHash, $passSalt, $email)) {
				$response = new HttpResponse("REGISTER_SUCCEEDED");
				echo $response->toString();
			}
			else {
				$response = new HttpResponse("REGISTER_FAILED");
				echo $response->toString();
			}
			$db->close();
			exit();
		}
	}
	else {
		$response = new HttpResponse();
		echo $response->toString();
	}
}

ob_flush();

?>