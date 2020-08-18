<?php

session_start();

if(isset($_POST['post_id']) && isset($_SESSION['id'])){

  require 'databaseConn.php';
  $user_id = $_SESSION['id'];
  $post_id = (int)$_POST['post_id'];

  $sql = "UPDATE userposts SET private = 1 WHERE post_id=? AND user_id=?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("ii", $post_id, $user_id);

  if($stmt->execute()){
    echo "success";
  } else {

    echo "failure";
  }

} else {

  echo "not_signed_in";
}






 ?>
