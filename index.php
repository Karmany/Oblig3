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
                  <div class="filter_wrap">
                     <input type="checkbox" id="'. $c->categoryID .'">
                     <label for="'. $c->categoryID .'" class="filter_text">'. ucfirst($c->name) .'</label>
                  </div>
               ';
            }
            ?>

            <h4>Counties</h4>

            <?php
            // Run the county function that gets all counties ordered by name.
            $counties = get_counties($db);
            // Loop through the counties and max a checkbox for each one to filter with
            foreach ($counties as $c) {
					echo '
            <div class="filter_wrap">
               <input type="checkbox" id="' . $c->name . '">
               <label for="' . $c->name . '" class="filter_text">' . $c->name_nice . '</label>
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
                  	// Delete the current conent in #result, which is the items
                  	$("#result").html("");
                  	// Add new content in #result that fit the filter options
                     for(var i=0; i<result.length; i++){
                     	//Make a variable with html to display item
								var content = '<a href="item.php?itemID='+ result[i]['itemID'] +'" class="col-sm-4 one_item"> ';
								// Add more and more html to the same variable
								content += '<h3>' + result[i]['name'] + '</h3>';
								content += '<div class="index_img_wrap"><img class="item_img" src="' + result[i]['imgPath'] + '"></div> ';
								content += '<p class="index_name">By: ' + capitalizeFirstLetter(result[i]['firstname']) + ' ' + capitalizeFirstLetter(result[i]['lastname']) + '</p>';
								content += '<p class="category_label index_category_label">'+ capitalizeFirstLetter(result[i]['category_name']) +'</p>';
								content += '<p class="index_date">' + result[i]['date'] + '</p>';
								content += '</a>';
								// Add the content of the variable into result and start over with the next item.
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
                     // Go through each item and show them on the page.
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