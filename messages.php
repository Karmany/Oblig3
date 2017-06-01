<?php
// ID of current user
$user_id = $_SESSION['user_id'];

$sql = "SELECT conversationID, itemOwnerID, userID FROM conversations WHERE itemOwnerID = '$user_id' OR userID = '$user_id'";
$stmnt = $db->prepare($sql);
$stmnt->execute(array());
$result = $stmnt->fetchAll(PDO::FETCH_OBJ);



foreach ($result as $row)
   {
      $convID = $row->conversationID;
      $itOwID = $row->itemOwnerID;
      $usID = $row->userID;


      $sql = "SELECT messageID, writerID, message FROM messages WHERE conversationID = '$convID'";
      $stmnt = $db->prepare($sql);
      $stmnt->execute(array());
      $result = $stmnt->fetchAll(PDO::FETCH_OBJ);

      if ($user_id == $itOwID) {
         $sql = "SELECT userID, firstname, lastname FROM users WHERE userID = '$usID'";
         $stmnt = $db->prepare($sql);
         $stmnt->execute(array());
         $result1 = $stmnt->fetchAll(PDO::FETCH_OBJ);

         foreach ($result1 as $row)
            {
               $uID = $row->userID;
               $fName = $row->firstname;
               $lName = $row->lastname;
            }
         echo "<hr><br><br> <h2>Message from: " . $fName . " " . "$lName" . "</h2>";
      }
      else
      {
         $sql = "SELECT userID, firstname, lastname FROM users WHERE userID = '$itOwID'";
         $stmnt = $db->prepare($sql);
         $stmnt->execute(array());
         $result2 = $stmnt->fetchAll(PDO::FETCH_OBJ);

         foreach ($result2 as $row)
            {
               $uID = $row->userID;
               $fName = $row->firstname;
               $lName = $row->lastname;
            }
         echo "<hr><br><br> <h2>Message to: " . $fName . " " . "$lName" . "</h2>";
      }




      // Print conversation
      foreach ($result as $row)
         {
            echo "<pre>";
            if ($row->writerID == $user_id) {
               echo "<div class='col-sm-12 text-right'><p class='messageRight'>" . $row->message . " <b>:Written by you</b> " . "</p></div><br>";
               //echo "Written by you: " . $row->message . "\n";
            } else {
               echo "<div class='col-sm-12'><p class='messageLeft'>" . "<b>Written by someone else: </b>" . $row->message . "</p></div><br>";
               //echo "Written by someone else: " . $row->message . "\n";
            }
            echo "</pre>";
         }
   }
echo "</pre>";




 ?>
