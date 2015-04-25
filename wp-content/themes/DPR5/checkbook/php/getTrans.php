<?php
// ...Call the database connection settings
require_once '../../../../../wp-config.php';

// ...Connect to WP database
$dbc = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
if($dbc->connect_errno > 0){
    die('Unable to connect to database [' . $db->connect_error . ']');
}

$query=<<<SQL
		SELECT id, check_number, date, description, tags, payment, deposit, highlight 
		FROM checkbook
		WHERE id > 0
		ORDER BY ID ASC
SQL;
if(!$result = $dbc->query($query)){
    die('There was an error running the query [' . $dbc->error . ']');
}

$arr = array();
if($result->num_rows > 0) {
 while($row = $result->fetch_assoc()) {
 $arr[] = $row;
 }
}
 
# JSON-encode the response
echo $json_response = json_encode($arr);

?>