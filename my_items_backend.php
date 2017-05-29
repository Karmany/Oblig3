<?php
header ("Content-Type: application/json");
session_start();
require_once("connect.php");
require_once("functions.php");
$user_id = $_SESSION['user_id'];
$img_paths = array();
$msg = "";

foreach ($_FILES['img']['name'] as $index => $name) {
	// echo json_encode("Image nr: $index has type " . $_FILES['img']['type'][$index] . "<br/>");
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
		// move_uploaded_file($tmp_name, $location.$name);
		$path = $location.$name;
		$img_paths[] = $path;
		$msg .= "<p class='success'>$name was successfully uploaded.</p>";
	}
}
echo json_encode(array("message"=>$msg, "file_paths"=>$img_paths));







//
// $files = $_FILES['files']['name'];
// $file_paths = array();
// $count = 0;
// foreach ($files as $file) {
// 	if(!empty($file)){
// 		$allowed_types = array("jpg", "jpeg", "png", "gif");
// 	}
// 	// $file_name = $file;
// 	$file_paths[] = $file;
// 	// $file_type = $_FILES['files']['type'][$count];
// 	// $file_size = $_FILES['files']['size'][$count];
// 	$count++;
// }
//
// $data = array('apple','banana','cherry');
// $data['animals'] = array('dog', 'elephant');
// echo json_encode($data);


// echo json_encode(array("message"=>"You have uploaded $count images", "data"=>$data, "img_paths"=>$file_paths));
