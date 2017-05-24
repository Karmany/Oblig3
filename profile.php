<!DOCTYPE html>
<html>
   <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title>Profile</title>
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
   $msg = "";
	?>

	<body id="profile">
      <div class="container-fluid">
			<div class="row">
				<div class="col-md-10 col-md-offset-1">
					<!-- Stop form from being sent, want to do it with ajax instead -->
					<form onsubmit="javascript: return false;">
                  <div id="message"><?=$msg?></div>
						<label for="firstname">First name</label>
						<input type="text" name="firstname" value="" placeholder="<?php if(isset($firstname)){echo $firstname;}?>" id="firstname"><br/>
						<label for="lastname">Last name</label>
						<input type="text" name="lastname" value="" placeholder="<?php if(isset($lastname)){echo $lastname;}?>" id="lastname"><br/>
						<input type="hidden" name="user_id" value="<?=$user_id?>" id="user_id">
						<input type="submit" value="Confirm changes" id="confirm_changes">
					</form>
				</div>
			</div>
		</div>
		<script>
			// When page has loaded, run function
			$(function(){
				// Get data from backend
				// $.ajax({
				// 	url: 'backend.php'
				// 	// data refers to array containing all data
				// }).done(function(data){
				// 	$('#firstname').val() = "test";
				// });

				$('#confirm_changes').click(function(){
					// Send data from form to backend
					$.ajax({
						url: 'backend.php',
						method: 'POST',
						data: {
							user_id: $('#user_id').val(),
							firstname: $('#firstname').val(),
							lastname: $('#lastname').val(),
							mode: 'edit_profile'
						},
                  success: function(response){
                     // ????
                  }
					}).done(function(response){
                  console.log(response.status);
                  // Clear input values and update placeholder text
                  $('#firstname').val('');
                  $('#firstname').attr('placeholder', response.firstname);
                  $('#lastname').val('');
                  $('#lastname').attr('placeholder', response.lastname);
                  $('#message').html(response.message);
					});
				})

				// Get data from backend
				// $.ajax({
				// 	url: 'backend.php'
				// 	// data refers to array containing all data
				// }).done(function(data){
				// 	$('#firstname').val(data.firstname);
				// });


			});
		</script>
	</body>
</html>
