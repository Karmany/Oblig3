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
SELECT it.itemID, it.name, im.imgPath
FROM items it, 
INNER JOIN images im ON it.itemID = im.itemID
INNER JOIN categories c ON it.categoryID = c.categoryID
WHERE c.categoryID IN ($qMarks)");
			$statement->execute($opts);
			$results = $statement->fetchAll(PDO::FETCH_OBJ);
//Puts the results from the statement into JSON
			$json = json_encode($results, JSON_PRETTY_PRINT);
//Echos the JSON back to index.php to be displayed there
			echo($json);

			break;
	}
};


/*
SELECT it.name, im.imgPath
FROM items it
JOIN images im ON im.itemID = it.itemID
	AND im.imgPath =(
	SELECT TOP 1 img.imgPath
	FROM images img
	WHERE im.imgPath = img.imgPath
)


SELECT it.name, img.imgPath
FROM items it
JOIN (
  SELECT im.itemID, im.imgPath
    ROW_NUMBER() OVER (
      PARTITION BY im.itemID
      ) AS row_num
  FROM images im
  ) img
  ON img.itemID = it.itemID AND row_num = 1;

SELECT it.name, im.imgPath
  FROM items it
  LEFT JOIN images im
    ON im.itemID = it.itemID
   AND im.imgPath =
        ( SELECT TOP 1 imgPath
            FROM images img
           WHERE img.itemID = it.itemID
       )


*/



?>