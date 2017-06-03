<!-- BOOTSTRAP MENU IS-SIGNED-IN -->
<nav class="navbar navbar-default navbar-static-top">
   <div class="container-fluid">
      <!-- Brand and toggle get grouped for better mobile display -->
      <div class="navbar-header">
         <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
         </button>
         <a class="navbar-brand" href="index.php">Free Stuff</a>
      </div>

      <!-- Collect the nav links, forms, and other content for toggling -->
      <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
         <ul class="nav navbar-nav">
            <li><a href="index.php">Home</a></li>


            <?php // Change menu if the user is logged in
               if (isset($_SESSION['user_id'])) {
                  echo "<li><a href='my_items.php'>My Items</a></li>";
                  echo "<li><a href='profile.php'>Profile</a></li>";
                  echo "<li><a href='logout.php'>Logout</a></li>";
               } else {
                  echo "<li><a href='register.php'>Register</a></li>";
                  echo "<li><a href='login.php'>Login</a></li>";
               }
            ?>

         </ul>
      </div><!-- /.navbar-collapse -->
   </div><!-- /.container-fluid -->
 </nav>
