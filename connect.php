<?php
// connection data
// $db_host = 'howabout2.mysql.domeneshop.no';
// $db_database = 'howabout2';
// $db_user = 'howabout2';
// $db_pass = 'EVqXD5db98okRo9';
$db_host = 'localhost';
$db_database = 'oblig3';
$db_user = 'root';
$db_pass = '';

// Create connection
try {
	// Try to connect to database called online_newspaper_db
	$db = new PDO("mysql:host=$db_host;dbname=$db_database;charset=utf8",$db_user, $db_pass);
} catch(PDOException  $e) {

	try {
		// online_newspaper_db does not exist --> connect with empty database name.
		$db_database = '';
		$db = new PDO("mysql:host=$db_host;dbname=$db_database;charset=utf8",$db_user, $db_pass);
	} catch (PDOException $e) {
		die ("Could not connect: ".$e->getMessage());
	}

}
