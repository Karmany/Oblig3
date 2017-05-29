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

      include 'header.php';
   ?>

   <body id="index">
      <div class="container-fluid">

         <div id="filter">
            <h2>Filter</h2>
            <div>
               <input type="checkbox" id="art">
               <label for="art">Antikviteter og kunst</label>
            </div>
            <div>
               <input type="checkbox" id="elektronics">
               <label for="elektronics">Elektronikk og hvitevarer</label>
            </div>
            <div>
               <input type="checkbox" id="entertainment">
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
                  method: "POST",
                  url: "index_backend.php",
                  dataType : 'json',
                  cache: false,
                  data: {filterOpts: opts},
                  success: function(result){
                     $("#result").html("");
                     for(i=0; i<result.length; i++){
                        content = '<p>' + result[i]['itemID'] + '</p>';
                        content += '<p>' + result[i]['name'] + '</p>'
                        content += '<p>' + result[i]['description'] + '</p>'
                        content += '<p>' + result[i]['category_name'] + '</p>'
                        content += '<br/>';
                        $("#result").append(content);
                     }
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
