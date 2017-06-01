<?php
session_start();
include 'connect.php';
include 'functions.php';

if(isset($_POST['submit'])){
   $message = get_post('message');
   $convID = get_post('convID');
   $user_id = $_SESSION['user_id'];
   echo $message . "<br>" . $convID . "<br>" . $user_id;


   $query = "INSERT INTO messages (writerID, conversationID, message) VALUES (?,?,?)";
   $stmnt = $db->prepare($query);
   $stmnt->execute(array($user_id, $convID, $message));
   header('Location: profile.php');
}

 ?>
