<?php
	require_once 'connect.php';
	require_once 'functions.php';
	echo "Today is " . date("d-m-Y H:i");

	$img_paths = array("PATH");

	$new_item = array("name"=>"name", "category"=>"category", "date"=>date("d-m-Y H:i"));

	$new_item['new_item'] = $img_paths[0];

	echo "<pre>";
	print_r($new_item);
	echo "</pre>";

	$county = "Hordaland";

	$counties = get_counties($db);
	foreach ($counties as $c) {
		if($county == $c->name_nice){
			echo $county_id = $c->countyID;
		}
	}


	// print_r($counties);
?>



<?php
// header ("Content-Type: application/json");
// session_start();
//
// require_once("connect.php");
// require_once("functions.php");
// // $user_id = $_SESSION['user_id'];
//
// // Retrieve all counties
// $counties = get_counties($db);
// $msg = "";
//
// // When user clicks submit, retrieve sanitized input fields and insert into database
//
// $firstname = get_post('firstname');
// $lastname = get_post('lastname');
// $email = get_post('email');
// $password = get_post('password');
// $confirmed_password = get_post('confirmed_password');
// $address = get_post('address');
// $county = get_post('county');
// $profile_img = "img/profile_img.png";
// $county_id = "";
//
// // Check that all fields have been filled in
// foreach($_POST as $var=>$value) {
// 	if(empty($_POST[$var])) {
// 		$msg .= "<p class='error'>All Fields are required</p>";
// 	break;
// 	}
// }
//
// // Get the correct countyID
// foreach ($counties as $c) {
// 	if($county == $c->name_nice){
// 		$county_id = $c->countyID;
// 	}
// }
//
// // Check password for length and characters
// $msg .= validate_password($password);
//
// // Check that password is not in database already
// $query = "SELECT email FROM users WHERE email = ?";
// $stmnt = $db->prepare($query);
//
// if(!$stmnt->execute(array($email))){
// 	die("Query Failed: " . $db->errorInfo()[2]);
// }
//
// // fetchALL returns empty array if there are zero results to fetch
// $result = $stmnt->fetchAll();
// if (!empty($result) ) {
// 	$msg .= "<p class='error'>Username already taken</p>";
// }
//
// // Check that passwords match
// if($_POST['password'] != $_POST['confirmed_password']){
// 	$msg .= "<p class='error'>Your passwords do not match</p>";
// }
//
// // Hash/encrypt password before storing in the database later
// $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
//
// // Make sure there are no errors, then register user
// if($msg == ""){
// 	$query = "INSERT INTO users (firstname, lastname, email, address, countyID, password, profileImg) VALUES (?,?,?,?,?,?,?)";
// 	$stmnt = $db->prepare($query);
//
// 	if($stmnt->execute(array($firstname, $lastname, $email, $address, $county_id, $password, $profile_img)) == false){
// 		$msg =  "<p>Databaser error: User could not be inserted</p>";
// 	}
// 	else{
// 		$msg = "<p class='success'>You have been successfully registered</p>";
// 	}
// 	// Redirect user to login page
// 	// header("Location: login.php");
// }
//
// echo json_encode(array("message"=>$msg));
