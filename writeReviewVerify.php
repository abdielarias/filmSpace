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
$movie_id = $_POST['movie_id'];

if(isset($_POST['isPrivate'])){
  $isPrivate = $_POST['isPrivate'];
}

if(!isset($_POST['submitReviewBtn'])){
  header("Location: writeReview.php?error=notSubmitted&title=$filmTitle&content=$content&movieID=$movie_id");
  exit();
}

if(empty($_POST['content'])){
  header("Location: writeReview.php?error=emptyContent&title=$filmTitle&content=$content&movieID=$movie_id");
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
  $sql = "UPDATE userposts SET modified_date=?, movie_id=?, content=?, private=? WHERE user_id=? AND post_id=?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("ssssss", $currentDate, $movie_id, $content, $privacyCheck, $userID, $post_id);
  $stmt->execute();
  header("Location: profile.php?message=reviewUpdated");
}
else {

  //save to database:
  $sql = "INSERT INTO userposts (user_id, username, post_date, modified_date, movie_id, content, private) VALUES (?,?,?,?,?,?,?)";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("sssssss", $userID, $userName, $currentDate, $currentDate, $movie_id, $content, $privacyCheck);
  $stmt->execute();
  header("Location: profile.php?message=newReviewCompleted");
}





 ?>
