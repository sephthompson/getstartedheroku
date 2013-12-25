<?php

ob_start();

include('GsDatabase.php');
include('HttpResponse.php');

$action = $_POST["action"];

if ($action !== NULL) {
	if ($action == "tagSearchGuess" && $_POST["text"] !== NULL && $_POST["callback"] !== NULL) {
		$text = $_POST["text"];
		$callback = $_POST["callback"];
		$limit = 5;
		
		if ($_POST["limit"] !== NULL)
			$limit = (int)$_POST["limit"];
		
		$db = new GsDatabase();
		if ($db->gsConnect()) {
			// $text = "decode('".str2hex($text)."', 'hex')";
			$query = $db->executeQuery(
				"SELECT *,
					CASE
						WHEN title LIKE '$text%' THEN 1
						WHEN title LIKE '%$text%' THEN 2
					END AS priority
				FROM tags
					WHERE title LIKE '$text%'
					OR    title LIKE '%$text%'
				ORDER BY priority
				LIMIT ".$limit
			);
			$rows = $db->fetchAll($query);
			$db->freeQuery($query);
			if ($rows) {
				$response = new HttpResponse("SEARCH_SUCCEEDED");
				foreach ($rows as $row) {
					$response->appendBody('<a href="javascript:'.$callback.'(\''.$row["uuid"].'\', \''.$row["title"].'\');">'.$row["title"].'</a>');
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