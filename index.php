<?php
	require_once("connect.php");

	$query = 'CREATE TABLE IF NOT EXISTS test (
		id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
		test VARCHAR(100))';
	if ($db->exec($query)===false){
		die('Query failed(4):' . $db->errorInfo()[2]);
	}


?>
