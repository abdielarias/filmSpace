<?php

$firstName= $_POST['firstName'];
$lastName = $_POST['lastName'];
$email = $_POST['email'];
$userName = $_POST['userName'];
$password = $_POST['password'];
$passwordConfirm = $_POST['passwordConfirm'];

$refillFields = array($firstName, $lastName, $email, $userName);

if(isset($_POST['submitButton'])){

//Check for any empty fields. If any are empty, redirect to createAccount page.
  if(empty($firstName) || empty($lastName) || empty($email) ||  empty($userName) || empty($password) || empty($passwordConfirm)){

    redirectToCreateAccount($refillFields, "emptyfields");
    exit();
  }
  else {

//Now that we know that all fields are filled in, we must verify the validity of each field.

    //check if the email is a valid email
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
      redirectToCreateAccount($refillFields, "invalidEmail");
      exit();
    }

    //check if the password is within limits
    if(!preg_match("/^[a-zA-Z0-9]*$/",$password)){
      redirectToCreateAccount($refillFields, "passwordPatternFail");
      exit();
    }

    //check if password matches the password Confirm
    if($password!==$passwordConfirm){
      redirectToCreateAccount($refillFields, "passwordsDoNotMatch");
      exit();
    }

//---------------------------------------------------------------------------
//connect to database and check if username already exists
    require 'databaseConn.php';

    //create prepared statement
    $sql = "SELECT * FROM users where username=?;";

    if(!$stmt = $conn->prepare($sql)){
      echo "ERROR: ".$conn->error;
      exit();
    }

    //bind parameters. The two arguments are "s" for string and then the variable storing the user's data input.
    if(!$stmt->bind_param("s", $userName)){
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

    if($result->num_rows>0){
      redirectToCreateAccount($refillFields, "usernameTaken");
      exit();
    }
//---------------------------------------------------------------------------
//check if email already taken:

    //create prepared statement
    $sql = "SELECT * FROM users where email=?;";

    if(!$stmt = $conn->prepare($sql)){
      echo "ERROR: ".$conn->error;
      exit();
    }

    //bind parameters. The two arguments are "s" for string and then the variable storing the user's data input.
    if(!$stmt->bind_param("s", $email)){
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

    if($result->num_rows>0){
      redirectToCreateAccount($refillFields, "emailTaken");
      exit();
    }
//---------------------------------------------------------------------------
//If we got this, far we are good to create the new account and insert this information into the database
//create prepared statement
    $sql = "INSERT INTO users (username, email, firstName, lastName, password, image) VALUES (?,?,?,?,?,?);";
    $image = "none";

    if(!$stmt = $conn->prepare($sql)){
      echo "ERROR: ".$conn->error;
      exit();
    }

//bind parameters. The two arguments are "s" for string and then the variable storing the user's data input.

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    if(!$stmt->bind_param("ssssss", $userName, $email, $firstName, $lastName, $hashedPassword, $image)){
      echo "mysql binding failed at insert stmt";
      exit();
    }

    //execute
    if(!$stmt->execute()){
      echo "mysql statement execution failed at insert stmt";
      exit();
    }

//---------------------------------------------------------------------------
    //Account created, send them to their profile page.
    $stmt->close();
    $conn->close();

    session_start();
    $_SESSION['isLogged'] = true;
    $_SESSION['id'] = $resultArray['id'];
    $_SESSION['userName'] = $userName;
    $_SESSION['firstName'] = $firstName;
    $_SESSION['lastName'] = $lastName;
    $_SESSION['email'] = $email;
    $_SESSION['image'] = $image;
    header("Location: profile.php");
    exit();
  }

}
else {
  header("Location: createAccount.php");
  exit();
}


//A function for redirecting them back to the createAccount page with an error message and the fields that were already fiilled.
function redirectToCreateAccount($fields, $errorMessage){

  $refillFields = $fields;

  $getFields = "?";
  //IF they are already filled out but one of them is empty send back firstName, lastName, email, and username and error message
  if(!empty($refillFields[0])){
    $getFields .= "firstName=".$refillFields[0]."&";
  }
  if(!empty($refillFields[1])){
    $getFields .= "lastName=".$refillFields[1]."&";
  }
  if(!empty($refillFields[2])){
    $getFields .= "email=".$refillFields[2]."&";
  }
  if(!empty($refillFields[3])){
    $getFields .= "userName=".$refillFields[3]."&";
  }

  header("Location: createAccount.php".$getFields."error=".$errorMessage);
  exit();
}



 ?>
