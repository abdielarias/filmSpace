<?php
if(session_status()==PHP_SESSION_NONE) {

  session_start();
}

?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Film Space</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Amatic+SC:wght@700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="images/cube.png"/>
  </head>
  <body>
    <header>

      <nav id="navbar">


          <div id="homeBtn"><a id="homeBtnAnchor" href="index.php"><img id="headerLogo" src="./images/cube.png" >
            <span style="align-self:center;border-bottom-style:solid;border-bottom-color: #8c2800;">Film Space</span></a></div>



          <div id="navRightBlock">
            <a href="popularFilms.php">Popular Films</a>
            <a href="recentReviews.php">Recent Reviews</a>
            <!-- <a href="about.php">About</a> -->
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

        <div id="searchContainer"><input type="text" id="searchBox" maxlength="100" placeholder="search for a movie..."><img src="./images/searchIcon.png" alt="enter search" id="searchBtn"></div>
        <script>
            var searchBox = document.querySelector("#searchBox");
            var searchBtn = document.querySelector("#searchBtn");
            searchBtn.addEventListener("click", ()=>{window.location.href="search.php?search="+searchBox.value});

            searchBox.addEventListener("keyup", ()=>{
              if (event.keyCode === 13)
                window.location.href="search.php?search="+searchBox.value
            });
        </script>



      </nav>
    </header>
