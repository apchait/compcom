<?php
	include('connect.php');
	$link = connectToDb();
	$fields = "";
	foreach($_GET["fields"] as $i => $field){
		$fields .=  $field . ", ";
	}
	$fields[strlen($fields) - 2] = "";
	$table = $_GET['table'];
	$query1 = "SELECT " . $fields . " FROM " . $table;
	$query2 = "SELECT pr_name, pr_code, pr_community, pr_certification, pr_id FROM producer";
	
	$result = mysql_query($query2,$link) or die('Error: ' . mysql_error());
	
	$rows = array();
	$nameToId = array();
	$codeToId = array();
	while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
		// rows[id] = [name, code, community, cert]
		$rows[$row[4]] = array("pr_name" => utf8_encode($row[0]), "pr_code" => $row[1], "pr_community" => $row[2], "pr_certfication" => $row[3]);

		$nameToId[utf8_encode($row[0])] = $row[4];
		$codeToId[$row[1]] = $row[4];
	}

	// This returns three dicts, one mapping names to id, one mapping codes to id, one mapping ids to all info.
	$result = array("codeToId" => $codeToId, "nameToId" => $nameToId, "idToAll" => $rows);
	echo json_encode($result);
?>