<?php
header ("Content-Type: application/json");
session_start();
require_once("connect.php");
require_once("functions.php");

//gets the checked boxes from index.php


if(isset($_POST['mode'])){
	switch($_POST['mode']){
		case 'show_all':
			$statement = $db->prepare("
SELECT items.itemID, items.name, items.description
FROM items INNER JOIN categories ON categories.categoryID = items.categoryID
");
			$statement ->execute();
			$results = $statement ->fetchAll(PDO::FETCH_ASSOC);
//Puts the results from the statement into JSON
			$json = json_encode($results, JSON_PRETTY_PRINT);
//Echos the JSON back to index.php to be displayed there
			echo($json);
		break;
		case 'update_items';
			$opts = $_POST['filterOpts'];

			$qMarks = str_repeat('?,', count($opts) - 1) . '?';
//Statment with gets the info we want from items aswell as the category it is in(Trenge kansje ikkje dette, men må ha imgPath på et punkt)
			$statement = $db->prepare("
SELECT items.itemID, items.name, items.description
FROM items INNER JOIN categories ON categories.categoryID = items.categoryID
WHERE categories.categoryID IN ($qMarks)");
			$statement->execute($opts);
			$results = $statement->fetchAll(PDO::FETCH_OBJ);
//Puts the results from the statement into JSON
			$json = json_encode($results, JSON_PRETTY_PRINT);
//Echos the JSON back to index.php to be displayed there
			echo($json);

			break;
	}
};





?>