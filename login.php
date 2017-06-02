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
	// User is already logged in, redirect to index.php
	if(isset($_SESSION['isloggedin'])){
		header("Location: index.php");
	}
	?>

	<body id="login">
		<?php require_once("header.php"); ?>
		<div class="container-fluid">
			<div class="row">
				<div class="col-sm-6 col-sm-offset-3">
					<div id="loginFormWrapper">
						<form class="loginForm" action="login.php" onsubmit="javascript:return false;">
							<input id="email" type="email" name="email" placeholder="Email" value="<?php if(isset($_POST['email'])){ echo $_POST['email']; } ?>">
							<input id="password" type="password" name="password" placeholder="Password">
							<input id="login_submit" type="submit" name="login" value="Log in">
						</form>
					</div>
					<div id="login_message"></div>
				</div>
			</div>
		</div>
		<script>
			// When page has loaded, run function
			$(function(){
				$('#login_submit').click(function(){
					$.ajax({
						url: 'login_backend.php',
						method: 'POST',
						data: {
							email: $('#email').val(),
							password: $('#password').val()
						}
					}).done(function(response){
						// Give feedback
						$('#login_message').html(response.message);
						// Validation is ok, redirect to login page
						if(response.status == 'success'){
							location.href = 'index.php';
						}
					});
				});
			});
		</script>
	</body>
</html>
