<?php
include 'header.php';
?>

<!--
<div id="frontPosterContainer">
  <p id="comingSoon">Coming Soon to Theaters</p>
  <img src="images/tenet.jpg" class="frontPosters">
  <img src="images/mulan.jpg" class="frontPosters">
  <img src="images/unhinged.jpg" class="frontPosters">
</div> -->

<!--look up some API data and display posters of the latest movies-->
<div class="hero">
  <div class="carouselContainer">
    <div class="carouselBackground">

    </div>
    <div class="carousel">
      <span class="leftCarouselButton">&#10094;</span><div class="imgWrapper"><img src="" id="currentImage" alt="api carousel"></div><span class="rightCarouselButton">&#10095;</span>
    </div>
  </div>
</div>

<script src="./javascript/indexAPIcalls.js"></script>
<script src="./javascript/carousel.js"></script>



<?php
include 'footer.php';
?>
