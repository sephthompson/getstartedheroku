<?php

include("server/GsDatabase.php");

$db = new GsDatabase();
$db->gsConnect();

$account = $db->fetchAccountByName("Omniscience");
$uuid = $account["uuid"];



$db->close();

?>