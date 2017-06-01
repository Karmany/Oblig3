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

      // Find who owns the item, and write who the message is TO/FROM
      if ($user_id == $itOwID) { // The message is frome someone else
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
      { //If the message is from you
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




      // Print conversations
      foreach ($result as $row)
         {
            echo "<pre>";
            if ($row->writerID == $user_id) {
               echo "<div class='col-sm-12 text-right'><p class='messageRight'>" . $row->message . " <b>:You</b> " . "</p></div><br>";
               //echo "Written by you: " . $row->message . "\n";
            } else {
               echo "<div class='col-sm-12'><p class='messageLeft'>" . "<b>Other: </b>" . $row->message . "</p></div><br>";
               //echo "Written by someone else: " . $row->message . "\n";
            }
            echo "</pre>";
         }
      echo "<div class='col-sm-12'>
               <div class='sendMessage'>
                  <form class='sendMessageForm' action='sendMessage.php' method='POST'>
                     <input type='hidden' name='convID' value='" . $convID ."'>
                     <input type='text' name='message' placeholder='Write message...'>
                     <input type='submit' name='submit' value='Send Message'>
                  </form>
               </div>
            </div>";
   }
echo "</pre>";



 ?>
