<?php

session_start();
include 'databaseConn.php';
$user_id = (int)$_SESSION['id'];
$num_likes = 0;
$postID = (int)$_POST['postID'];

//go into the database and update the likes amount for this post
if(isset($_POST['postID'])){

  //check if a 'likes' table entry related to that post_id and user_id exist. If not, then insert a new row:
  //insert a new entry into likes table
  $sql = "SELECT isLiked, isDisliked FROM likes WHERE post_id=? AND user_id=?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("ii", $postID, $user_id);
  $stmt->execute();
  $results = $stmt->get_result();
  $row = $results->fetch_assoc();
  
//if we have voted before:
  if($row){
    //if it was previously disliked, update the likes table, and add 1 to the likes on the userpost entry and decrement the dislikes:
    if($row['isDisliked'] == 1){

      //update the existing entry in likes table
      $sql = "UPDATE likes SET isLiked = 1, isDisliked = 0 WHERE post_id=? AND user_id=?";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("ii", $postID, $user_id);
      $stmt->execute();

      //update userposts likes and dislikes counters
      $sql = "UPDATE userposts SET num_likes = num_likes + 1, num_dislikes = num_dislikes - 1 WHERE post_id=?";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("i", $postID);
      $stmt->execute();
    }
    else if($row['isLiked'] == 1){
      //Do nothing, all is already set correctly.
    }

    //fetch the new numlikes:
    $sql = "SELECT num_likes FROM userposts WHERE post_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $postID);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $num_likes = $row['num_likes'];
  }
  else {
    //insert a new entry into likes table
    $sql = "INSERT INTO likes (post_id, user_id, isLiked, isDisliked) VALUES (?,?,?,?)";
    $stmt = $conn->prepare($sql);
    $one = 1;
    $zero = 0;
    $stmt->bind_param("iiii", $postID, $user_id, $one, $zero);
    $stmt->execute();

    $sql = "UPDATE userposts SET num_likes = num_likes + 1 WHERE post_id=?";
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
  }

  //pass javascript the new number of likes
  echo $num_likes;
}
