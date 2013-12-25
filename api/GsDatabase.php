<?php

include("db/PgDatabase.php");

function str2hex($str) {
	$hex="";
	for ($i=0; $i < strlen($str); $i++) {
		$c = dechex(ord($str[$i]));
		if (strlen($c) == 1) $c = "0".$c;
		$hex .= $c;
	}
	return $hex;
}
function hex2str($hex) {
	$str="";
	for ($i=0; $i < strlen($hex)-1; $i+=2)
		$str .= chr(hexdec($hex[$i].$hex[$i+1]));
	return $str;
}

function antiSqlInject($text) {
	return "encode(decode('".str2hex($text)."', 'hex'), 'escape')";
}

class GsDatabase extends PgDatabase
{
	protected $host = "shinra.dyndns-office.com";
	
	protected $ociConnStr = "
		(DESCRIPTION=(ADDRESS_LIST=(ADDRESS=
			(PROTOCOL = TCP)
			(HOST = shinra.dyndns-office.com)
			(PORT = 1521)
		))(CONNECT_DATA=
			(SERVICE_NAME=getStarted)
		))";
	protected $pgConnStr = "";
	
	public function gsConnect() {
		return $this->connect($this->host, 5432, "getstarted", "postgres", "password", $this->pgConnStr);
	}
	public function fetchAccountByName($username) {
		$ary = $this->fetchOneRow("accounts", "username", $username);
		return $ary;
	}
	public function fetchAccount($uuid) {
		$ary = $this->fetchOneRow("accounts", "uuid", $uuid);
		return $ary;
	}
	public function updateAccountField($uuid, $key, $value) {
		$this->updateField("accounts", "uuid", $uuid, $key, $value);
	}
	public function createAccount($username, $passwordHash, $email) {
		$row = $this->insertForResult("accounts",
			array("username", "password_hash", "password_salt", "email"),
			array($username, $passwordHash, $passwordSalt, $email),
			array("uuid"));
		if ($row) {
			return $row["uuid"];
		}
		return FALSE;
	}
	
	public function fetchSession($uuid, $session) {
		return $this->fetchOneRowParams("
			SELECT * FROM sessions
			WHERE session_id = $1
			AND account_id = $2",
			array($session, $uuid));
	}
	public function createSession($uuid) {
		$row = $this->insertForResult("sessions", array("account_id"), array($uuid), array("session_id"));
		if ($row) {
			return $row["session_id"];
		}
		return FALSE;
	}
	public function removeSession($session) {
		$this->removeRow("sessions", "session_id", $session);
	}
	
	public function updateTitle($uuid, $title) {
		$res = $this->executeQueryParams("
			UPDATE accounts
			SET title=".antiSqlInject($title)."
			WHERE uuid = $1",
			array($uuid));
		$num = $this->numRowsAffected($res);
		$this->freeQuery($res);
		return ($num == 1);
	}
	
	public function createEndorsement($poster, $subject, $text) {
		return $this->insertEx("endorsements",
			array("poster_id", "subject_id", "text"),
			array("'".$poster."'", "'".$subject."'", antiSqlInject($text)),
			array("subject_id"));
	}
};

function sessionValid($db) {
	$uuid = $_COOKIE["gsUUID"];
	$session = $_COOKIE["gsSessionID"];
	if ($uuid && $session) {
		$row = $db->fetchSession($uuid, $session);
		if ($row)
			return TRUE;
	}
	return FALSE;
}

function invalidateSession($db) {
	//$uuid = $_COOKIE["gsUUID"];
	//if ($uuid) $db->updateAccountField($uuid, "session_id", NULL);
	setcookie("gsUUID", NULL, time()-1, "/");
	setcookie("gsSessionID", NULL, time()-1, "/");
	/* $cookies = explode(";", $_SERVER["HTTP_COOKIE"]);
	foreach ($cookies as $cookie) {
		$entry = explode("=", $cookie);
		$name = trim($entry[0]);
		$value = $entry[1];
		if ($name == "gsUUID" || $name == "gsSessionID") {
			setcookie($name, NULL, time()-1);
			setcookie($name, NULL, time()-1, "/");
		}
	}*/
}

?>