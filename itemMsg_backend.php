<?php
header ("Content-Type: application/json");
session_start();
require_once("connect.php");
require_once("functions.php");

// Vars from SESSION and POST recived from message sendt
$itemID = $_POST['itemID'];
$itemOwnerID = $_POST['itemOwnerID'];
$userID = $_POST['userID'];
$firstMessage = $_POST['firstMessage'];


if(empty($firstMessage)){ //If message empty, return errormessage
   echo json_encode(array("status"=>"error", "message"=>"<p class='error'>Message cannot be empty</p>"));
   return;
   }
   else //If message is NOT empty
   {
      // Make new conversation
      $sql = "INSERT INTO conversations (itemID, itemOwnerID, userID) VALUES (?,?,?)";
      $stmnt = $db->prepare($sql);
      $res = $stmnt->execute(array($itemID, $itemOwnerID, $userID));

      //Get last conversation
      $sql = "SELECT * FROM conversations ORDER BY conversationID DESC LIMIT 1";
      $stmnt = $db->prepare($sql);
      $stmnt->execute(array());
      $result = $stmnt->fetchAll(PDO::FETCH_OBJ);
      // Put last conversation in a variable
      foreach ($result as $row)
         {
            $lastConversation = $row->conversationID;
         }

      //Insert message in new conversationID
      $sql = "INSERT INTO messages (writerID, conversationID, message) VALUES (?,?,?)";
      $stmnt = $db->prepare($sql);
      $res = $stmnt->execute(array($userID, $lastConversation, $firstMessage));

      // Successfull query
      if(isset($res)){
         // Update session parameters
         $newmessage_status = ""; //"<p class='success'>" . $lastMessage . "<b> IS SENDT</b></p>";
         echo json_encode(array("status"=>"success", "message"=>"<p class='success'>Message sent.<br>To see messages, go to profile.</p>"));
         return;
      } else {
         // Error when running query
         echo json_encode(array("status"=>"error", "message"=>$stmnt->errorInfo()[2]));
         return;
      }
   }
break;
 ?>
