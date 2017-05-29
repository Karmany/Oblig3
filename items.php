<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Items</title>
		<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
		<link rel='stylesheet' type='text/css' href='css/styles.css'>
	</head>
	<?php
	session_start();
	// require_once("connect.php");
	require_once("functions.php");

	// Retrieving session variables for the user
	$user_id = $_SESSION['user_id'];
	$email = $_SESSION['email'];
	$firstname = $_SESSION['firstname'];
	$lastname = $_SESSION['lastname'];
   $profile_img = $_SESSION['profile_img'];
   $msg = "";

   print_r($_SESSION);

	?>
	<body id="items">
		<div class="container-fluid">
			<div class="row">
				<div class="col-sm-10 col-sm-offset-1">
					<div class="col-sm-12">
						<h2>Add new item</h2>
						<form onsubmit="javascript:return false;">
							<div id="new_item_message">
								<label for="item_name">Name:</label>
								<input type="text" name="item_name" value="">
								<label for="item_description">Description:</label>
								<input type="text" name="item_description" value="">
								<label for="mapLong">Longitude:</label>
								<input type="text" name="mapLong" value="">
								<label for="mapLat">Lattitude:</label>
								<input type="text" name="mapLat" value="">
								<input type="submit" value="Confirm >>" id="add_new_item">
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>
