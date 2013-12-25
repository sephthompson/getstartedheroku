<?php

$page = $_GET["p"];

if ($page !== NULL) {
	if ($page == "register") {
		include("register.html");
	}
	else if ($page == "profile") {
		include("profile.php");
	}
	else if ($page == "search") {
		include("search.html");
	}
	else if ($page == "sessions") {
		include("sessions.php");
	}
}
else {
	include("home.html");
}

?>