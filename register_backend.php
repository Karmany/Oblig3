<?php
header ("Content-Type: application/json");
session_start();

require_once("connect.php");
require_once("functions.php");

// Retrieve all counties
$counties = get_counties($db);

// Get user input and delcare variables
$firstname = get_post('firstname');
$lastname = get_post('lastname');
$email = get_post('email');
$password = get_post('password');
$confirmed_password = get_post('confirmed_password');
$address = get_post('address');
$county = $_POST['county'];
$profile_img = "img/profile_img.png";
$county_id = "";
$msg = "";
$status = "error";

// Check that all fields have been filled in
foreach($_POST as $var=>$value) {
	if(empty($_POST[$var])) {
		$msg .= "<p class='error'>All Fields are required</p>";
	break;
	}
}

// Get the correct countyID
foreach ($counties as $c) {
	if($county == $c->name_nice){
		$county_id = (int)$c->countyID;
	}
}

// Check password for length and characters
$msg .= validate_password($password);

// Check that password is not in database already
$query = "SELECT email FROM users WHERE email = ?";
$stmnt = $db->prepare($query);

if(!$stmnt->execute(array($email))){
	die("Query Failed: " . $db->errorInfo()[2]);
}

// fetchALL returns empty array if there are zero results to fetch
$result = $stmnt->fetchAll();
if (!empty($result) ) {
	$msg .= "<p class='error'>Username already taken</p>";
}

// Check that passwords match
if($_POST['password'] != $_POST['confirmed_password']){
	$msg .= "<p class='error'>Your passwords do not match</p>";
}

// Hash/encrypt password before storing in the database later
$password = password_hash($password, PASSWORD_DEFAULT);

// Make sure there are no errors, then register user
if($msg == ""){
	$query = "INSERT INTO users (firstname, lastname, email, address, countyID, password, profileImg) VALUES (?,?,?,?,?,?,?)";
	$stmnt = $db->prepare($query);

	if($stmnt->execute(array($firstname, $lastname, $email, $address, $county_id, $password, $profile_img)) == false){
		$msg = "<p class='error'>Database error: User could not be inserted</p>";
		echo json_encode($stmnt->errorInfo()[2]);
	}
	else{
		$status = "success";
	}
}

echo json_encode(array("status"=>$status, "message"=>$msg));
