<?php
function connectToDb(){
	// This will check if the url is on the server or local and select the appropriate database
	$url = (!empty($_SERVER['HTTPS'])) ? "https://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'] : "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
	$onIschool = substr_count($url, "ischool");
	if ($onIschool){
		$link = mysql_connect('', 'acopio', 'acopio11235') or die(mysql_errno()); 
		mysql_select_db('acopio') or die ('Could not select: ' . mysql_error());
	}
	else{
		$link = mysql_connect('localhost', 'root', 'root') or die(mysql_errno()); 
		mysql_select_db('acopio') or die ('Could not select: ' . mysql_error());
	}
	return $link;
}

function runQuery($link, $query){
	$result = mysql_query($query,$link) or die(mysql_errno());
	return $result;
}

function sanitizeArray($ar){
	// Takes an array of query elements and sanitizes them, as well as makes them lower case
	// Returns sanitized array of elements
	foreach ($ar as $i => $var){
		$ar[$i] = strtolower(mysql_real_escape_string($var));
	}
	return $ar;
}
function rows($result){
	// Returns an array with all of the rows from the result of a query
	// Could make this return a dictionary with the column names as keys pointing to an array of results?
	$return = array();
	while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
		$return[] = $row;
	}
	return $return;
}

function hello(){
	echo 'Hello There';
}

function links(){
	// Prints the css and js links
	echo '<link rel="stylesheet" href="./css/bootstrap/bootstrap.min.css">
	<link rel="stylesheet" href="./css/ui-lightness/jquery-ui-1.8.16.custom.css">
	<script src="./js/jquery-1.6.2.min.js"></script>
	<script src="./js/jquery-ui-1.8.16.custom.min.js"></script>';
}
?>