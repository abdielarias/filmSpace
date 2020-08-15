<?php

session_start();
include 'databaseConn.php';
$user_id = (int)$_SESSION['id'];

//go into the database and update the likes amount for this post
if(isset($_POST['postID'])){

  $postID = (int)$_POST['postID'];
  $sql = "UPDATE userposts SET num_dislikes = num_dislikes + 1 WHERE post_id=?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("i", $postID);
  $stmt->execute();

  //fetch the new num_likes
  $sql = "SELECT num_likes FROM userposts WHERE post_id=?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("i", $postID);
  $stmt->execute();
  $result = $stmt->get_result();
  $row = $result->fetch_assoc();
  $num_likes = $row['num_likes'];

  //update the likes table:

  //check if a 'likes' table entry related to that post_id and user_id exist. If not, then insert a new row:
  //insert a new entry into likes table
  $sql = "SELECT isLiked AND isDisliked FROM likes WHERE post_id=? AND user_id=?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("ii", $postID, $user_id);
  $stmt->execute();
  $results = $stmt->get_result();
  $row = $results->fetch_assoc();

  if($row){
    //update the existing entry
    $sql = "UPDATE likes SET isLiked = 1, isDisliked = 0 WHERE post_id=? AND user_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $postID, $user_id);
    $stmt->execute();
  }
  else {
    //insert a new entry into likes table
    $sql = "INSERT INTO likes (post_id, user_id, isLiked, isDisliked) VALUES (?,?,?,?)";
    $stmt = $conn->prepare($sql);
    $one = 1;
    $zero = 0;
    $stmt->bind_param("iiii", $postID, $user_id, $one, $zero);
    $stmt->execute();
  }

  //pass javascript the new number of likes
  echo $num_likes;
}
