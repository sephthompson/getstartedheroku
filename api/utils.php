<?php

function shortTime($time) {
	return date_format(date_create($time), "Y-m-d");
}

?>