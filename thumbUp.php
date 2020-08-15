<?php

session_start();
include 'databaseConn.php';

//go into the database and update the likes amount for this post
if(isset($_POST['postID'])){

  $postID = $_POST['postID'];


  $sql = "UPDATE userposts SET num_likes = num_likes + 1 WHERE post_id=?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("i", $postID);
  $stmt->execute();


}

 ?>
