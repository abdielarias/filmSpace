<?php
//Sign the person out.
session_start();
session_unset();
session_destroy();

include 'header.php';
?>

<div class="signinPanel">
  <p>You've signed out. That's a wrap!</p>
  <img src="images/logoutReel.png" width="200px">
</div>

<?php
include 'footer.php';
?>
