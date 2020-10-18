<?php
if(session_status()==PHP_SESSION_NONE) {

  session_start();
}

?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <meta charset="utf-8">
    <title>Film Space</title>

  

    <!-- Phone Styles -->
    <link rel="stylesheet" href="phoneStyles.css?<?php echo time(); ?>">

    <!-- Tablet Styles -->
    <link rel="stylesheet" href="tabletStyles.css?<?php echo time(); ?>">

    <!-- Desktop Styles -->
    <link rel="stylesheet" href="styles.css?<?php echo time(); ?>">

    <!-- fav icon  -->
    <link rel="icon" type="image/png" href="images/cube.png"/>
  </head>
  <body>
    <header>

      <nav id="navbar">

          <div id="homeBtn"><a id="homeBtnAnchor" href="index.php"><img id="headerLogo" src="./images/cube.png" >
            <span style="align-self:center;border-bottom-style:solid;border-bottom-color: #8c2800;">Film Space</span></a></div>



          <div id="navRightBlock">
            <a href="popularFilms.php">Most Popular</a>
            <!-- <a href="topRated.php">Top Rated</a>
            <a href="nowPlaying.php">Now Playing</a> -->
            <a href="recentReviews.php">Community Reviews</a>

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
              //If user presses enter key:
              if (event.keyCode === 13)
                window.location.href="search.php?search="+searchBox.value
            });
        </script>



      </nav>
    </header>
