<?php

if(isset($_POST['password-reset-submit'])){

  if(!empty($_POST['email'])){

    $email = $_POST['email'];

    //check if email exists first:
    require 'databaseConn.php';

    $sql = "SELECT * FROM users WHERE email=?";
    //prepare
    $stmt = $conn->prepare($sql);
    //bind
    $stmt->bind_param("s", $email);
    //execute
    $stmt->execute();
    //get results and check if there's more than one
    $result = $stmt->get_result();
    if(!$resultArray = $result->fetch_assoc()){
      echo "can't fetch_assoc() line 21";
      exit();
    }

    //if email exists in users table...
    if($result->num_rows !== 0){

      if($resultArray['email']==$email){

        /*We found a matching user, so we know they exist.
        Now we can start the password reset steps and finally go ahead and send an email with the reset link:   */

        /*First, we prepare two tokens: the selector for the primary key in the db, and the token validator as the way
        that we confirm the user is the actual authorized user. The primary key will be converted to a hex, so it's more compact.*/
        $selector = bin2hex(random_bytes(8));
        $token = random_bytes(32);
        $tokenAsValidator = bin2hex($token);

        $url = "www.abdielarias.com/createNewPassword.php?selector=" . $selector . "&validator=" . $tokenAsValidator;

        $expireTime = date("U") + 1800;

        require 'databaseConn.php';


        //delete existing reset request
        $sql = "DELETE FROM pwdReset WHERE email=?;";

        if($stmt = $conn->prepare($sql)){

          $stmt->bind_param("s", $email);
          $stmt->execute();
        }


        //Now we insert a new row into this pwdReset database with new request data

        $sql = "INSERT INTO pwdReset (email, selector, token, expireTime) VALUES (?,?,?,?);";

        //Bcrypt default hashing method for security
        $hashedToken = password_hash($token, PASSWORD_DEFAULT);

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $email, $selector, $hashedToken, $expireTime);
        $stmt->execute();
        //Now we have a new reset request record in the database
        $stmt->close();
        $conn->close();

        //Now we'll send the email to the user with the link to the createNewPassword page
        $to = $email;
        $subject = "Reset your password";

        $message = "<p>Follow the link in this email in order to reset your password.
        If you did not request a password, please ignore this message.</p>";
        $message .= '<br> Here is your password reset link: <br>
        <a href='.$url.'>'.$url.'</a>';

        $headers = "From: no-reply <abdiel@abdielarias.com>\r\n";
        $headers .= "Reply-To: abdiel@abdielarias.com\r\n";
        $headers .= "Content-type: text/html \r\n";

        mail($to, $subject, $message, $headers);

        header("Location: password-reset-request.php?resetMessage=initialized");
        exit();
      }
      else {
        //we could not find a user with that email address
        header('Location: password-reset-request.php?resetMessage=emailDoesNotExist2');
        exit();
      }

    } else {

      //we could not find a user with that email address
      header('Location: password-reset-request.php?resetMessage=emailDoesNotExist1');
      exit();
    }

  }
  else {

    header('Location: password-reset-request.php?resetMessage=emailEmpty');
    exit();
  }

}


 ?>
