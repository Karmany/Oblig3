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
			$is_category_active = $_POST['category_active'];
			$is_county_active = $_POST['county_active'];
			$qMarks = str_repeat('?,', count($opts) - 1) . '?';
			//Statment with gets the info we want for showing items, including name og category, the image path and the user who posted it
			$most_of_statement = "
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
				ON u.countyID = co.countyID ";

			if($is_category_active == 1 && $is_county_active == 1) {
				$where = 'WHERE ca.categoryID IN ('.$qMarks.') AND co.name IN ('.$qMarks.') ';
			}
			elseif ($is_category_active == 1){
				$where = 'WHERE ca.categoryID IN ('.$qMarks.') ';
			}
			elseif ($is_county_active == 1){
				$where = 'WHERE co.name IN ('.$qMarks.') ';
			}
			else{
				$where = '';
			}

			$order_by = "ORDER BY it.date DESC";

			$query = $most_of_statement . $where . $order_by;

			$statement = $db->prepare($query);
			if($is_category_active == 1 && $is_county_active == 1) {
				//Double the same values if we have to use $qMarks twice, and create twice as many ? as we have variables for
				$array_combined = array_merge($opts, $opts);
				$statement->execute($array_combined);
			}
			else{
				$statement->execute($opts);
			}
			$results = $statement->fetchAll(PDO::FETCH_OBJ);
			foreach ($results as $r){
				$r->date = htmlspecialchars(date("d-m-Y H:i", strtotime($r->date)));
			}

			//Puts the results from the statement into JSON
			$json = json_encode($results);
			//Echos the JSON back to index.php to be displayed there
			echo($json);

			break;
	}
};
?>
