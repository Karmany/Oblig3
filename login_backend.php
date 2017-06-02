<?php
header ("Content-Type: application/json");
session_start();
require_once("connect.php");
require_once("functions.php");
$msg = "";
$status = "error";

// User is not logged in and clicks the login button
if (!isset($_SESSION['isloggedin'])) {
	$email = get_post('email');
	$password = get_post('password');

	if(empty($email) || empty($password)){
		$msg = "<p class='error'>Please enter your email and password</p>";
	}
	else {
		// Check database for users matching input email
		$query = "SELECT * FROM users WHERE email=?";
		$stmnt = $db->prepare ($query);
		if (!$stmnt->execute(array($email)))
		die('Query failed:' . $db->errorInfo()[2]);

		// All email addresses are supposed to be unique. Can therefore use fetch()
		// to get the last (the only) row matching the query
		$user = $stmnt->fetch(PDO::FETCH_OBJ);

		// If there is in fact a user with that username in the database
		if (count($user) != 0) {
			// Check that input password matches the users password stored in the database
			if(password_verify($password, $user->password)){
				// Set session parameters
				$_SESSION['user_id'] = $user->userID;
				$_SESSION['firstname'] = $user->firstname;
				$_SESSION['lastname'] = $user->lastname;
				$_SESSION['email'] = $email;
				$_SESSION['profile_img'] = $user->profileImg;
				$_SESSION['isloggedin'] = true;

				// Redirect user to index.php
				$status = "success";
				// header("Location: index.php");
				// die();
			}
			// Bad password
			else {
				$msg = "<p class='error'>Wrong username or password</p>";
			}
		}
		// If there is no user with that username
		else {
			$msg = "<p class='error'>Wrong username or password</p>";
		}
	}
	echo json_encode(array("status"=>$status, "message"=>$msg));
}
?>
