<?php
include 'header.php';

if(isset($_GET['emailUsername'])){
  $emailUsername = $_GET['emailUsername'];
}
else $emailUsername = "";
?>
<div class="fullscreen">
  <div class="signinPanel">


    <p>Sign In</p>
    <form action="signInVerify.php" method="post">
      <input type="text" name="emailUsername" placeholder="Email or Username" value="<?php echo $emailUsername ?>"><br>
      <input type="password" name="password" placeholder="Password"><br>
      <input type="submit" name="submitButton" value="submit" class="submitButton"><br>
    </form>


    <?php
    //Check for error messages and print to screen.
      if(isset($_GET['error'])){
        if($_GET['error'] == "emptyfields"){
          echo '<p class="warning">Please fill in all fields.</p>';
        }
        else if($_GET['error'] == "invalidUsername"){

          echo '<p class="warning">Invalid Username.</p>';

        } else if($_GET['error'] == "invalidPassword"){

          echo '<p class="warning">Invalid Password.</p>';
        }
      }

      //Check for success messages
      if(isset($_GET['successMessage'])){

        if($_GET['successMessage'] == "passwordResetSuccess"){
          echo '<p class="success">Password Reset Successfully.</p>';
        }
      }
    ?>
    <br><br><br>
    <a href="createAccount.php" class="submitButton">Create a New Account</a>
    <a href="password-reset-request.php" class="submitButton">Forgot Password</a>
  </div>
</div>
<?php
include 'footer.php';
?>
