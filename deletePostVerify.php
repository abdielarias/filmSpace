<?php

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
}
else {

  echo "failure";
}



?>
