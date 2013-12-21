<?php

ob_start();

include('GsDatabase.php');
include('HttpResponse.php');

$action = $_POST["action"];

if ($action !== NULL) {
	if ($action == "runScript" && $_POST["src"] !== NULL) {
		$src = $_POST["src"];
		
		$db = new GsDatabase();
		if ($db->gsConnect()) {
			$response = new HttpResponse("SUCCESS");
			
			ob_start();
			include("../".$src);
			$text = ob_get_clean();
			$response->appendBody($text);
			
			echo $response->toString();
			
			$db->close();
		}
		else {
			$response = new HttpResponse("DATABASE_UNAVAILABLE");
			echo $response->toString();
		}
	}
}

ob_flush();

?>