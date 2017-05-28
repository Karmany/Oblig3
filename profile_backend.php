<?php
header ("Content-Type: application/json");
session_start();
require_once("connect.php");
require_once("functions.php");
$user_id = $_SESSION['user_id'];

// Execute code based on the mode sent via POST
if(isset($_POST['mode'])){
	switch ($_POST['mode']) {
		// --------------------------
		// ----- EDIT NAME: ---------
		// --------------------------
		case 'edit_name':
			$firstname = $_POST['firstname'];
			$lastname = $_POST['lastname'];
			if(empty($firstname) || empty($lastname)){
				echo json_encode(array("status"=>"error", "message"=>"<p class='error'>Name cannot be empty</p>"));
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
					echo json_encode(array("status"=>"error", "message"=>$stmnt->errorInfo()[2]));
				}
			}
			break;

		// --------------------------
		// ----- EDIT EMAIl: --------
		// --------------------------
		case 'edit_email':
			$email = $_POST['email'];
			if(empty($email)){
				echo json_encode(array("status"=>"error", "message"=>"<p class='error'>Email cannot be empty</p>"));
			}
			else{
				// Check that new email address is not taken (exists in database already)
            $sql = "SELECT email FROM users WHERE email = ?";
            $stmnt = $db->prepare($sql);
				$stmnt->execute(array($email));

				// fetchALL returns empty array if there are zero results to fetch
				$result = $stmnt->fetchAll();
				if (!empty($result) ) {
					echo json_encode(array("status"=>"error", "message"=>"<p class='error'>That email address is already in use</p>"));
				}
				else{ // Email is available, update email in db
					$sql = "UPDATE users SET email = ? WHERE userID = ?";
					$stmnt = $db->prepare($sql);
					$res = $stmnt->execute(array($email, $user_id));

					if($res == 1){ // Successfull query
						$_SESSION['email'] = $email; // Update session variable
						echo json_encode(array("status"=>"success", "email"=>$email, "message"=>"<p class='success'>Your changes have been successfully updated</p>"));
					}else{ // Error when running query
						echo json_encode(array("status"=>"error", "message"=>$stmnt->errorInfo()[2]));
					}
				}
			}
			break;

		// --------------------------
		// ----- EDIT IMAGE: --------
		// --------------------------
		case 'edit_profile_image':
			// Check if file (image) has been uploaded
			if (isset($_FILES["profile_img"]["name"])) {
				$name = $_FILES["profile_img"]["name"];
				$tmp_name = $_FILES['profile_img']['tmp_name'];
				$error = $_FILES['profile_img']['error'];
				$location = 'img/';

				// Get the file extension
				$extension = strtolower(substr($name, strpos($name, '.') +1));

				// Check if file exists
				if (!empty($name)) {
					// Check that file is of allowed type
					$allowed_types = array("jpg", "jpeg", "png", "gif");
					if(!in_array($extension, $allowed_types)) {
						echo json_encode(array("status"=>"error", "message"=>"<p class='error'>Sorry, that filetype is not allowed.</p>"));
					}
					else{ // File is ok, move to the img folder
						move_uploaded_file($tmp_name, $location.$name);
						$img_url = $location.$name;

						// Update profile image in db
						$sql = "UPDATE users SET profileImg = ? WHERE userID = ?";
						$stmnt = $db->prepare($sql);
						$res = $stmnt->execute(array($img_url, $user_id));

						if($res == 1){ // Successfull query
							$_SESSION['profile_img'] = $img_url; // Update session variable
							echo json_encode(array("status"=>"success", "profile_img"=>$img_url, "message"=>"<p class='success'>Your changes have been successfully updated</p>"));
						}else{ // Error when running query
							echo json_encode(array("status"=>"error", "message"=>$stmnt->errorInfo()[2]));
						}
					}
				}else{ // User did not choose a file
					echo json_encode(array("status"=>"error", "message"=>"<p class='error'>Please choose a file.</p>"));
				}
			}
			break;

		// --------------------------
		// ----- EDIT PASSWORD: -----
		// --------------------------
		case 'edit_password':
			$current_password = $_POST['current_password'];
			$new_password = $_POST['new_password'];
			$confirm_password = $_POST['confirm_password'];
			$msg = "";

			if(empty($current_password) || empty($new_password) || empty($confirm_password)){ // At least one empty input field
				$msg .= "<p class='error'>No empty fields are allowed</p>";
			}
			if($new_password != $confirm_password) { // Input in new and confirmed password do not match
				$msg .= "<p class='error'>Your new password and confirmed password do not match</p>";
			}

			$sql = "SELECT password FROM users WHERE userID = ?";
			$stmnt = $db->prepare($sql);
			$stmnt->execute(array($user_id));
			$result = $stmnt->fetch(PDO::FETCH_OBJ);

			$msg .= validate_password($new_password); // Validate password, see functions.php
			if(!password_verify($current_password, $result->password)){ // user's current password does not match the one stored in db
				$msg .= "<p class='error'>Incorrect value for current password</p>";
			}

			// Password has passed validation and there are no errors
			if($msg == ""){
				// Hash the new password before storing in db
				$new_password = password_hash($new_password, PASSWORD_DEFAULT);
				$sql = "UPDATE users SET password = ? WHERE userID = ?";
				$stmnt = $db->prepare($sql);
				$res = $stmnt->execute(array($new_password, $user_id));

				if($res == 1){ // Successfull query
					echo json_encode(array("status"=>"success", "message"=>"<p class='success'>Your changes have been successfully updated</p>"));
				}else{ // Error when running query
					echo json_encode(array("status"=>"error", "message"=>$stmnt->errorInfo()[2]));
				}

			// Return error message(s)
			}else{
				echo json_encode(array("status"=>"error", "message"=>$msg));
			}
			break;
	}
}
