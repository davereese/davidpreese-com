<?php
// $data = json_decode(file_get_contents("php://input"));
// $ID = mysql_real_escape_string($data->id);
// $bal = mysql_real_escape_string($data->balance);
// $hilite = mysql_real_escape_string($data->highlight);
$ID = $_GET['id'];
$bal = $_GET['balance'];
$hilite = $_GET['highlight'];

// ...Call the database connection settings
require_once '../../../../wp-config.php';

// ...Connect to WP database
$dbc = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
if($dbc->connect_errno > 0){
    die('Unable to connect to database [' . $db->connect_error . ']');
}

$query=<<<SQL
		UPDATE checkbook SET Balance = $bal,
		Highlight = $hilite
		WHERE ID = $ID
SQL;
if(!$result = $dbc->query($query)){
    die('There was an error running the query [' . $dbc->error . ']');
}
?>