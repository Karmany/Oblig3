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
   include 'header.php';

	if(!isset($_SESSION['isloggedin'])){
		header("Location: login.php");
	}

	// Retrieving session variables for the user
	$user_id = $_SESSION['user_id'];
	$email = $_SESSION['email'];
	$firstname = $_SESSION['firstname'];
	$lastname = $_SESSION['lastname'];
   $profile_img = $_SESSION['profile_img'];
   $user = get_user($user_id, $db);
   $counties = get_counties($db);
   $current_county = "";

   // Get the correct county name
   foreach ($counties as $county) {
      if($user->countyID == $county->countyID){
         $current_county = $county->name_nice;
      }
   }

   $msg = "";
	?>

	<body id="profile">
      <div class="container-fluid">
			<div class="row">
				<div id="profile_section" class="col-sm-4">
               <h1 class="section_heading">Profile information</h1>

               <div class="edit_profile_img col-sm-12">
                  <!-- <h2>Profile Image:</h2> -->
                  <form id= "edit_profile_image_form" onsubmit="javascript: return false;" enctype="multipart/form-data">
                     <div id="edit_profile_img_message"></div>
                     <label for="profile_img">Upload custom profile image:</label>
                     <img src="<?=$profile_img?>" alt="Profile Image">
                     <input type="file" name="profile_img">
                     <input type="submit" value="Confirm" id="confirm_profile_img_change">
                  </form>
               </div>

               <div class="edit_name col-sm-12">
                  <!-- Stop form from being sent, want to do it with ajax instead -->
                  <form onsubmit="javascript: return false;">
                     <div id="edit_name_message"></div>
   						<label for="firstname">First name</label>
   						<input type="text" name="firstname" value="" placeholder="<?php if(isset($firstname)){echo $firstname;}?>" id="firstname"><br/>
   						<label for="lastname">Last name</label>
   						<input type="text" name="lastname" value="" placeholder="<?php if(isset($lastname)){echo $lastname;}?>" id="lastname"><br/>
   						<input type="submit" value="Confirm" id="confirm_name_change">
   					</form>
               </div>

               <div class="edit_email col-sm-12">
                  <form onsubmit="javascript:return false;">
                     <div id="edit_email_message"></div>
                     <label for="email">Your email address:</label>
   						<input type="email" name="email" value="" placeholder="<?php if(isset($email)){echo $email;}?>" id="email"><br/>
                     <input type="submit" value="Confirm" id="confirm_email_change">
                  </form>
               </div>

               <div class="edit_password col-sm-12">
                  <form onsubmit="javascript:return false;">
                     <div id="edit_password_message"></div>
                     <label for="current_password">Your current password:</label>
                     <input type="password" name="current_password" id="current_password">
                     <label for="new_password">New password:</label>
                     <input type="password" name="new_password" id="new_password">
                     <label for="confirm_password">Confirm password:</label>
                     <input type="password" name="confirm_password" id="confirm_password">
                     <input type="submit" value="Confirm" id="confirm_password_change">
                  </form>
               </div>

               <div class="edit_address col-sm-12">
                  <form onsubmit="javascript:return false;">
                     <div id="edit_address_message"></div>
                     <input type="text" name="address" value="" placeholder="<?=$user->address?>" id="address"><br/>
   						<label for="lastname">County</label>
                     <select id="county" name="county">
                        <?php
                        // Make sure the selected option is the original category
                        echo "<option selected='$current_county'>$current_county</option>";
                        foreach ($counties as $county) {
                           if($county->name_nice != $current_county){
										echo "<option>" . $county->name_nice . "</option>";
									}
                        }
                        ?>
                     </select>
                     <input type="submit" value="Confirm" id="confirm_address_change">
                  </form>
               </div>

            </div>

            <div id="messages_section" class="col-sm-8">
               <div class="row">
                  <div class="col-sm-12">
                     <h1 class="section_heading">Messages</h1>
                  </div>
                  <?php
                     include 'messages.php';
                  ?>
               </div>
            </div>

			</div>
		</div>
		<script>
			// When page has loaded, run function
			$(function(){
            // ---- CHANGE NAME -----
				$('#confirm_name_change').click(function(){
					// Send data from form to backend
					$.ajax({
						url: 'profile_backend.php',
						method: 'POST',
						data: {
							firstname: $('#firstname').val(),
							lastname: $('#lastname').val(),
							mode: 'edit_name'
						}
					}).done(function(response){
                  // Give feedback
                  $('#edit_name_message').html(response.message);
                  if(response.status == 'success'){
                     // Update the greeting text
                     $('#greeting_txt_firstname').html(response.firstname);
                     $('#greeting_txt_lastname').html(response.lastname);
                     // Clear input values and update placeholder text
                     $('#firstname').val('');
                     $('#firstname').attr('placeholder', response.firstname);
                     $('#lastname').val('');
                     $('#lastname').attr('placeholder', response.lastname);
                  }
					});
				})

            // ---- CHANGE EMAIL ----
            $('#confirm_email_change').click(function(){
					// Send data from form to backend
					$.ajax({
						url: 'profile_backend.php',
						method: 'POST',
						data: {
							email: $('#email').val(),
                     mode: 'edit_email'
						}
					}).done(function(response){
                  // Give feedback
                  $('#edit_email_message').html(response.message);
                  // Clear input field and update placeholder text
                  if(response.status == 'success'){
                     $('#email').val(''),
                     $('#email').attr('placeholder', response.email)
                  }
					});
				})

            // ---- CHANGE PROFILE IMAGE ----
            $('#confirm_profile_img_change').click(function(){
               // Get form data and add variable for mode to it
               var form = $('#edit_profile_image_form')[0];
               var form_data = new FormData(form);
               form_data.append('mode', 'edit_profile_image');
					$.ajax({
						url: 'profile_backend.php',
						method: 'POST',
						data: form_data,
                  // Stop Content-Type header from being added automatically
                  contentType: false,
                  // Stop FormData from trying to convert into string
                  processData: false
					}).done(function(response){
                  // Give feedback
                  $('#edit_profile_img_message').html(response.message);
                  // Show the new profile image
                  $('#edit_profile_image_form img').attr('src', response.profile_img);
					});
				})

            // ---- CHANGE PASSWORD ----
            $('#confirm_password_change').click(function(){
               // Send data from form to backend
               $.ajax({
                  url: 'profile_backend.php',
                  method: 'POST',
                  data: {
                     current_password: $("#current_password").val(),
                     new_password: $("#new_password").val(),
                     confirm_password: $("#confirm_password").val(),
                     mode: 'edit_password'
                  }
               }).done(function(response){
                  // Give feedback
                  $('#edit_password_message').html(response.message);
                  // Clear input fields
                  if(response.status == 'success'){
                     $("#current_password").val(''),
                     $("#new_password").val(''),
                     $("#confirm_password").val('')
                  }
               });
            })

            // ---- CHANGE PASSWORD ----
            $('#confirm_address_change').click(function(){
               // Send data from form to backend
               $.ajax({
                  url: 'profile_backend.php',
                  method: 'POST',
                  data: {
                     address: $("#address").val(),
                     county: $("#county").val(),
                     mode: 'edit_address'
                  }
               }).done(function(response){
                  // Give feedback
                  $('#edit_address_message').html(response.message);
                  // Clear input fields
                  if(response.status == 'success'){
                     $("#address").val(''),
                     $("#address").attr('placeholder', response.address),
                     $("#county option[value='" + response.county +"']").attr("selected", "selected");
                  }
               });
            })

			});
		</script>
	</body>
</html>
