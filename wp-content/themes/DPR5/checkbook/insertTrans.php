<?php
$request_body = file_get_contents("php://input");
$data = json_decode($request_body);
$check = mysql_real_escape_string($data->check_number);
$date = mysql_real_escape_string($data->date);
$desc = mysql_real_escape_string($data->desc);
$pay = mysql_real_escape_string($data->payment);
$dep = mysql_real_escape_string($data->deposit);
$bal = mysql_real_escape_string($data->balance);
$hilite = 0;

// ...Call the database connection settings
require_once '../../../../wp-config.php';

// ...Connect to WP database
$dbc = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
if($dbc->connect_errno > 0){
    die('Unable to connect to database [' . $db->connect_error . ']');
}

$query=<<<SQL
		INSERT INTO checkbook (`check_number`, `date`, `description`, `payment`, `deposit`, `balance`, `highlight`)
		VALUES ('$check', '$date', '$desc', '$pay', '$dep', '$bal', '$hilite')
SQL;
if(!$result = $dbc->query($query)){
    die('There was an error running the query [' . $dbc->error . ']');
}
?>