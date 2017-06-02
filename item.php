<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Index</title>
		<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.0.47/jquery.fancybox.min.js"></script>
		<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.0.47/jquery.fancybox.min.css">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
		<link rel='stylesheet' type='text/css' href='css/styles.css?<?php echo time(); ?>'>
	</head>


	<?php
	session_start();
	require_once("connect.php");
	require_once("functions.php");
	$itemID = $_GET['itemID'];
	?>

	<body id="index">
		<?php include 'header.php'; ?>
		<?php $item = get_item($db, $itemID); ?>
		<div class="container-fluid">
			<div class="row">

				<div class='col-sm-8 col-sm-offset-2 list-group gallery'>
					<h2><?php echo $item->name ?></h2>
					<div class="row">
						<div class="col-sm-8">
							<div class="row">
							<?php

							$images = get_images($db, $itemID);
							foreach ($images as $i) {
								echo '
									<div class="col-sm-3">
										<a class="thumbnail fancybox one_picture" rel="ligthbox" href="'. $i->imgPath .'">
											<img class="img-responsive" alt="" src="'. $i->imgPath .'" />
										</a>
									</div>
								';
							}
							?>
							</div>
							<div class="row">
								<div>
									<h3>Description</h3>
									<p><?php echo $item->description ?></p>
								</div>
							</div>
						</div>
						<div class="col-sm-4">
							<div class="item_profile">
								<img src="<?php echo $item->profileImg ?>" class="img-circle" alt="">
								<p class="item_profile_name"><?php echo ucfirst($item->firstname) . ' ' . ucfirst($item->lastname) ?></p>
								<p><a href="mailto:<?php echo $item->email ?>"><?php echo $item->email ?></a></p>
								<p><?php echo $item->address . ', ' . $item->county_name?></p>
								<p></p>
							</div>
						</div>
					</div>
					<div class="row">
						
					</div>

					
					

				</div>
			</div>
		</div>
		<script>
			$(document).ready(function(){
				$(".fancybox").fancybox({
					openEffect: "none",
					closeEffect: "none"
				});
			});
		</script>

	</body>
</html>
