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
      <link rel='stylesheet' type='text/css' href='css/styles.css?<?php echo time(); ?>'>
   </head>


	<?php
      session_start();
		require_once("connect.php");
		require_once("functions.php");



	?>

	<body id="index">
      <?php include 'header.php'; ?>

      <div class="container-fluid">

         <div class="row">

         <div id="filter" class="col-sm-2 col-sm-offset-1">
            <h2>Filter</h2>

            <h4>Categories</h4>

            <?php
            $stmt = $db->prepare("
            SELECT categoryID, name 
            FROM categories
            ORDER BY name
            ");
            $stmt->execute();

            $categories = $stmt->fetchAll(PDO::FETCH_OBJ);
            foreach ($categories as $c){
               echo'
                  <div>
                     <input type="checkbox" id="'. $c->categoryID .'">
                     <label for="'. $c->categoryID .'">'. ucfirst($c->name) .'</label>
                  </div>
               ';
            }
            ?>

            <h4>Counties</h4>
            <!--
            <div>
               <input type="checkbox" id="akershus">
               <label for="akershus">Akershus</label>
            </div>
            <div>
               <input type="checkbox" id="aust_agder">
               <label for="aust_agder">Aust-agder</label>
            </div>
            <div>
               <input type="checkbox" id="buskerud">
               <label for="buskerud">Buskerud</label>
            </div>
            <div>
               <input type="checkbox" id="finnmark">
               <label for="finnmark">Finnmark</label>
            </div>
            <div>
               <input type="checkbox" id="hedmark">
               <label for="hedmark">Hedmark</label>
            </div>
            <div>
               <input type="checkbox" id="hordaland">
               <label for="hordaland">Hordaland</label>
            </div>
            <div>
               <input type="checkbox" id="more_og_romsdal">
               <label for="more_og_romsdal">Møre og romsdal</label>
            </div>
            <div>
               <input type="checkbox" id="nord_trondelag">
               <label for="nord_trondelag">Nord Trøndelag</label>
            </div>
            <div>
               <input type="checkbox" id="nordland">
               <label for="nordland">Nordland</label>
            </div>
            <div>
               <input type="checkbox" id="oppland">
               <label for="oppland">Oppland</label>
            </div>
            <div>
               <input type="checkbox" id="oslo">
               <label for="oslo">Oslo</label>
            </div>
            <div>
               <input type="checkbox" id="rogaland">
               <label for="rogaland">Rogaland</label>
            </div>
            <div>
               <input type="checkbox" id="sogn_og_fjordane">
               <label for="sogn_og_fjordane">Sogn og fjordane</label>
            </div>
            <div>
               <input type="checkbox" id="sor_trondelag">
               <label for="sor_trondelag">Sør trøndelag</label>
            </div>
            <div>
               <input type="checkbox" id="telemark">
               <label for="telemark">Telemark</label>
            </div>
            <div>
               <input type="checkbox" id="troms">
               <label for="troms">Troms</label>
            </div>
            <div>
               <input type="checkbox" id="vest_agder">
               <label for="vest_agder">Vest-agder</label>
            </div>
            <div>
               <input type="checkbox" id="vestfold">
               <label for="vestfold">Vestfold</label>
            </div>
            <div>
               <input type="checkbox" id="ostfold">
               <label for="ostfold">Østfold</label>
            </div>
-->
            <?php

            $counties = get_counties($db);
            foreach ($counties as $c) {
					echo '
            <div>
               <input type="checkbox" id="' . $c->name . '">
               <label for="' . $c->name . '">' . ucfirst($c->name_nice) . '</label>
            </div>
            ';
				}

            ?>
         </div>


         <div id="result" class="col-sm-8">
         </div>
         </div>


         <script>
         //Checks the checked boxes and puts their values into opts array
         //This object array is later sent to backend
            function get_item_filter_options(){
               var opts = [];
               $checkboxes.each(function(){
                  if(this.checked){
                     opts.push(this.id);
                     }
               });
               return opts;
            }
            //Function that triggers when a checkbox is checked or unchecked
            // This function sends a request to the backend file with the options array containing the filter options
            function update_items(opts){
               $.ajax({
                  method: "POST",
                  url: "index_backend.php",
                  dataType : 'json',
                  cache: false,
                  data: {filterOpts: opts,
                        mode: 'update_items'},
                  success: function(result){
                     $("#result").html("");
                     for(var i=0; i<result.length; i++){
                        var content = '<p>' + result[i]['name'] + '</p>'
                        content += '<img class="item_img" src="' + result[i]['imgPath'] + '"> '
                        content += '<br/>';
                        $("#result").append(content);
                     }
                  }
               });
            }
            //This function is called when page loads and when all checkboxes are unchecked
            //This function shows all items
            function show_all(){
            	$.ajax ({
						method: "POST",
						url: "index_backend.php",
						dataType : 'json',
						cache: false,
						data: {mode:'show_all'},
						success: function(result) {
							$("#result").html("");
							for ( var i = 0; i < result.length; i++) {

								var content = '<p>' + result[i]['name'] + '</p>';
                        content += '<img class="item_img" src="' + result[i]['imgPath'] + '"> ';
								content += '<br/>';
								$("#result").append(content);
							}
						}
               });
            }

            var $checkboxes = $("input:checkbox");
            $checkboxes.on('click',function(){
					if( $('input[type=checkbox]:checked').length>0){
						var opts = get_item_filter_options();
						update_items(opts);
               }
               else
						show_all()

            });
            $(document).ready(show_all());
            //$checkboxes.trigger("change");
         </script>
      </div>
   </body>
</html>