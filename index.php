<!DOCTYPE html>
<html>
   <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title>Item</title>
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
            //Calls a function that runs a query that gets all categories ordered by name
            $categories = get_categories($db);
            // Go though all categories in result of previous function and make a checkbox for each one
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
            // Run the county function that gets all counties ordered by name.
            $counties = get_counties($db);
            // Loop through the counties and max a checkbox for each one to filter with
            foreach ($counties as $c) {
					echo '
            <div>
               <input type="checkbox" id="' . $c->name . '">
               <label for="' . $c->name . '">' . $c->name_nice . '</label>
            </div>
            ';
				}

            ?>
         </div>


         <div class="col-sm-8">
            <div class="row" id="result"></div>
         </>
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
            function capitalizeFirstLetter(string) {
               return string.charAt(0).toUpperCase() + string.slice(1);
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
								var content = '<a href="item.php?itemID='+ result[i]['itemID'] +'" class="col-sm-4 one_item"> ';
								content += '<h3>' + result[i]['name'] + '</h3>';
								content += '<div class="index_img_wrap"><img class="item_img" src="' + result[i]['imgPath'] + '"></div> ';
								content += '<p class="index_name">By: ' + capitalizeFirstLetter(result[i]['firstname']) + ' ' + capitalizeFirstLetter(result[i]['lastname']) + '</p>';
								content += '<p class="category_label index_category_label">'+ capitalizeFirstLetter(result[i]['category_name']) +'</p>';
								content += '<p class="index_date">' + result[i]['date'] + '</p>';
								content += '</a>';
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
                     console.log(result);
							for ( var i = 0; i < result.length; i++) {
								var content = '<a href="item.php?itemID='+ result[i]['itemID'] +'" class="col-sm-4 one_item"> ';
								content += '<h3>' + result[i]['name'] + '</h3>';
                        content += '<div class="index_img_wrap"><img class="item_img" src="' + result[i]['imgPath'] + '"></div> ';
								content += '<p class="index_name">By: ' + capitalizeFirstLetter(result[i]['firstname']) + ' ' + capitalizeFirstLetter(result[i]['lastname']) + '</p>';
								content += '<p class="category_label index_category_label">'+ capitalizeFirstLetter(result[i]['category_name']) +'</p>';
								content += '<p class="index_date">' + result[i]['date'] + '</p>';
                        content += '</a>';
								$("#result").append(content);
							}
						}
               });
            }
            //Script that reacts on click on any checkbox and runs one of the two functions to show data.

            var $checkboxes = $("input:checkbox");
            $checkboxes.on('click',function(){
            	//If any is checked get filter options and run the update items function
					if( $('input[type=checkbox]:checked').length>0){
						var opts = get_item_filter_options();
						update_items(opts);
               }
               //If the click was unchecking the last checked box, and no boxes are checked show all the content
               else
						show_all()

            });
            //Show all content on first load
            $(document).ready(show_all());
            //$checkboxes.trigger("change");
         </script>
      </div>
   </body>
</html>