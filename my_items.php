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
	require_once("connect.php");
	require_once("functions.php");

	// Retrieving session variables for the user
	$user_id = $_SESSION['user_id'];
	$email = $_SESSION['email'];
	$firstname = $_SESSION['firstname'];
	$lastname = $_SESSION['lastname'];
   $profile_img = $_SESSION['profile_img'];
   $msg = "";

	// Retrieves category_list, See functions.php
	$category_list = get_categories($db);
	// Retrieves items, see functions.php
	// $items = get_items($db);
	?>
	<body id="items">
		<?php include("header.php") ?>
		<div class="container-fluid">
			<div class="row">
				<div class="col-sm-10 col-sm-offset-1">
					<!-- New item -->
					<div class="col-sm-6">
						<h2>Add new item</h2>
						<form id="new_item_form" onsubmit="javascript:return false;" enctype="multipart/form-data">
							<div id="new_item_message"></div>
							<label for="item_name">Name:</label>
							<input type="text" name="item_name" id="item_name">
							<label for="item_description">Description:</label>
							<textarea name="item_description" id="item_description"></textarea>
							<label for="mapLong">Longitude:</label>
							<input type="text" name="mapLong" id="mapLong">
							<label for="mapLat">Lattitude:</label>
							<input type="text" name="mapLat" id="mapLat">
							<label for="img[]">Image:
								<input type="file" name="img[]" multiple="true">
							</label>
							<label for="category">Category:
							<select class="category_ddl" name="category">
								<?php
								// Make sure the selected option is the original category
								foreach ($category_list as $category_array => $category) {
									echo "<option>" . ucfirst($category->name) . "</option>";
								}
								?>
							</select>
							</label>
							<input type="button" value="Confirm >>" id="add_new_item">
						</form>
					</div>

					<!-- My items -->
					<div id="items_list" class="col-sm-6">
						<h2>My items:</h2>
						<?php
						$query = "SELECT items.*, images.*
							FROM items
							LEFT JOIN images
							ON items.itemID = images.itemID";
						$stmnt = $db->prepare($query);
						if (!$stmnt->execute(array())){
							die('Query failed:' . $db->errorInfo()[2]);
						}
						$items = $stmnt->fetchAll(PDO::FETCH_OBJ);
						// foreach ($result as $item) {
						// 	echo $result->name;
						// }
						foreach ($items as $item) {
							echo $item->itemID . " " . $item->name . "<br/>";
						}
						?>
					</div>
				</div>
			</div>
		</div>

		<script>
			// When page has loaded, run function
			$(function(){
				$('#add_new_item').click(function(){
					// Get form data and add variable for mode to it
					var form = $('#new_item_form')[0];
					var form_data = new FormData(form);

					// form_data.append('mode', 'edit_profile_image');
					$.ajax({
						url: 'my_items_backend.php',
						method: 'POST',
						data: form_data,
						// Stop Content-Type header from being added automatically
						contentType: false,
						// Stop FormData from trying to convert into string
						processData: false,
						cache: false
					}).done(function(response){
						// Give feedback
						$('#new_item_message').html(response.message);
						if(response.status == 'success'){
							$('#item_name').val('');
							$('#item_description').val('');
							$('#mapLong').val('');
							$('#mapLat').val('');
						}
					});
				});
			});
		</script>
	</body>
</html>
