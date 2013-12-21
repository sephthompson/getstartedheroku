<?php

$page = $_GET["p"];

$maybeLoggedIn = $_COOKIE["gsUUID"] && $_COOKIE["gsSessionID"];

if ($page !== NULL) {
	if ($page == "profile") {
		include("profile.php");
	}
	else if ($page == "search") {
		include("search.html");
	}
	else if ($page == "endorse") {
		include("endorse.html");
	}
	else if ($page == "postproject") {
		include("postProject.html");
	}
	else if ($page == "sessions") {
		include("sessions.php");
	}
}
else {
	include("home.html");
}

?>