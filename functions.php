<?php
// Funcion for sanitizing input
function get_post($var){
	$var = stripslashes($_POST[$var]);
	$var = htmlentities($var);
	$var = strip_tags($var);
	return $var;
}

// Check if POST array contain any empty values
// Return feedback
function check_for_empty_field(){
	$msg = "";
	foreach($_POST as $var=>$value) {
		// If empty, give error message
		if(empty($_POST[$var])) {
			if($var != "msg") {
				$msg .= "<p class='error'>Please fill out all fields</p>";
			}
		break;
		}
	}
	return $msg;
}

// Validation of password. Checks that password contains a minimum of 5 characters.
// Also checks that password contains at least one uppercase and lowercase letter
// Returns appropriate message, empty if fine.
function validate_password($password) {
	$msg = "";
	if (!preg_match("/^.{5,}$/", $password))
		$msg .= "<p class='error'>Password should be minimum of 5 characters.</p>";
	if (!preg_match("/[a-z]/", $password))
		$msg .= "<p class='error'>Password should include at least one lowercase letter.</p>";
	if (!preg_match("/[A-Z]/", $password))
		$msg .= "<p class='error'>Password should include at least one uppercase letter.</p>";
	return $msg;
}

// Get data for a spesific user
function get_user($user_id, $db){
	$query = "SELECT * FROM users WHERE userID = ?";
	$stmnt = $db->prepare ($query);
	if (!$stmnt->execute(array($user_id))){
		die('Query failed:' . $db->errorInfo()[2]);
	}
	return $result = $stmnt->fetch(PDO::FETCH_OBJ);
}

// Retrieves the categories stored in the database
function get_categories($db){
	$query = "SELECT categoryID, name FROM categories ORDER BY name";
	$stmnt = $db->prepare ($query);
	if (!$stmnt->execute(array())){
		die('Query failed:' . $db->errorInfo()[2]);
	}
	return $result = $stmnt->fetchAll(PDO::FETCH_OBJ);
}


// Retrives all images for one item
function get_images($db, $itemID){
	$query = "SELECT imgPath FROM images WHERE itemID = ?";
	$stmnt = $db->prepare ($query);
	if (!$stmnt->execute(array($itemID))){
		die('Query failed:' . $db->errorInfo()[2]);
	}
	return $result = $stmnt->fetchAll(PDO::FETCH_OBJ);
}


// Retrives all info on a specific item

function get_item($db, $itemID){
	$query = "
			SELECT i.name, i.userID, i.description, i.date, u.firstname, u.lastname, u.email, u.address, u.profileImg, c.name_nice AS county_name
			FROM items i
			INNER JOIN users u ON u.userID = i.userID
			INNER JOIN counties c ON u.countyID = c.countyID

			WHERE i.itemID = ?
";
	$stmnt = $db->prepare ($query);
	if (!$stmnt->execute(array($itemID))){
		die('Query (get_item) failed:' . $db->errorInfo()[2]);
	}
	return $result = $stmnt->fetch(PDO::FETCH_OBJ);
}




// Gets Names and first imgPath for all items
function get_items_oneimg($db)
{
	$stmnt = $db->prepare("
		SELECT it.itemID, it.name, it.date, im.imgPath, u.firstname, u.lastname, c.name AS category_name
		FROM items it
		LEFT JOIN images im
		ON it.itemID = im.itemID
		AND im.imgPath = (
			SELECT img.imgPath
			FROM images img
			WHERE it.itemID = img.itemID
			LIMIT 1)
		INNER JOIN users u
		ON it.userID = u.userID
		INNER JOIN categories c
		ON it.categoryID = c.categoryID
		ORDER BY it.date DESC
			"
		);
	if (!$stmnt->execute (array())){
		die('Query failed:' . $db->errorInfo()[2]);
	}
	return $result = $stmnt->fetchAll(PDO::FETCH_OBJ);
}

// Returns name and first imgPath for all items for a specific user
function get_user_items_oneimg($user_id, $db)
{
	$stmnt = $db->prepare("
	SELECT it.itemID, it.name, it.date, im.imgPath, c.name AS category_name
		FROM items it
		LEFT JOIN images im
		ON it.itemID = im.itemID
		AND im.imgPath = (
			SELECT img.imgPath
			FROM images img
			WHERE it.itemID = img.itemID
			LIMIT 1)
		INNER JOIN users u
		ON u.userID = it.userID
		  INNER JOIN categories c
		  ON it.categoryID = c.categoryID
		WHERE u.userID = ?"
		);
	if (!$stmnt->execute (array($user_id))){
		die('Query failed:' . $db->errorInfo()[2]);
	}
	return $result = $stmnt->fetchAll(PDO::FETCH_OBJ);
}

// Get all counties ordered by name
function get_counties($db)
{
	$stmnt = $db->prepare('
            SELECT countyID, name, name_nice
            FROM counties
            ORDER BY name
            ');
	if (!$stmnt->execute(array())) {
		die('Query failed:' . $db->errorInfo()[2]);
	}
	return $result = $stmnt->fetchAll(PDO::FETCH_OBJ);

}
