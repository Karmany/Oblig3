<?php

if (isset($_GET['itemID'])) {
    $currItem = (int)$_GET['itemID'];
}
if(isset($_SESSION['isloggedin'])){
   $user_id = $_SESSION['user_id'];
}

// Ask DB what ID owns the current item
$sql = "SELECT userID FROM items WHERE itemID = ?";
$stmnt = $db->prepare($sql);
$stmnt->execute(array($currItem));
$result = $stmnt->fetchAll(PDO::FETCH_OBJ);

// Put owner of item in a variable
foreach ($result as $row)
   {
      $usID = $row->userID;
   }

// See if the
if(isset($_SESSION['isloggedin'])){
   $sql = "SELECT conversationID FROM conversations WHERE itemID = ? AND userID = ?";
   $stmnt = $db->prepare($sql);
   $stmnt->execute(array($currItem, $user_id));
   $result = $stmnt->fetchAll(PDO::FETCH_OBJ);


   // Put results in var
   foreach ($result as $row)
      {
         $oldConv = $row->conversationID;
      }
}

if(isset($_SESSION['isloggedin'])){ // If user is signed in
   if ($usID == $user_id) { // If user owns the item
   } else if (!isset($oldConv)){ // If user don't own the item
      $convID = 1;
      echo "<div class='col-sm-12' id='startConvOuter'>
               <div id='newConvStatus'></div>
               <div id='startConvInner'>
                  <form onsubmit='javascript: return false;' class='startConvForm' method='POST'>
                     <input type='hidden' name='itemID' value='" . $currItem . "' id='itemID'>
                     <input type='hidden' name='itemOwnerID' value='" . $usID . "' id='itemOwnerID'>
                     <input type='hidden' name='userID' value='" . $user_id . "' id='userID'>
                     <input type='text' name='firstMessage' placeholder='Start conversation...' id='firstMessage'>
                     <input type='submit' name='submit' value='Send Message' id='startConv'>
                  </form>
               </div>
            </div>";
   } else {
      echo "<p>Cannot start new conversation, one already exists. See your profile.</p><br> <a href='profile.php' class='item_button'>Profile</a>";
   }
} else { // Not signed in
   echo '<div class="col-sm-12 interested_wrap"></div><p> You need to be logged in to send a message </p>';
   echo '<a href="register.php" class="item_button">Register</a> <a href="login.php" class="item_button">Login</a></div>';
}
?>

<script type='text/javascript'>
   $(function(){
      // ---- UPDATE MESSAGES -----
      $('#startConv').click(function(){
         // Send data from form to backend
         console.log('Sending data!');
         $.ajax({
            url: 'itemMsg_backend.php',
            method: 'POST',
            data: {
               itemID: $('#itemID').val(),
               itemOwnerID: $('#itemOwnerID').val(),
               userID: $('#userID').val(),
               firstMessage: $('#firstMessage').val()
            }
         }).done(function(response){
               // Make new message
               console.log('Done runs!');
               $('#newmessage').val('');
               $('#newConvStatus').html(response.message);
               if(response.status == 'success'){
                  $( "#startConvInner" ).remove();
                  $( "<br><br><a href='profile.php' class='item_button'>Profile</a>" ).insertAfter( "#newConvStatus" );
                  console.log('Status: successfull!');
               }
            }
         );
      });
   });
</script>
