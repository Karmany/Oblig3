
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
      session_start();
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
      <?php include 'header.php'; ?>
      <div class="container-fluid">

          <div id="filter">
              <h2>Filtrering</h2>
              <div>
                  <input type="checkbox" id="kunst">
                  <label for="kunst">Antikviteter og kunst</label>
              </div>
              <div>
                  <input type="checkbox" id="elektronikk">
                  <label for="elektronikk">Elektronikk og hvitevarer</label>
              </div>
              <div>
                  <input type="checkbox" id="fritid">
                  <label for="fritid">Fritid hobby og underholdning</label>
              </div>
              <div>
                  <input type="checkbox" id="friluftsliv">
                  <label for="friluftsliv">Sport og friluftsliv</label>
              </div>
          </div>
          <div id="result">

          </div>



          <script>
			  //Checks the checked boxes and puts their values into variable
			  function get_item_filter_options(){
				  var opts = [];
				  $checkboxes.each(function(){
					  if(this.checked){
						  opts.push(this.id);
					  }
				  });
				  return opts;
			  }
                //
			  function update_items(opts){
				  $.ajax({
					  type: "POST",
					  url: "index_backend.php",
					  dataType : 'json',
					  cache: false,
					  data: {filterOpts: opts},
					  success: function(result){

						  $.each(result, function (k, v) {
							  content += '<p>' + result[k] + '</p>';
							  content += '<br/>';
							  $(content).appendTo("#result");
						  });




                          /*
                           var str = JSON.stringify(result);
                           $('#result').html(str);





                           $.each(parsed_result, function (key, data) {

                           $.each(data, function (index, data) {
                           content += '<p>' + index + ": " +  data + '</p>';
                           });
                           content += '<br/>';
                           $(content).appendTo("#result");
                           })



                           $.each(parsed_result, function (index, data) {
                           content += '<p>' + index + ": " +  data + '</p>';
                           content += '<br/>';
                           $(content).appendTo("#result");
                           });

                           */

					  }
				  });
			  }

			  var $checkboxes = $("input:checkbox");
			  $checkboxes.on("change", function(){
				  var opts = get_item_filter_options();
				  update_items(opts);
			  });

			  $checkboxes.trigger("change");



          </script>

          </div>
	</body>
</html>
