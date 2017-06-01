<?php
// ID of current user
$user_id = $_SESSION['user_id'];

$sql = "SELECT conversationID FROM conversations WHERE itemOwnerID = '$user_id' OR userID = '$user_id'";
$stmnt = $db->prepare($sql);
$stmnt->execute(array());
$result = $stmnt->fetchAll(PDO::FETCH_OBJ);

echo "<pre>";
print_r ($result);
foreach ($result as $row)
   {
      echo $row->conversationID;
   }
echo "</pre>";


// Get messages from database
$sql = "SELECT messageID, writerID, message FROM messages WHERE conversationID = 1";
$stmnt = $db->prepare($sql);
$stmnt->execute(array());
$result = $stmnt->fetchAll(PDO::FETCH_OBJ);


// Print conversation
foreach ($result as $row)
   {
      if ($row->writerID == $user_id) {
         echo "<div class='col-sm-12 text-right'><p class='messageRight'>" . $row->message . " <b>:Written by you</b> " . "</p></div><br>";
         //echo "Written by you: " . $row->message . "\n";
      } else {
         echo "<div class='col-sm-12'><p class='messageLeft'>" . "<b>Written by someone else: </b>" . $row->message . "</p></div><br>";
         //echo "Written by someone else: " . $row->message . "\n";
      }
   }

 ?>
