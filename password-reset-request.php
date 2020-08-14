<?php
include 'header.php';


 ?>

<div class="signinPanel">
  <p>Password Recovery<br><br>Please enter your email address:</p>
  <form action="passwordResetVerify.php" method="post">
    <input type="text" placeholder="Email" name="email">
    <input type="submit" value="Reset" name="password-reset-submit">
  </form>

<?php
//this message will be sent once we confirm the user exists and they submit the reset button
if(isset($_GET['resetMessage'])){

  if($_GET['resetMessage'] == "initialized"){

    echo '<p>A reset link has been sent to you. Please check your email.</p>';
    }
    else if($_GET['resetMessage'] == "emailDoesNotExist1" || $_GET['resetMessage'] == "emailDoesNotExist2"){

      echo '<p>This email does not have an account associated with it.</p>';
    }
    else if($_GET['resetMessage'] == "success"){

      echo '<p>Your password has been reset.</p>';
    }
    else if($_GET['resetMessage'] == "resetExpired"){

      echo '<p>Your reset request has expired. Request a new password reset.</p>';
    }
    else if($_GET['resetMessage'] == "emailEmpty"){

      echo '<p>Please enter your email if you would like to reset your password.</p>';
    }
    else if($_GET['resetMessage'] == "invalidTokens"){

      echo '<p>Link incomplete. Please enter your reset link exactly as it appears in your email.</p>';
    }
}
 ?>

</div>


 <?php
include 'footer.php';
  ?>
