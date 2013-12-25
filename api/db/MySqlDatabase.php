<?php

include("Database.php");

class MySqlDatabase implements Database
{
	protected $conn = NULL;
	
	public function connect($host, $port, $dbname, $user, $pass, $connStr) {
		$this->conn = mysql_connect($host.":".$port, $user, $pass);
		if (!$this->conn && mysql_select_db($dbname)) {
			$this->conn = NULL;
			return FALSE;
		}
		return TRUE;
	}
	public function close() {
		mysql_close($this->conn);
		$this->conn = NULL;
	}
	public function getError() {
		$m = mysql_last_error();
		return $m['message'];
	}
	public function createQuery($statement) {
		$res = mysql_prepare($this->conn, $statement);
		return $res;
	}
	public function executeQuery($statement) {
		$res = mysql_query($this->conn, $statement);
		return $res;
	}
	public function freeQuery($res) {
		mysql_free_result($res);
	}
	public function fetchAssoc($res) {
		$ary = mysql_fetch_assoc($res);
		return $ary;
	}
	public function fetchAll($res) {
		$ary = mysql_fetch_all($res);
		return $ary;
	}
	public function execute($res) {
		mysql_execute($this->conn, $res);
	}
	public function fetchOneRowEx($statement) {
		$res = pg_query($this->conn, $statement);
		$ary = pg_fetch_assoc($res);
		mysql_free_result($res);
		return $ary;
	}
	public function fetchOneRow($table, $key, $value) {
		$res = mysql_query_params($this->conn, "SELECT * FROM ".$table." WHERE ".$key." = $1", array($value));
		$ary = mysql_fetch_assoc($res);
		mysql_free_result($res);
		return $ary;
	}
	public function updateField($table, $key, $value, $updateKey, $newValue) {
		$res = mysql_query_params($this->conn, "UPDATE ".$table." SET ".$updateKey." = $1 WHERE ".$key." = $2", array($newValue, $value));
		mysql_free_result($res);
	}
};

?>