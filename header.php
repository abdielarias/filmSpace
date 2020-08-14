<?php
if(session_status()==PHP_SESSION_NONE) {

  session_start();
}

?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title></title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Amatic+SC:wght@700&display=swap" rel="stylesheet">
  </head>
  <body>
    <header>

      <nav id="navbar">
        <ul>

          <li id="homeBtnListItem"><a id="homeBtnAnchor" href="index.php">practice site</a></li>

          <?php

            if(isset($_SESSION['isLogged'])){
                if($_SESSION['isLogged'] == true){
                  echo '<li class="navButton"><a href="signOut.php">Sign Out</a></li>';
                  echo'<li class="navButton"><a href="profile.php">My Profile</a></li>';
                }
                else {
                  echo '<li class="navButton"><a href="signin.php">Sign In</a></li>';
                }
            }
            else {
              echo '<li class="navButton"><a href="signin.php">Sign In</a></li>';
            }

          ?>

          <li class="navButton"><a href="">Popular Films</a></li>
          <li class="navButton"><a href="">Question of the Week</a></li>
          <li class="navButton"><a href="recentReviews.php">Recent Reviews</a></li>


        </ul>

      </nav>
    </header>
