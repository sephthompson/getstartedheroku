<?php

interface Database
{
	public function connect($host, $port, $dbname, $user, $pass, $connStr);
	public function close();
	public function getError();
	public function createQuery($statement);
	public function executeQuery($statement);
	public function freeQuery($res);
	public function fetchAssoc($res);
	public function fetchAll($res);
	public function execute($res);
	public function fetchOneRowEx($statement);
	public function fetchOneRow($table, $key, $value);
	public function updateField($table, $key, $value, $updateKey, $newValue);
};

?>