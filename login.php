<!DOCTYPE html>
<html>
<<<<<<< HEAD
   <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title>Login</title>
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
?>

<?php include 'header.php'; ?>

<?php
// User is not logged in and clicks the login button
if (!isset($_SESSION['isloggedin']) && isset($_POST['login'])) {
	$email = get_post('email');
	$password = get_post('password');
=======
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
>>>>>>> origin/master

	// User is already logged in, redirect to profile.php
	if(isset($_SESSION['isloggedin'])){
		header("Location: profile.php");
	}

	// User is not logged in and clicks the login button
	if (!isset($_SESSION['isloggedin']) && isset($_POST['login'])) {
		$email = get_post('email');
		$password = get_post('password');

		if(empty($email) || empty($password)){
			$msg = "<p class='error'>Please enter your email and password</p>";
		}
		else {
			// Check database for users matching input email
			$query = "SELECT * FROM users WHERE email=?";
			$stmnt = $db->prepare ($query);
			if (!$stmnt->execute(array($email)))
			die('Query failed:' . $db->errorInfo()[2]);

			// All email addresses are supposed to be unique. Can therefore use fetch()
			// to get the last (the only) row matching the query
			$user = $stmnt->fetch(PDO::FETCH_OBJ);

			// If there is in fact a user with that username in the database
			if (count($user) != 0) {
				// Check that input password matches the users password stored in the database
				if(password_verify($password, $user->password)){
					// Set session parameters
					$_SESSION['user_id'] = $user->userID;
					$_SESSION['firstname'] = $user->firstname;
					$_SESSION['lastname'] = $user->lastname;
					$_SESSION['email'] = $email;
					$_SESSION['profile_img'] = $user->profileImg;
					$_SESSION['isloggedin'] = true;

					// Retrieve admin list, see functions.php
					// $admin_list = get_admins($db);
					// // If user is an admin, set Session variable for admin
					// foreach ($admin_list as $admin) {
					// 	if($admin->admin_id == $result->user_id){
					// 		$_SESSION['admin'] = true;
					// 	}
					// }

					// Redirect user to index.php
					header("Location: index.php");
					die();
				}
				// Bad password
				else {
					$msg = "<p class='error'>Wrong username or password</p>";
				}
			}
			// If there is no user with that username
			else {
				$msg = "<p class='error'>Wrong username or password</p>";
			}
		}
		echo $msg;
	}
	?>

	<body id="login">
		<div class="container-fluid">
			<div class="row">
				<div class="col-sm-10 col-sm-offset-1">
					<div class="loginFormWrapper">
						<form class="loginForm" action="login.php" method="POST">
							<input type="email" name="email" placeholder="Email" value="<?php if(isset($_POST['email'])){ echo $_POST['email']; } ?>">
							<input type="password" name="password" placeholder="Password">
							<input type="submit" name="login" value="Log in">
						</form>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>
