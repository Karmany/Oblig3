<?php
// Get messages from databse
$sql = "SELECT messageID, writerID, message FROM messages WHERE conversationID = 1";
$stmnt = $db->prepare($sql);
$stmnt->execute(array());
$result = $stmnt->fetchAll(PDO::FETCH_OBJ);

// ID of current user
$user_id = $_SESSION['user_id'];

//echo "<pre>";
//print_r($result);
//echo "<pre>";

// Print conversation
foreach ($result as $row)
   {
      if ($row->writerID == $user_id) {
         echo "<div><p class='float-left msg-width'>" . "Written by you: " . $row->message . "</p></div><br>";
         //echo "Written by you: " . $row->message . "\n";
      } else {
         echo "<div><p class='float-right msg-width'>" . "Written by someone else: " . $row->message . "</p></div><br>";
         //echo "Written by someone else: " . $row->message . "\n";
      }
   }

 ?>
