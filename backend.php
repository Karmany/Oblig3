<?php
header ("Content-Type: application/json");
session_start();
require_once("connect.php");
require_once("functions.php");

// Execute code based on the mode sent via POST
if(isset($_POST['mode'])){
	switch ($_POST['mode']) {
		// ----- EDIT PROFILE: -----
		case 'edit_profile':
			$user_id = $_POST['user_id'];
			$firstname = $_POST['firstname'];
			$lastname = $_POST['lastname'];
			if(empty($firstname) || empty($lastname)){
				echo json_encode(array("Name cannot be empty"));
			}
			else {
				$sql = "UPDATE users SET firstname=?, lastname=? WHERE userID=?";
				$stmnt = $db->prepare($sql);
				$res = $stmnt->execute(array($firstname, $lastname, $user_id));

				// execute for INSERT, UPDATE or DELETE returns the number of rows that were affected
				if($res == 1){
					// Update session parameters
					$_SESSION['firstname'] = $firstname;
					$_SESSION['lastname'] = $lastname;
					$msg = "<p class='success'>Your changes have been successfully updated</p>";
					echo json_encode(array("status"=>"success", "message"=>$msg, "firstname"=>$firstname, "lastname"=>$lastname));
				}
				else {
					echo json_encode(array("status"=>"fail", "message"=>$stmnt->errorInfo()[2]));
				}
			}
			break;
	}
}
// No POST request
else {
	$sql = "SELECT firstname, lastname, email FROM users";
	$stmt = $db->prepare($sql);
	$stmt->execute(array());
	// fetchAll returns an array containing all posts
	echo json_encode($stmt->fetchAll(PDO::FETCH_OBJ));
}
