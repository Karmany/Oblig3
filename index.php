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
            <input type="checkbox" id="akershus">
            <label for="akershus">Akershus</label>
            <input type="checkbox" id="aust-agder">
            <label for="aust-agder">Aust-agder</label>
            <input type="checkbox" id="buskerud">
            <label for="buskerud">Buskerud</label>
            <input type="checkbox" id="finnmark">
            <label for="finnmark">Finnmark</label>
            <input type="checkbox" id="">
            <label for=""></label>
            <input type="checkbox" id="">
            <label for=""></label>
            <input type="checkbox" id="">
            <label for=""></label>
            <input type="checkbox" id="">
            <label for=""></label>
            <input type="checkbox" id="">
            <label for=""></label>
            <input type="checkbox" id="">
            <label for=""></label>
            <input type="checkbox" id="">
            <label for=""></label>
            <input type="checkbox" id="">
            <label for=""></label>
            <input type="checkbox" id="">
            <label for=""></label>
            <input type="checkbox" id="">
            <label for=""></label>
            <input type="checkbox" id="">
            <label for=""></label>
            <input type="checkbox" id="">
            <label for=""></label>
            <input type="checkbox" id="">
            <label for=""></label>
            <input type="checkbox" id="">
            <label for=""></label>
            <input type="checkbox" id="">
            <label for=""></label>
            <input type="checkbox" id="">
            <label for=""></label>
            <input type="checkbox" id="">
            <label for=""></label>
            <input type="checkbox" id="">
            <label for=""></label>

            <?php
            $stmt = $db->prepare('
            SELECT countyID, name
            FROM counties
            ORDER BY name
            ');
            $stmt->execute();

            $categories = $stmt->fetchAll(PDO::FETCH_OBJ);
            foreach ($categories as $c) {
					echo '
            <div>
               <input type="checkbox" id="' . $c->countyID . '">
               <label for="' . $c->countyID . '">' . ucfirst($c->name) . '</label>
            </div>
            ';
				}
            ?>
         </div>


         <div id="result"class="col-sm-8">
         </div>
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
                  data: {filterOpts: opts,
                        mode: 'update_items'},
                  success: function(result){
                     $("#result").html("");
                     for(i=0; i<result.length; i++){
                        content = '<p>' + result[i]['name'] + '</p>'
                        content += '<img class="item_img" src="' + result[i]['imgPath'] + '"> '
                        content += '<br/>';
                        $("#result").append(content);
                        if (opts){
									console.log(opts);
                        }
                        else
                        	console.log(a);
                     }
                  }
               });
            }
            function show_all(){
            	$.ajax ({
						method: "POST",
						url: "index_backend.php",
						dataType : 'json',
						cache: false,
						data: {mode:'show_all'},
						success: function(result) {
							$("#result").html("");
							for (i = 0; i < result.length; i++) {
								content = '<p>' + result[i]['name'] + '</p>'
                        content += '<img class="item_img" src="' + result[i]['imgPath'] + '"> '
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
			$( window ).load(show_all())




            //$checkboxes.trigger("change");
         </script>
      </div>
   </body>
</html>