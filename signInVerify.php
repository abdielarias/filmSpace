<?php

$emailUsername = $_POST['emailUsername'];
$password = $_POST['password'];

//If Submit was pressed, we do some error checking, connect to db, verify that the account exists, and then send them to the profile page: profile.php
if(isset($_POST['submitButton'])){

  //check for empty fields, if so redirect
  if(empty($emailUsername) || empty($password)){

    if(!empty($emailUsername)){
      header("Location: signin.php?error=emptyfields&emailUsername=".$emailUsername);
    }
    else{
      header("Location: signin.php?error=emptyfields");
    }
  }
  else{

    //We don't know if user will type an email or a username. Let's just take it and search for it as either email OR username from database
    require 'databaseConn.php';

    //create prepared statement
    $sql = "SELECT * FROM users where username=? OR email=?;";

    if(!$stmt = $conn->prepare($sql)){
      echo "ERROR: ".$conn->error;
      exit();
    }

    //bind parameters. The two arguments are "s" for string and then the variable storing the user's data input.
    if(!$stmt->bind_param("ss", $emailUsername, $emailUsername)){
      echo "mysql binding failed";
      exit();
    }

    //execute
    if(!$stmt->execute()){
      echo "mysql statement execution failed";
      exit();
    }

    //print results
    //mysqli_result object gets returned
    $result = $stmt->get_result();
    //turn mysqli_ result object into associative array
    $resultArray = $result->fetch_assoc();

    if($result->num_rows==0){
      header("Location: signin.php?error=invalidUsername");
      exit();
    }

    $passwordVerify = password_verify($password, $resultArray['password']);
    if($passwordVerify==false){
      header("Location: signin.php?error=invalidPassword&emailUsername=$emailUsername");
      exit();
    }

    //We were able to verify username/email and password so now...
    $stmt->close();
    $conn->close();

    session_start();
    $_SESSION['isLogged'] = true;
    $_SESSION['id'] = $resultArray['id'];
    $_SESSION['userName'] = $resultArray['username'];
    $_SESSION['firstName'] = $firstName;
    $_SESSION['lastName'] = $lastName;
    $_SESSION['email'] = $email;
    $_SESSION['image'] = $resultArray['image'];

    header("Location: profile.php");
    exit();
  }
}
else {

  header("Location: signin.php");
  exit();
}
?>
