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
   $profile_img = $_SESSION['profile_img'];
   $msg = "";
	?>

	<body id="profile">
      <div class="container-fluid">
			<div class="row">
				<div class="col-sm-10 col-sm-offset-1">
               <h1>Welcome, <span id="greeting_txt_firstname"><?=$firstname?></span> <span id="greeting_txt_lastname"><?=$lastname?></span></h1>
               <input type="hidden" name="user_id" value="<?=$user_id?>" id="user_id">

               <div class="edit_name col-sm-12">
                  <h2>Name:</h2>
                  <!-- Stop form from being sent, want to do it with ajax instead -->
                  <form onsubmit="javascript: return false;">
                     <div id="edit_name_message"><?=$msg?></div>
   						<label for="firstname">First name</label>
   						<input type="text" name="firstname" value="" placeholder="<?php if(isset($firstname)){echo $firstname;}?>" id="firstname"><br/>
   						<label for="lastname">Last name</label>
   						<input type="text" name="lastname" value="" placeholder="<?php if(isset($lastname)){echo $lastname;}?>" id="lastname"><br/>
   						<input type="submit" value="Confirm >>" id="confirm_name_change">
   					</form>
               </div>

               <div class="edit_email col-sm-12">
                  <h2>Email:</h2>
                  <form onsubmit="javascript:return false;">
                     <div id="edit_email_message"><?=$msg?></div>
                     <label for="email">Your email address:</label>
   						<input type="email" name="email" value="" placeholder="<?php if(isset($email)){echo $email;}?>" id="email"><br/>
                     <input type="submit" value="Confirm >>" id="confirm_email_change">
                  </form>
               </div>

               <div class="edit_profile_img col-sm-12">
                  <h2>Profile Image:</h2>
                  <form onsubmit="javascript: return false;">
                     <div id="edit_profile_img_message"><?=$msg?></div>
                     <img src="<?=$profile_img?>" alt="Profile Image">
                     <label for="profile_img">Image:
   							<input type="file" name="profile_img">
   						</label>
                     <input type="submit" value="Confirm >>" id="confirm_profile_img_change">
                  </form>
               </div>

               <div class="edit_password col-sm-12">
                  <div id="edit_password_message"><?=$msg?></div>
                  <h2>Password:</h2>
                  <form onsubmit="javascript:return false;">
                     <input type="submit" value="Confirm >>">
                  </form>
               </div>
            </div>

			</div>
		</div>
		<script>
			// When page has loaded, run function
			$(function(){
				$('#confirm_name_change').click(function(){
					// Send data from form to backend
					$.ajax({
						url: 'backend.php',
						method: 'POST',
						data: {
							user_id: $('#user_id').val(),
							firstname: $('#firstname').val(),
							lastname: $('#lastname').val(),
							mode: 'edit_name'
						}
					}).done(function(response){
                  // Update the greeting text
                  $('#greeting_txt_firstname').html(response.firstname);
                  $('#greeting_txt_lastname').html(response.lastname);
                  // Clear input values and update placeholder text
                  $('#firstname').val('');
                  $('#firstname').attr('placeholder', response.firstname);
                  $('#lastname').val('');
                  $('#lastname').attr('placeholder', response.lastname);
                  // Give feedback
                  $('#edit_name_message').html(response.message);
					});
				})

            $('#confirm_email_change').click(function(){
					// Send data from form to backend
					$.ajax({
						url: 'backend.php',
						method: 'POST',
						data: {
							user_id: $('#user_id').val(),
							email: $('#email').val(),
                     mode: 'edit_email'
						}
					}).done(function(response){
                  // Give feedback
                  $('#edit_email_message').html(response.message);
					});
				})
			});
		</script>
	</body>
</html>
