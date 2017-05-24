
<!DOCTYPE html>
<html>
   <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title>Index</title>
      <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
      <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
      <link rel='stylesheet' type='text/css' href='css/styles.css'>
   </head>

	<?php
		require_once("connect.php");
		// $query = 'CREATE TABLE IF NOT EXISTS test (
		// 	id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
		// 	test VARCHAR(100))';
		// if ($db->exec($query)===false){
		// 	die('Query failed(4):' . $db->errorInfo()[2]);
		// }
      //
		// $sql = "SELECT * FROM test";
		// $stmnt = $db->prepare($sql);
		// if (!$stmnt->execute(array()))
		// 	die('Query failed:' . $db->errorInfo()[2]);
      //
		// $result = $stmnt->fetchAll(PDO::FETCH_OBJ);
      //
		// foreach ($result as $r) {
		// 	echo $r->test;
		// }

	?>
	<body id="index">
      <div class="container-fluid">
			<div class="col-md-10 col-md-offset-1">
				<p>Content here</p>
			</div>
		</container>
	</body>
</html>
