<?php

include("GsDatabase.php");
include("HttpResponse.php");

function array_push_assoc(&$arr) {
	$args = func_get_args();
	foreach ($args as $arg) {
		if (is_array($arg)) {
			foreach ($arg as $key => $value) {
				$arr[$key] = $value;
			}
		}
	}
}

class UserDataResponse extends HttpResponse
{
	public $user = array();
	
	public function __construct()
	{
		
	}
	public function toString()
	{
		$response = parent::toArray();
		array_push_assoc($response, array(
			"user" => $this->user
		));
		return json_encode($response);
	}
};

$action = $_POST["action"];

if ($action !== NULL) {
	if ($action == "getAccountByName" && $_POST["user"] !== NULL) {
		$user = $_POST["user"];
		
		$db = new GsDatabase();
		if ($db->gsConnect()) {
			if (sessionValid($db)) {
				$response = new UserDataResponse();
				$response->user = $db->fetchAccountByName($user);
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
	else if ($action == "getAccount") {
		$user = $_POST["uuid"];
		if ($uuid == NULL) $uuid = $_COOKIE["gsUUID"];
		if ($uuid == NULL) {
			$response = new HttpResponse("UUID_NOT_SUPPLIED");
			echo $response->toString();
		}
		else {

			$db = new GsDatabase();
			if ($db->gsConnect()) {
				if (sessionValid($db)) {
					$response = new UserDataResponse();
					$response->user = $db->fetchAccount($uuid);
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
	else {
		$response = new HttpResponse();
		echo $response->toString();
	}
}

?>