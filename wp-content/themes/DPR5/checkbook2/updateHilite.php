<?php
$ID = $_GET['id'];
$hilite = $_GET['hilite'];

// ...Call the database connection settings
require_once '../../../../wp-config.php';

// ...Connect to WP database
$dbc = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
if($dbc->connect_errno > 0){
    die('Unable to connect to database [' . $db->connect_error . ']');
}

$query=<<<SQL
		UPDATE checkbook SET Hilight = $hilite
		WHERE ID = $ID
SQL;
if(!$result = $dbc->query($query)){
    die('There was an error running the query [' . $dbc->error . ']');
}
?>