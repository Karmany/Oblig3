<?php
	echo "Today is " . date("d-m-Y H:i");

	$img_paths = array("PATH");

	$new_item = array("name"=>"name", "category"=>"category", "date"=>date("d-m-Y H:i"));

	$new_item['new_item'] = $img_paths[0];

	echo "<pre>";
	print_r($new_item);
	echo "</pre>";
?>
