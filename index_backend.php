<?php
header ("Content-Type: application/json");
session_start();
require_once("connect.php");
require_once("functions.php");

//gets the checked boxes from index.php


if(isset($_POST['mode'])){
	switch($_POST['mode']){
		case 'show_all':
			$items = get_items_oneimg($db);

			foreach ($items as $r){
				$r->date = htmlspecialchars(date("d-m-Y H:i", strtotime($r->date)));
			}

//Puts the results from the statement into JSON
			$json = json_encode($items, JSON_PRETTY_PRINT);
//Echos the JSON back to index.php to be displayed there
			echo($json);
		break;
		case 'update_items';
			$opts = $_POST['filterOpts'];

			$qMarks = str_repeat('?,', count($opts) - 1) . '?';
//Statment with gets the info we want from items aswell as the category it is in(Trenge kansje ikkje dette, men må ha imgPath på et punkt)
			$statement = $db->prepare("
SELECT it.itemID, it.name, it.date,  im.imgPath, u.firstname, u.lastname, ca.name AS category_name
FROM items it 
LEFT JOIN images im 
ON it.itemID = im.itemID 
AND im.imgPath = ( 
SELECT img.imgPath 
FROM images img 
WHERE it.itemID = img.itemID LIMIT 1) 

INNER JOIN categories ca 
ON it.categoryID = ca.categoryID
INNER JOIN users u 
ON it.userID = u.userID
INNER JOIN counties co
ON u.countyID = co.countyID
WHERE ca.categoryID IN ($qMarks)
OR co.name IN ($qMarks)
ORDER BY it.date DESC


			
			");
			//Double the same values since we have to use $qMarks twice, and create twice as many ? as we have variables for
			$array_combined = array_merge($opts, $opts);
			$statement->execute($array_combined);
			$results = $statement->fetchAll(PDO::FETCH_OBJ);
			foreach ($results as $r){
				$r->date = htmlspecialchars(date("d-m-Y H:i", strtotime($r->date)));
			}

//Puts the results from the statement into JSON
			$json = json_encode($results, JSON_PRETTY_PRINT);
//Echos the JSON back to index.php to be displayed there
			echo($json);

			break;
	}
};
?>