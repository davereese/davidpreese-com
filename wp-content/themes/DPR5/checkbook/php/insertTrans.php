<?php
$check = '';
if (isset($_GET['check_number'])) {
	$check = $_GET['check_number'];
}
$date = $_GET['date'];
$desc = $_GET['desc'];
$tags = $_GET['transtags'];
$pay = '';
if (isset($_GET['payment'])) {
	$pay = $_GET['payment'];
}
$dep = '';
if (isset($_GET['deposit'])) {
	$dep = $_GET['deposit'];
}
$hilite = 0;

$desc = addslashes($desc);
$tags = addslashes($tags);

// ...Call the database connection settings
require_once '../../../../../wp-config.php';

// ...Connect to WP database
$dbc = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
if($dbc->connect_errno > 0){
    die('Unable to connect to database [' . $db->connect_error . ']');
}

$query=<<<SQL
		INSERT INTO checkbook (`Check_Number`, `Date`, `Description`, `Tags`, `Payment`, `Deposit`, `Highlight`)
		VALUES ('$check', '$date', '$desc', '$tags', '$pay', '$dep', '$hilite')
SQL;
if(!$result = $dbc->query($query)){
    die('There was an error running the query [' . $dbc->error . ']');
}
?>