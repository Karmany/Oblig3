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

	if(isset($_SESSION['isloggedin'])){
		header("Location: index.php");
	}

	// // Retrieve all counties
	$counties = get_counties($db);
	$msg = "";
	?>

	<body id="register">
		<?php require_once("header.php"); ?>
		<div class="container-fluid">
			<div class="row">
				<div class="col-sm-6 col-sm-offset-3">
					<div class="col-sm-12">
						<div class="registerFormWrapper">
							<form id="registerForm" onsubmit="javascript:return false;">
								<input type="text" id="firstname" name="firstname" placeholder="First Name">
								<input type="text" id="lastname" name="lastname" placeholder="Last Name">
								<input type="password" id="password" name="password" placeholder="Password">
								<input type="password" id="confirmed_password" name="confirmed_password" placeholder="Confirm Password">
								<input type="email" id="email" name="email" placeholder="Email">
								<input type="text" id="address" name="address" placeholder="Address">
								<select id="county" name="county">
									<?php foreach ($counties as $county): ?>
										<option value="<?=$county->name_nice?>"><?=$county->name_nice?></option>
									<?php endforeach; ?>
								</select>
								<input id="register_user" type="submit" name="submit" value="Register &#187;">
								<div id="register_message"></div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
		<script>
			// When page has loaded, run function
			$(function(){
				$('#register_user').click(function(){
					$.ajax({
						url: 'register_backend.php',
						method: 'POST',
						data: {
							firstname: $('#firstname').val(),
							lastname: $('#lastname').val(),
							password: $('#password').val(),
							confirmed_password: $('#confirmed_password').val(),
							email: $('#email').val(),
							address: $('#address').val(),
							county: $('#county').val()
						}
					}).done(function(response){
						// Give feedback
						$('#register_message').html(response.message);
						// Validation is ok, redirect to login page
						if(response.status == 'success'){
							location.href = 'login.php';
						}
					});
				});
			});
		</script>
	</body>
</html>
