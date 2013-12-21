<?php

include("Database.php");

class OciDatabase implements Database
{
	protected $conn = NULL;
	
	public function connect($host, $port, $dbname, $user, $pass, $connStr) {
		$this->conn = @oci_connect($user, $pass, $connStr);
		if (!$this->conn) {
			$this->conn = NULL;
			return FALSE;
		}
		return TRUE;
	}
	public function close() {
		oci_close($this->conn);
		$this->conn = NULL;
	}
	public function getError() {
		$m = oci_error();
		return $m['message'];
	}
	public function createQuery($statement) {
		$res = oci_parse($this->conn, $statement);
		return $res;
	}
	public function executeQuery($statement) {
		$res = oci_parse($this->conn, $statement);
		oci_execute($res);
		return $res;
	}
	public function freeQuery($res) {
		oci_free_statement($res);
	}
	public function fetchAssoc($res) {
		$ary = oci_fetch_assoc($res);
		return $ary;
	}
	public function fetchAll($res) {
		$ary = oci_fetch_all($res);
		return $ary;
	}
	public function execute($res) {
		oci_execute($res);
	}
	public function fetchOneRowEx($statement) {
		$res = oci_parse($this->conn, $statement);
		oci_execute($res);
		$ary = oci_fetch_assoc($res);
		oci_free_statement($res);
		return $ary;
	}
	public function fetchOneRow($table, $key, $value) {
		$res= oci_parse($this->conn, "SELECT * FROM ".$table." WHERE ".$key." = :value");
		oci_bind_by_name($res, ":value", $value);
		oci_execute($res);
		$ary = oci_fetch_assoc($res);
		oci_free_statement($res);
		return $ary;
	}
	public function updateField($table, $key, $value, $updateKey, $newValue) {
		$res = oci_parse($this->conn, "UPDATE ".$table." SET ".$updatekey." = :newValue WHERE ".$key." = :value");
		oci_bind_by_name($res, ":value", $value);
		oci_bind_by_name($res, ":newValue", $newValue);
		oci_execute($res);
		oci_free_statement($res);
	}
};

?>