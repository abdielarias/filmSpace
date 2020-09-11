<?php
include 'header.php';
?>


<div class="hero">
  <div class="carouselContainer">
    <div class="carouselBackground"></div>
    <div class="carousel">
      <span class="leftCarouselButton">&#10094;</span>
      <div class="imgWrapper">
        <img src="" id="currentImage" alt="api carousel">
      </div>
        <span class="rightCarouselButton">&#10095;</span>
      <span id="carouselTitle">Title</span>
    </div>
  </div>
</div>

<h3 id="featureTitle">Features of this site:</h3>
<div class="featuresBanner">
  <div class="featuresPanel">
    <h3 style="text-align: center;">Languages</h3>
    <div style="display: grid; grid-template-columns: auto auto auto; align-items: center; justify-content: space-around;">
    <img src="./images/frontend.png" alt="html5 logo" width="150px">
    <!-- <div>CSS <img src="./images/css.png" alt="css logo" width="20px"></div> -->
    <!-- <div>javascript</div> -->
    <img src="./images/php.png" alt="css logo" width="80px">

    <span style="font-size: 2em;">  SQL </span>
    </div>

  </div>
  <div class="featuresPanel">
    <h1></h1>
  </div>
  <div class="featuresPanel">
    <h1></h1>
  </div>
  <div class="featuresPanel">
    <h1></h1>
  </div>
</div>
<br><br><br>
<script src="./javascript/indexAPIcalls.js"></script>
<script src="./javascript/carousel.js"></script>



<?php
include 'footer.php';
?>
