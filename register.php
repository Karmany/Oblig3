<!DOCTYPE html>
<html>
   <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title>Index</title>
      <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
      <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
      <link rel='stylesheet' type='text/css' href='css/styles.css'>
   </head>


<?php
session_start();
require_once("connect.php");
require_once("functions.php");
include 'header.php';

// Delete user table if it alrady exists
// $query = 'DROP TABLE IF EXISTS users';
// if ($db->exec($query)===false){
// 	die('Query failed(1):' . $db->errorInfo()[2]);
// }
//
// // Create table for users
// $query = 'CREATE TABLE IF NOT EXISTS users (
// 	userID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
// 	firstname VARCHAR(64),
// 	lastname VARCHAR(64),
// 	email VARCHAR(256),
// 	password VARCHAR(256),
// 	profileImg VARCHAR(256))';
// if ($db->exec($query)===false){
// 	die('Query failed(2):' . $db->errorInfo()[2]);
// }


// When user clicks submit, retrieve sanitized input fields and insert into database
if(isset($_POST['submit'])){
	$firstname = get_post('firstname');
	$lastname = get_post('lastname');
	$email = get_post('email');
	$password = $_POST['password'];
	$confirmed_password = $_POST['confirmed_password'];
	$profile_img = "img/profile_img.png";

	$msg = "";

	// Check that all fields have been filled in
	foreach($_POST as $var=>$value) {
		if(empty($_POST[$var])) {
			$msg .= "<p class='error'>All Fields are required</p>";
		break;
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
	$password = password_hash($_POST['password'], PASSWORD_DEFAULT);

	// Make sure there are no errors, then register user
	if($msg == ""){
		$query = "INSERT INTO users (firstname, lastname, email, password, profileImg) VALUES (?,?,?,?,?)";
		$stmnt = $db->prepare($query);

		if($stmnt->execute(array($firstname, $lastname, $email, $password, $profile_img)) == false){
			echo "<p>User could not be inserted</p>";
		}
		else{
			$msg = "<p class='success'>You have been successfully registered</p>";
			// Retrieves the user id, which is then used to redirect to login page
			// GET variable is used to send user id to login page in order to give
			// a confirmation message for first time users (newly registered users)
			// $query = "SELECT user_id FROM users WHERE username = ?";
			// $stmnt = $db->prepare($query);
			//
			// if(!$stmnt->execute(array($username))){
			// 	die('Query failed:' . $db->errorInfo()[2]);
			// }
			// $result = $stmnt->fetch(PDO::FETCH_OBJ);
			// $user_id = $result->user_id;
			//
			// // Send user to login
			// header("Location: login.php?id=$user_id&reg=success");
			// die();
		}
	}
	// Display error messages
	echo $msg;
}
?>

<div class="registerFormWrapper">
	<form class="registerForm" action="register.php" method="POST">
		<input type="text" name="firstname" placeholder="First Name" value="<?php if(isset($_POST['firstname'])){echo $_POST['firstname'];}?>">
		<input type="text" name="lastname" placeholder="Last Name" value="<?php if(isset($_POST['lastname'])){echo $_POST['lastname'];}?>">
		<input type="email" name="email" placeholder="Email" value="<?php if(isset($_POST['email'])){echo $_POST['email'];}?>">
		<input type="password" name="password" placeholder="Password">
		<input type="password" name="confirmed_password" placeholder="Confirm Password">
		<input type="submit" name="submit" value="Register &#187;">
	</form>
</div>
