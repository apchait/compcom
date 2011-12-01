<?php
	include('../php/connect.php');	
	$link = connectToDb();
	$query = "SELECT * FROM transaction";
	$r = runQuery($link,$query);
	$return = array();
	$colnames = array();
	$i = 0;
	while ($i < mysql_num_fields($r)) {
		$colnames[$i] = mysql_field_name($r, $i);
		$i++;
	}

	while ($row = mysql_fetch_assoc($r, MYSQL_NUM)) {
		$assoc_row = array();
		foreach($row as $i => $v){
			$assoc_row[$colnames[$i]] = $v;
		}
	    array_push($return, $assoc_row);
	}
	echo JSON_encode($return);

?>