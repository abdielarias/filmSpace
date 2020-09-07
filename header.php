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


          <div id="homeBtn"><a id="homeBtnAnchor" href="index.php">practice site</a></div>

          <div id="navRightBlock">
            <a href="">Popular Films</a>
            <a href="">Question of the Week</a>
            <a href="recentReviews.php">Recent Reviews</a>
            <?php

              if(isset($_SESSION['isLogged'])){
                  if($_SESSION['isLogged'] == true){
                    echo'<a href="profile.php">My Profile</a>';
                    echo '<a href="signOut.php">Sign Out</a>';
                  }
                  else {
                    echo '<a href="signin.php">Sign In</a>';
                  }
              }
              else {
                echo '<a href="signin.php">Sign In</a>';
              }

            ?>
        </div>





      </nav>
    </header>
