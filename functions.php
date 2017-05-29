<?php
// Funcion for sanitizing input
function get_post($var){
	$var = stripslashes($_POST[$var]);
	$var = htmlentities($var);
	$var = strip_tags($var);
	return $var;
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
	$query = "SELECT categoryID, name FROM categories";
	$stmnt = $db->prepare ($query);
	if (!$stmnt->execute (array())){
		die('Query failed:' . $db->errorInfo()[2]);
	}
	return $result = $stmnt->fetchAll(PDO::FETCH_OBJ);
}
