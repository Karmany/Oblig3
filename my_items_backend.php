<?php
header ("Content-Type: application/json");
session_start();
require_once("connect.php");
require_once("functions.php");
$user_id = $_SESSION['user_id'];
$img_paths = array();
$msg = "";
$status = "error";
$new_item = array();

// Check that input fields are not empty, see functions.php
$msg .= check_for_empty_field();

// Check that user has selected one or more image(s) to upload
if ( $_FILES['img']['error'][0] == 4 ){ // No image was chosen
	$msg .= "<p class='error'>No image(s) selected</p>";
	$status = "error";
}

// INSERT ITEM
if($msg == ""){
	$name = get_post('item_name');
	$desc = get_post('item_description');
	$long = get_post('mapLong');
	$lat = get_post('mapLat');
	$cat = strtolower(get_post('category'));
	$category_id = "";

	// Retrieves category_list, See functions.php
	$category_list = get_categories($db);
	// Loop through categories and get the correct category id
	foreach ($category_list as $category) {
		if($cat == $category->name){
			$category_id = $category->categoryID;
		}
	}

	$item = array($name, $desc, $user_id, $long, $lat, $category_id);
	$sql = "INSERT INTO items (name, description, userID, mapLong, mapLat, categoryID) VALUES(?, ?, ?, ?, ?, ?)";
	$stmnt = $db->prepare($sql);
	$res = $stmnt->execute($item);

	// execute for INSERT, UPDATE or DELETE returns the number of rows that were affected
	if($res == 1){ // Successfull query
		$msg .= "<p class='success'>Your item has been posted.</p>";
		$status = "success";
		// Create array for new item to be returned to my_items page, img path is added later
		$new_item = array("name"=>$name, "category"=>ucfirst($cat), "date"=>date("d-m-Y H:i"));
		// Get the item_id for the last inserted item
		$last_id = $db->lastInsertId();
		// echo json_encode(array("status"=>"success", "message"=>$msg));
	}else { // Error when running query
		echo json_encode(array("status"=>"error", "message"=>$stmnt->errorInfo()[2]));
		die();
	}

	// Loop through all images and validate them
	foreach ($_FILES['img']['name'] as $index => $name) {
		$name = $_FILES['img']['name'][$index];
		$tmp_name = $_FILES['img']['tmp_name'][$index];
		$type = $_FILES['img']['type'][$index];
		$error = $_FILES['img']['error'][$index];
		$allowed_types = array("jpg", "jpeg", "png", "gif");
		$location = "img/";

		// Check if the file is of allowed type
		$extension = strtolower(substr($name, strpos($name, '.') +1));
		if(!in_array($extension, $allowed_types)){
			$msg .= "<p class='error'>Error: $name could not be uploaded, $extension is an invalid filetype.</p>";
		}else{ // Valid filetype, move to img folder
			move_uploaded_file($tmp_name, $location.$name);
			$path = $location.$name;
			$img_paths[] = $path;
			$msg .= "<p class='success'>$name was successfully uploaded.</p>";

			// INSERT IMAGES FOR THE ITEM
			$sql = "INSERT INTO images (itemID, imgPath) VALUES(?, ?)";
			$stmnt = $db->prepare($sql);
			$res = $stmnt->execute(array($last_id, $path));

			if($res != 1){ // Error when running query
				echo json_encode(array("status"=>"error", "message"=>$stmnt->errorInfo()[2]));
				die();
			}
		}
	}
}

if($status == 'success'){
	// Add image path to the item array
	$new_item['img_path'] = $img_paths[0];
}

echo json_encode(array("status"=>$status, "message"=>$msg, "new_item"=>$new_item));
