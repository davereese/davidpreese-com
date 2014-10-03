<?php
$data = json_decode(file_get_contents("php://input"));
$check = mysql_real_escape_string($data->check_number);
$date = mysql_real_escape_string($data->date);
$desc = mysql_real_escape_string($data->desc);
$pay = mysql_real_escape_string($data->payment);
$dep = mysql_real_escape_string($data->deposit);
$bal = mysql_real_escape_string($data->balance);
$hilite = 0;

var_dump($data);

// ...Call the database connection settings
require_once '../../../../wp-config.php';
?>