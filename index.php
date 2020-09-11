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
<h1 id="featureTitle">Features of this site:</h1>
<div class="featuresBanner">
  <div class="featuresPanel">

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
