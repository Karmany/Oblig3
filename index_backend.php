<?php
session_start();
require_once("connect.php");
require_once("functions.php");

//gets the checked boxes from index.php
$opts = $_POST['filterOpts'];
//Creates a variable for SQL with as many questionmarks as there was boxes checked
$qMarks = str_repeat('?,', count($opts) - 1) . '?';
//Statment with gets the info we want from items aswell as the category it is in(Trenge kansje ikkje dette, men må ha imgPath på et punkt)
$statement = $db->prepare("
SELECT items.itemID, items.name, items.description, categories.name AS category_name
FROM items INNER JOIN categories ON categories.categoryID = items.categoryID
WHERE categories.name IN ($qMarks)");
  $statement ->execute($opts);
  $results = $statement ->fetchAll(PDO::FETCH_ASSOC);
  //Puts the results from the statement into JSON
  $json = json_encode($results);
  //Echos the JSON back to index.php to be displayed there
  echo($json);


// Ting jeg ikke vil slette før jeg er done :P
/*
$select = 'SELECT *';
$from = ' FROM mobile_phones';
$where = ' WHERE ';
$opts = $_POST['filterOpts'];

if (empty($opts)){
  // 0 checkboxes checked
  $where .= 'TRUE';
} else {
  if(count($opts) == 1){
	  // 1 checkbox checked
	  $where .= $opts[0] . ' = 1';
  } else {
	  // 2+ checkboxes checked
	  $where .= implode(' = 1 OR ', $opts) . ' = 1';
  }
}

$sql = $select . $from . $where;
$statement = $db->prepare($sql);
$statement->execute();
$results = $statement->fetchAll(PDO::FETCH_ASSOC);
$json = json_encode($results);
echo($json);
*/

/*




SELECT items.itemID, items.name, items.description, categories.name
FROM items, categories
WHERE categories.categoryID = items.categoryID
AND categories.name IN ($qMarks)");
*/
?>