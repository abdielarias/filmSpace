<?php
include 'header.php';

if(isset($_GET["selector"])){
  $selector = $_GET["selector"];
}
else{
  header("Location: password-reset-request.php?resetMessage=invalidTokens");
  exit();
}

if(isset($_GET["validator"])){
  $validator = $_GET["validator"];
}
else {
  header("Location: password-reset-request.php?resetMessage=invalidTokens");
  exit();
}

if (empty($selector) || empty($validator)){
  header("Location: password-reset-request.php?resetMessage=invalidTokens");
  exit();
}
else{

  //check if they are not valid hex digits:
  if(!ctype_xdigit($selector) || !ctype_xdigit($validator)){
    header("Location: password-reset-request.php?resetMessage=invalidTokens");
    exit();
  }

}


?>
<div class="signinPanel">


  <p>Enter a New Password</p>
  <form action="createNewPasswordVerify.php" method="post">
    <input type="hidden" name="selector" value="<?php echo $selector; ?>">
    <input type="hidden" name="validator" value="<?php echo $validator; ?>">
    <input type="password" name="password" placeholder="Password"><br>
    <input type="password" name="passwordConfirm" placeholder="Confirm Password"><br>
    <input type="submit" name="submitButton" value="submit"><br>
  </form>

  <?php
  //Check for error messages and print to screen.
    if(isset($_GET['error'])){
      if($_GET['error'] == "emptyFields"){
        echo '<p class="warning">Please fill in all fields.</p>';
      }
      else if($_GET['error'] == "invalidUsername"){

        echo '<p class="warning">Invalid Username.</p>';

      } else if($_GET['error'] == "invalidPassword"){

        echo '<p class="warning">Invalid Password.</p>';
      }
    }
  ?>
</div>

<?php
include 'footer.php';
?>
