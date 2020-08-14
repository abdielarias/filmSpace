<?php
include 'header.php';
?>

<div class="signinPanel">

  <p>Create a New Account</p>
  <form action="createAccountVerify.php" method="post">
  <?php
  //If either firstName, lastName, email, or userName were already filled, refill those fields

    if(isset($_GET['firstName'])){
      echo '<input type="text" name="firstName" value="'.$_GET['firstName'].'"><br>';
    }
    else{
      echo '<input type="text" name="firstName" placeholder="First Name"><br>';
    }

    if(isset($_GET['lastName'])){
      echo '<input type="text" name="lastName" value="'.$_GET['lastName'].'"><br>';
    }
    else{
      echo '<input type="text" name="lastName" placeholder="Last Name"><br>';
    }

    if(isset($_GET['email'])){
      echo '<input type="text" name="email" value="'.$_GET['email'].'"><br>';
    }
    else{
      echo '<input type="text" name="email" placeholder="Email"><br>';
    }

    if(isset($_GET['userName'])){
      echo '<input type="text" name="userName" value="'.$_GET['userName'].'"><br>';
    }
    else{
      echo '<input type="text" name="userName" placeholder="Username"><br>';
    }

   ?>

    <input type="password" name="password" placeholder="Password"><br>
    <input type="passwordConfirm" name="passwordConfirm" placeholder="Confirm Password"><br>
    <input type="submit" name="submitButton" value="submit"><br>
  </form>

  <?php
  //check if error returned called empty fields. If so, print error message.
    if(isset($_GET['error'])){

      if($_GET['error'] == "emptyfields"){

        echo '<p class="warning">Please fill in all fields.</p>';
      }
      else if($_GET['error'] == "invalidEmail"){

        echo '<p class="warning">Invalid email.</p>';
      }
      else if($_GET['error'] == "passwordPatternFail"){

        echo '<p class="warning">Password cannot include symbols, only letters and numbers.</p>';
      }
      else if($_GET['error'] == "passwordsDoNotMatch"){

        echo '<p class="warning">Passwords do not match.</p>';
      }
      else if($_GET['error'] == "usernameTaken"){

        echo '<p class="warning">That username is already taken.</p>';
      }
      else if($_GET['error'] == "emailTaken"){

        echo '<p class="warning">That email is already used by another account.</p>';
      }

    }


   ?>

  <a href="signin.php" class="submitButton">Already have an account? Sign in here.</a>
</div>





<?php
include 'footer.php';
?>
