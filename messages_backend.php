<?php
header ("Content-Type: application/json");
session_start();
require_once("connect.php");
require_once("functions.php");

$user_id = $_SESSION['user_id'];
$convID = $_POST['convID'];
$newmessage = $_POST['newmessage'];



if(empty($newmessage)){
   //echo json_encode(array("test"=>"halla"));
   echo json_encode(array("status"=>"error", "message"=>"<p class='error'>Message cannot be empty</p>"));
   return;
   }
   else
   {
      // Put message in DB
      $sql = "INSERT INTO messages (writerID, conversationID, message) VALUES (?,?,?)";
      $stmnt = $db->prepare($sql);
      $res = $stmnt->execute(array($user_id, $convID, $newmessage));

      //Get last message
      $sql = "SELECT * FROM messages ORDER BY messageID DESC LIMIT 1";
      $stmnt = $db->prepare($sql);
      $stmnt->execute(array());
      $result = $stmnt->fetchAll(PDO::FETCH_OBJ);

      // Put last message in a variable
      foreach ($result as $row)
         {
            $lastMessage = $row->message;
         }

      if($result == 1){ // Successfull query
         // Update session parameters
         $newmessage_status = "<p class='success'>" . $lastMessage . "<b> IS SENDT</b></p>";
         echo json_encode(array("status"=>"success", "message"=>$newmessage_status));
         }
         else
         { // Error when running query
            echo json_encode(array("status"=>"error", "message"=>$stmnt->errorInfo()[2]));
         }
   }
break;
 ?>
