<?php

include("Database.php");

class PgDatabase implements Database
{
	protected $conn = NULL;
	
	public function connect($host, $port, $dbname, $user, $pass, $connStr) {
		$connStr .= " host='".$host."' port='".$port."' dbname='".$dbname."' user='".$user."' password='".$pass."'";
		$this->conn = pg_connect($connStr);
		if (!$this->conn) {
			$this->conn = NULL;
			return FALSE;
		}
		return TRUE;
	}
	public function getConnection() {
		return $this->conn;
	}
	public function close() {
		pg_close($this->conn);
		$this->conn = NULL;
	}
	public function getError() {
		$m = pg_last_error();
		return $m['message'];
	}
	public function createQuery($statement) {
		$res = pg_prepare($this->conn, $statement);
		return $res;
	}
	public function executeQuery($statement) {
		$res = pg_query($this->conn, $statement);
		return $res;
	}
	public function freeQuery($res) {
		pg_free_result($res);
	}
	public function fetchAssoc($res) {
		$ary = pg_fetch_assoc($res);
		return $ary;
	}
	public function fetchAll($res) {
		$ary = pg_fetch_all($res);
		return $ary;
	}
	public function execute($res) {
		pg_execute($this->conn, $res);
	}
	public function fetchRowsEx($statement) {
		$res = pg_query($this->conn, $statement);
		$ary = pg_fetch_all($res);
		pg_free_result($res);
		return $ary;
	}
	public function fetchRows($table, $key, $value) {
		$res = pg_query_params($this->conn, "SELECT * FROM ".$table." WHERE ".$key." = $1", array($value));
		$ary = pg_fetch_all($res);
		pg_free_result($res);
		return $ary;
	}
	public function fetchOneRowEx($statement) {
		$res = pg_query($this->conn, $statement);
		$ary = pg_fetch_assoc($res);
		pg_free_result($res);
		return $ary;
	}
	public function fetchOneRowParams($statement, $params) {
		$res = NULL;
		if ($params) $res = pg_query_params($this->conn, $statement, $params);
		else $res = pg_query($this->conn, $statement);
		$ary = pg_fetch_assoc($res);
		pg_free_result($res);
		return $ary;
	}
	public function fetchOneRow($table, $key, $value) {
		$res = pg_query_params($this->conn, "SELECT * FROM ".$table." WHERE ".$key." = $1", array($value));
		$ary = pg_fetch_assoc($res);
		pg_free_result($res);
		return $ary;
	}
	public function updateField($table, $key, $value, $updateKey, $newValue) {
		$res = pg_query_params($this->conn, "UPDATE ".$table." SET ".$updateKey." = $1 WHERE ".$key." = $2", array($newValue, $value));
		pg_free_result($res);
	}
	public function updateFieldEx($table, $key, $value, $updateKey, $newValue) {
		$res = pg_query_params($this->conn, "UPDATE ".$table." SET ".$updateKey." = $newValue WHERE ".$key." = $1", array($value));
		pg_free_result($res);
	}
	public function removeRow($table, $key, $value) {
		$res = pg_query_params($this->conn, "DELETE FROM ".$table." WHERE ".$key." = $1", array($value));
		pg_free_result($res);
	}
	public function insert($table, $fieldsArray, $valuesArray) {
		$paramsArray = array();
		for ($i=1; $i<=sizeof($valuesArray); $i++)
			array_push($paramsArray, "$".$i);
		
		$fields = "(".implode(", ", $fieldsArray).")";
		$params = "(".implode(", ", $paramsArray).")";
		
		$res = pg_query_params($this->conn, "
			INSERT INTO ".$table." ".$fields."
			VALUES ".$params, $valuesArray);
		
		pg_free_result($res);
	}
	public function insertForResult($table, $fieldsArray, $valuesArray, $resultsArray) {
		$paramsArray = array();
		for ($i=1; $i<=sizeof($valuesArray); $i++)
			array_push($paramsArray, "$".$i);
		
		$fields = "(".implode(", ", $fieldsArray).")";
		$params = "(".implode(", ", $paramsArray).")";
		$results = "(".implode(", ", $resultsArray).")";
		
		$res = pg_query_params($this->conn, "
			INSERT INTO ".$table." ".$fields."
			VALUES ".$params."
			RETURNING ".$results, $valuesArray);
		
		$row = pg_fetch_assoc($res);
		pg_free_result($res);
		return $row;
	}
};

?>