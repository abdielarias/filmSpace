<?php
session_start();
$user_id = $_SESSION['id'];
require 'databaseConn.php';
$post_id = (int)$_POST['postID'];

if($post_id){

  $sql = "DELETE FROM userposts WHERE post_id=?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("i", $post_id);
  if($stmt->execute()){
    echo "success".$post_id;
  }
  else {
    echo "failure";
  }

  //Now delete the likes table entry
  $sql = "DELETE FROM likes WHERE post_id=? AND user_id=?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("ii", $post_id, $user_id);
  $stmt->execute();
}
else {

  echo "failure";
}



?>
