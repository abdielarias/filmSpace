<?php

session_start();
require 'databaseConn.php';
$userID = $_SESSION['id'];
$currentImage = $_SESSION['image'];

if(isset($_POST["uploadProfileImgBtn"])){

  //get the file and its properties, which are in an associative array. $_FILES is a 2D array. An array of associative arrays. Each file has an associative array.
  $file = $_FILES["file"];

  //get the name of the file
  $fileName = $_FILES["file"]['name'];
  $fileTmpName = $file['tmp_name'];
  $fileSize = $file['size'];
  $fileError = $file['error'];
  //were errors encountered?
  if($fileError !== 0){
    header("Location: profileEdit.php?error=fileCouldNotOpen&name=".$fileName);
    exit();
  }
  $fileType = $file['type'];

  //get the extension
  //This returns an array with the two components: "name" and "extension"
  $fileExplode = explode(".", $fileName);
  $lastElement = end($fileExplode);
  $fileExt = strtolower($lastElement);

  //Allowed extension list:
  $allowedExt = array("jpg", "jpeg", "png");

  //Is this file type allowed?
  if(in_array($fileExt, $allowedExt) == false){
    header("Location: profileEdit.php?error=fileTypeNotSupported");
    exit();
  }

  //Is this image greater than 2MB? Not allowed.
  if($fileSize > 2000000){
    header("Location: profileEdit.php?error=fileTooLarge");
    exit();
  }

  //Before we insert the new image, lets delete the old one, if its not the default one:
  if($currentImage !== "profilePics/defaultAvatar.png"){
    unlink($currentImage);
  }


  //name the new file, select the directory and move the file to it.
  $random = bin2hex(random_bytes(2));
  $profilePicName = $random ."pic-userID" . $userID . "." . $fileExt;
  $destination = "profilePics/".$profilePicName;

  move_uploaded_file($fileTmpName, $destination);

  //insert this into the users table
  $sql = 'UPDATE users SET image=? WHERE id=?';
  if(!$stmt = $conn->prepare($sql)){
    echo "could not prepare";
    exit();
  }
  $stmt->bind_param("ss", $destination, $userID);
  $stmt->execute();

//this session variable holds the full name and path of the file.
  $_SESSION['image'] = $destination;
  unset($_FILES["file"]);
  header("Location: profile.php?message=success");
}



 ?>
