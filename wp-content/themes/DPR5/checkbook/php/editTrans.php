<?php
$ID = $_GET['id'];
$check = $_GET['check_number'];
$date = $_GET['date'];
$desc = $_GET['desc'];
$tags = $_GET['transtags'];
$pay = $_GET['payment'];
$dep = $_GET['deposit'];

if ( '' == $check ) {
	$check = 0;
}

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
		UPDATE checkbook SET Check_Number = '$check', Date = '$date', Description = '$desc', Tags = '$tags', Payment = '$pay', Deposit = '$dep'
		WHERE ID = $ID
SQL;
if(!$result = $dbc->query($query)){
    die('There was an error running the query [' . $dbc->error . ']');
}
?>