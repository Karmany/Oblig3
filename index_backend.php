<?php
session_start();
require_once("connect.php");
require_once("functions.php");
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


$opts = $_POST['filterOpts'];
$qMarks = str_repeat('?,', count($opts) - 1) . '?';
$statement = $db->prepare("
SELECT i.itemsID, i.name, i. , model, price 
FROM items INNER JOIN categories ON categoryID = i.categoryID 
WHERE name IN ($qMarks)");
  $statement ->execute($opts);
  $results = $statement ->fetchAll(PDO::FETCH_ASSOC);
  $json = json_encode($results);
  echo($json);
?>