<?php
header ("Content-Type: application/json");
session_start();
require_once("connect.php");
require_once("functions.php");

// Execute code based on the mode sent via POST
if(isset($_POST['mode'])){
	switch ($_POST['mode']) {
		// --------------------------
		// ----- EDIT PROFILE: ------
		// --------------------------
		// Edit first and last name:
		case 'edit_name':
			$user_id = $_POST['user_id'];
			$firstname = $_POST['firstname'];
			$lastname = $_POST['lastname'];
			if(empty($firstname) || empty($lastname)){
				echo json_encode(array("message"=>"<p class='error'>Name cannot be empty</p>"));
			}
			else {
				$sql = "UPDATE users SET firstname=?, lastname=? WHERE userID=?";
				$stmnt = $db->prepare($sql);
				$res = $stmnt->execute(array($firstname, $lastname, $user_id));

				// execute for INSERT, UPDATE or DELETE returns the number of rows that were affected
				if($res == 1){ // Successfull query
					// Update session parameters
					$_SESSION['firstname'] = $firstname;
					$_SESSION['lastname'] = $lastname;
					$msg = "<p class='success'>Your changes have been successfully updated</p>";
					echo json_encode(array("status"=>"success", "message"=>$msg, "firstname"=>$firstname, "lastname"=>$lastname));
				}else { // Error when running query
					echo json_encode(array("status"=>"fail", "message"=>$stmnt->errorInfo()[2]));
				}
			}
			break;

		// Edit email:
		case 'edit_email':
			$user_id = $_POST['user_id'];
			$email = $_POST['email'];
			if(empty($email)){
				echo json_encode(array("status"=>"fail", "message"=>"<p class='error'>Email cannot be empty</p>"));
			}
			else{
				// Check that new email address is not taken (exists in database already)
            $sql = "SELECT email FROM users WHERE email = ?";
            $stmnt = $db->prepare($sql);
				$stmnt->execute(array($email));

				// fetchALL returns empty array if there are zero results to fetch
				$result = $stmnt->fetchAll();
				if (!empty($result) ) {
					echo json_encode(array("message"=>"<p class='error'>That email address is already in use</p>"));
				}
				else{ // Email is available, update email in db
					$sql = "UPDATE users SET email = ? WHERE userID = ?";
					$stmnt = $db->prepare($sql);
					$res = $stmnt->execute(array($email, $user_id));

					if($res == 1){ // Successfull query
						echo json_encode(array("status"=>"success", "message"=>"<p class='success'>Your changes have been successfully updated</p>"));
					}else{ // Error when running query
						echo json_encode(array("status"=>"fail", "message"=>$stmnt->errorInfo()[2]));
					}
				}
			}
			break;

		// Edit password
		case 'edit_password':
			$user_id = $_POST['user_id'];
			$current_password = $_POST['current_password'];
			$new_password = $_POST['new_password'];
			$confirm_password = $_POST['confirm_password'];

			if(empty($current_password) || empty($new_password) || empty($confirm_password)){
				echo json_encode(array("status"=>"fail", "message"=>"<p class='error'>No empty fields are allowed</p>"));
			}

	}
}
