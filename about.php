<?php
include 'header.php';
?>

<div class="fullscreen">
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
      <h3 style="text-align: center;">Account</h3>
      <ul>
        <li>Account creation</li>
        <li>Sign-In system</li>
        <li>Password Recovery system</li>
        <li>Upload a profile picture</li>
      </ul>
    </div>
    <div class="featuresPanel">
      <h3 style="text-align: center;">Search</h3>
      <ul>
        <li>Search bar</li>
        <li>Search results page</li>
        <li>TMDb API</li>
        <li>"load more" button for dynamic JS fetching</li>
      </ul>
    </div>
    <div class="featuresPanel">
      <h3 style="text-align: center;">Reviews</h3>
      <ul>
        <li>create, read, update, and delete movie reviews</li>
        <li>view a stream of public reviews</li>
        <li>ability to make reviews private</li>
        <li>like or dislike reviews</li>
      </ul>
    </div>
  </div>
</div>
<br><br><br>




<?php
include 'footer.php';
?>
