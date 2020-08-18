<?php

session_start();
$user_id = $_SESSION['id'];
require 'databaseConn.php';
$post_id = (int)$_POST['postID'];

if($post_id){

  $sql = "UPDATE userposts SET privacy = 0 WHERE post_id=? AND user_id=?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("ii", $post_id, $user_id);

  if($stmt->execute()){
    echo "success";
  } else {

    echo "failure";
  }
  
} else {

  echo "failure";
}






 ?>
