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
      <div id="carouselTitle"></div>
    </div>
  </div>
</div>
<br>

<br><br><br>
<!-- <script src="./javascript/indexAPIcalls.js"></script> -->
<script type="text/javascript" language="javascript">
    var versionUpdate = (new Date()).getTime();
    var script = document.createElement("script");
    script.type = "text/javascript";
    script.src = "./javascript/indexAPIcalls.js?v=" + versionUpdate;
    document.body.appendChild(script);
</script>

<br><br>
<footer style="position:relative;bottom:-200px;width:100%;">
  <div id="cred">&nbsp;&nbsp;&nbsp;<a href="https://abdielarias.com/">return to portfolio (abdielarias.com)</a><br><br>
<span style="color: #1c1c1c">This product uses the TMDb API but is not endorsed or certified by TMDb.</span></div><br>
</footer>

</body>
</html>
