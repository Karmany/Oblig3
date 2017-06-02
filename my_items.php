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

	if(!isset($_SESSION['isloggedin'])){
		header("Location: login.php");
	}

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
	$items = get_user_items_oneimg($user_id, $db);
	?>
	<body id="my_items">
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
							<input type="submit" value="Confirm" id="add_new_item">
						</form>
					</div>

					<!-- My items -->
					<h2>My items</h2>
					<div id="items_list" class="col-sm-6">
					<?php foreach ($items as $item): ?>
						<div class="item">
							<div class="item_text">
								<h3><?=$item->name?></h3>
								<span class="category_date">Posted: <?=htmlspecialchars(date("d-m-Y H:i", strtotime($item->date)))?></span>
								<span class="category_label"><?=ucfirst($item->category_name)?></span>
							</div>
							<img class="item_img" src="<?=$item->imgPath?>" alt="Image of <?=$item->name?>">
							<?php echo $item->itemID ?>
							<button type="submit" class="delete_item" id="<?=$item->itemID?>" name="delete_item">Delete</button>
						</div>
					<?php endforeach; ?>
					</div>
					<div id="items_message"></div>
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
					form_data.append('mode', 'add_new_item');

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

							// Show the new item withouth refreshing
							var content = "<div class='item'><div class='item_text'>";
							content += "<h3>" + response.new_item.name + "</h3>";
							content += "<span class='category_date'>Posted: " + response.new_item.date + " </span>";
							content += "<span class='category_label'>Posted: " + response.new_item.category + " </span></div>";
							content += "<img class='item_img' src='" + response.new_item.img_path + "' alt=Image of '" + response.new_item.name + "'/>";
							content += "</div>";
							$('#items_list').append(content);
						}
					});
				});

				$('.delete_item').click(function(){
					// Get the correct id for the cosen item
					var del_id = $(this).attr('id');
					$.ajax({
						url:"my_items_backend.php",
						method: "POST",
						data: {
							mode: 'delete_item',
							item_id: del_id
						},
						cache:false
					}).done(function(response){
						// Give feedback
					});
				});

			});
		</script>
	</body>
</html>
