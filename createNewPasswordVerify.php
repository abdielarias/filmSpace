<?php

if(isset($_POST['submitButton'])){

  $selector = $_POST["selector"];
  $validator = $_POST["validator"];
  $password = $_POST['password'];
  $passwordConfirm = $_POST['passwordConfirm'];

  if(empty($password) || empty($passwordConfirm)){
    header('Location: createNewPassword.php?selector='.$selector.'&validator='.$validator.'&error=emptyFields');
    exit();
  }

  if(empty($selector) || empty($validator)){
    //If we don't have the tokens at all... we should start the whole process over.
    header("Location: password-reset-request.php?resetMessage=invalidTokens");
    exit();
  }

  if($password !== $passwordConfirm){
    header('Location: createNewPassword.php?selector='.$selector.'&validator='.$validator.'&error=passwordsDoNotMatch');
    exit();
  }

  /*At this point, let's assume no errors. We'll look into our pwdReset table and see if it exists. If it does, we'll check
  if the data of expiration has not passed. If so, take them back to the password-reset-request.php and start all over. Otherwise,
  we can accept the new password and insert it into the users database and then delete the pwdReset token record.*/

  $currentDate = date("U");

  require 'databaseConn.php';

  //this is causing a problem.... not interpretting as a string?

  $sql = "SELECT * FROM pwdReset where selector=?";

  $stmt = $conn->prepare($sql);
  $stmt->bind_param("s", $selector);
  $stmt->execute();

  $result = $stmt->get_result();

  $resultArray = $result->fetch_assoc();

  if($result->num_rows==0){
    echo "fatal error. num rows:".$result->num_rows;
    exit();
  }

  //echo "it works: " . $resultArray['email'];
  //exit();

  //check to see if the token given to us matches the one in this record of the table
  //convert token back to binary:
  $validatorBin = hex2bin($validator);
  //token in db is hashed. Lets compare it to our unhashed binary token in the db:
  $isValid = password_verify($validatorBin, $resultArray["token"]);

  if($isValid == false){
    echo "Fatal error: token comparison failed in db, where it should not have.";
    exit();
  }
  else if($isValid == true){

    //Now check if the date of expiration for the reset request has not passed:
    if($currentDate > $resultArray['expireTime']){
      //this reset request has expired, so delete the record from pwdReset and send the user to the password-reset-request page to start over
      $sql="DELETE FROM pwdReset WHERE selector=?;";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("s", $selector);
      $stmt->execute();

      header("Location: password-reset-request.php?resetMessage=resetExpired");
      exit();
    }

    //take the email for this in pwdReset and go to the users corresponding record to submit the new password (make sure to hash this password)
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $email = $resultArray['email'];
    $sql="UPDATE users SET password=? WHERE email=?;";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $hashedPassword, $email);
    $stmt->execute();

    //On successful password reset, delete the old reset request record in the pwdReset table
    $sql="DELETE FROM pwdReset WHERE selector=?;";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $selector);
    $stmt->execute();

    //Finally, redirect to sign in page with some success message
    header("Location: signin.php?successMessage=passwordResetSuccess");
    $stmt->close();
    $conn->close();
    exit();
  }

  $stmt->close();
  $conn->close();
}
else {
  header('Location: createNewPassword.php?selector='.$selector.'&validator='.$validator.'error=submitNotPressed');
  exit();
}


?>
