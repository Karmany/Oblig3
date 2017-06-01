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

// Retrieves the categories stored in the database
function get_categories($db){
	$query = "SELECT categoryID, name FROM categories ORDER BY name";
	$stmnt = $db->prepare ($query);
	if (!$stmnt->execute(array())){
		die('Query failed:' . $db->errorInfo()[2]);
	}
	return $result = $stmnt->fetchAll(PDO::FETCH_OBJ);
}


// Gets Names and first imgPath for all items
function get_items_oneimg($db)
{
	$stmnt = $db->prepare("
		SELECT it.name, im.imgPath
		FROM items it
		LEFT JOIN images im
		ON it.itemID = im.itemID
		AND im.imgPath = (
			SELECT img.imgPath
			FROM images img
			WHERE it.itemID = img.itemID
			LIMIT 1)"
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
	SELECT it.name, it.date, im.imgPath, c.name AS category_name
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