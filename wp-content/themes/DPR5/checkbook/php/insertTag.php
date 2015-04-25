<?php
$tag = $_GET['tag'];

$tag = addslashes($tag);

// ...Call the database connection settings
require_once '../../../../../wp-config.php';

// ...Connect to WP database
$dbc = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
if($dbc->connect_errno > 0){
    die('Unable to connect to database [' . $db->connect_error . ']');
}

$query=<<<SQL
		INSERT INTO tags (`tag`)
		VALUES ('$tag')
SQL;
if(!$result = $dbc->query($query)){
    die('There was an error running the query [' . $dbc->error . ']');
}
?>