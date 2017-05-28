<?php
    session_start();
 ?>


<header>
    <nav>
        <ul>

            <li><a href="index.php">HOME</a></li>
            <?php
            //Vises om man er logget inn
            if (isset($_SESSION['user_id'])) {
                echo "<li><a href='post.php'>POST</a></li>";
                echo "<li><a href='mynews.php'>MY NEWS</a></li>";
                echo "<li><a href='profile.php'>PROFILE</a></li>";
                echo "<form action='includes/logout.inc.php'>
                    button>LOG OUT</button>
                    </form>";
            } else {
                // Er man ikke logget inn får man opp innloggingsskjema
                echo "<form action='includes/login.inc.php' method='POST'>
                    <input type='text' name='uid' placeholder='Username'>
                    <input type='password' name='pwd' placeholder='Password'>
                    <button type='submit'>LOGIN</button>
                    </form>";
            }

            //Er man ikke logget inn kan man registrere seg
            if (!isset($_SESSION['id'])) {
                echo "<li><a href='signup.php'>SIGNUP</a></li>";
            }

             ?>

        </ul>
    </nav>
</header>
