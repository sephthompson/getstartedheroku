<?php

ob_start();

include('GsDatabase.php');
include('HttpResponse.php');

$action = $_POST["action"];

if ($action !== NULL) {
	if ($action == "search" && ($_POST["tags"] !== NULL || $_POST["location"] !== NULL)) {
		$tags = $_POST["tags"];
		$location = $_POST["location"];
		
		$db = new GsDatabase();
		if ($db->gsConnect()) {
			$query = $db->executeQuery("SELECT * FROM projects");
			$rows = $db->fetchAll($query);
			$db->freeQuery($query);
			if ($rows) {
				$response = new HttpResponse("SEARCH_SUCCEEDED");
				foreach ($rows as $row) {
					$poster = $db->fetchAccount($row["poster_id"]);
					$tags = $db->fetchRows("project_tags", "project_id", $row["uuid"]);
					ob_start();
					include("../searchResultProject.html");
					$text = ob_get_clean();
					$response->appendBody($text);
					
				}
				echo $response->toString();
			}
			else {
				$response = new HttpResponse("NO_RESULTS");
				echo $response->toString();
			}
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