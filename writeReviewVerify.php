<?php
//pure backend file
session_start();
require 'databaseConn.php';

$filmTitle = $_POST['filmTitle'];
$content = $_POST['content'];
$userID =  $_SESSION['id'];
$userName = $_SESSION['userName'];
$currentDate = $_POST['date'];
$isPrivate = "off";
$post_id = $_POST['post_id'];

if(isset($_POST['isPrivate'])){
  $isPrivate = $_POST['isPrivate'];
}

if(!isset($_POST['submitReviewBtn'])){
  header("Location: writeReview.php?error=notSubmitted&title=$filmTitle&content=$content");
  exit();
}

if(empty($_POST['filmTitle'])){
  header("Location: writeReview.php?error=emptyTitle&title=$filmTitle&content=$content");
  exit();
}

if(empty($_POST['content'])){
  header("Location: writeReview.php?error=emptyContent&title=$filmTitle&content=$content");
  exit();
}

$privacyCheck = "0";
if($isPrivate == "on"){
  $privacyCheck = "1";
}

//if this is an existing post:
if($post_id != "new"){

  //update
  //save to database:
  $sql = "UPDATE userposts SET modified_date=?, subject=?, content=?, private=? WHERE user_id=? AND post_id=?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("ssssss", $currentDate, $filmTitle, $content, $privacyCheck, $userID, $post_id);
  $stmt->execute();
  header("Location: profile.php?message=reviewUpdated");
}
else {

  //save to database:
  $sql = "INSERT INTO userposts (user_id, username, post_date, modified_date, subject, content, private) VALUES (?,?,?,?,?,?,?)";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("sssssss", $userID, $userName, $currentDate, $currentDate, $filmTitle, $content, $privacyCheck);
  $stmt->execute();
  header("Location: profile.php?message=newReviewCompleted");
}





 ?>
