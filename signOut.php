<?php
//Sign the person out.
session_start();
session_unset();
session_destroy();

include 'header.php';
?>
<div class="fullscreen">
  <div class="signinPanel">
    <p>That's a wrap!</p><p> Redirecting you home...</p>
    <img src="images/logoutReel.png" width="200px">
  </div>
</div>
<script>
  setTimeout(()=>{window.location.href="index.php"}, 4000);
</script>
<?php
include 'footer.php';
?>
