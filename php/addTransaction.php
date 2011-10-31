<?php
	include('connect.php');
	$link = connectToDb();
	$data = $_POST["fields"];
	$data['pr_code'] = "1-001";
	// If the producer doesn't add him to the database
	$query = "SELECT * FROM producer WHERE pr_code = '". $data['pr_code'] ."'";
	$numrows = mysql_num_rows(mysql_query($query, $link));
	if ($numrows == 0){
		// Producer Doesn't exits
	}
	else if($numrows > 1){
		// Too many producers for this code
	}
	else{
		echo 'exists';
	}
	
	// Add the transaction
	
	// Attempt to sync
	
	
	/*
	
	$result = array();
	$trTable = array();
	$prTable = array();
	foreach ($_POST["fields"] as $col_name => $value){
		// Find out if field is for tr or pr table
		if (substr($col_name,0,2) == "tr"){
			$trTable[$col_name] = $value;
		}
		else{
			$prTable[$col_name] = $value;
		}
	}
	
	
	
	$query = "INSERT '$trTable'";
	echo json_encode($query);
	
	*/
?>