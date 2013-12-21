<?php

include("db/PgDatabase.php");

function str2hex($str) {
	$hex="";
	for ($i=0; $i < strlen($str); $i++)
		$hex .= dechex(ord($str[$i]));
	return $hex;
}
function hex2str($hex) {
	$str="";
	for ($i=0; $i < strlen($hex)-1; $i+=2)
		$str .= chr(hexdec($hex[$i].$hex[$i+1]));
	return $str;
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
	
	public function fetchSession($uuid, $session) {
		return $this->fetchOneRowParams("
			SELECT * FROM sessions
			WHERE session_id = $1
			AND account_id = $2", array($session, $uuid));
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
		$title = "encode(decode('".str2hex($title)."', 'hex'), 'escape')";
		$this->updateFieldEx("accounts", "uuid", $uuid, "title", $title);
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
}

?>