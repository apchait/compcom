<?php
	include('../php/connect.php');	
	$link = connectToDb();
	// if $link is a number, it is a mysql error
/*	if (is_numeric($link)){
		echo $link . "<br>";
		return $link;
	}
	echo $link . "<br>";
	*/
	// Take local storage
	$ls = $_POST;

	// Parse out arguments
	$transactions = $ls['transactions'];	

	
//$transactions = Array(0 => Array('tr_folio' => '1235', 'tr_pr_code' => '1-013', 'tr_date' => '11/02/2011'), 1 => Array('tr_folio' => '1234', 'tr_pr_code' => '1-013', 'tr_date' => '11/02/2011'));
	// Query DB
	foreach($transactions as $i => $tr){
		$cols = "(";
		$vals = "(";
		foreach($tr as $col => $val){
			$cols .= '`' . $col . "`,";
			$vals .= "'" . $val . "',";
		}
		// Clean up the strings
		$end = strlen($cols) -1;
		$cols[$end] = ")";
		$end = strlen($vals) -1;
		$vals[$end] = ")";
		$query = "INSERT INTO transaction $cols VALUES $vals";
	//	echo $query . "<br>";
		$r = runQuery($link, $query);
	//	echo $r . "<br>";
	}
	// Send result status
 	echo "success";
?>