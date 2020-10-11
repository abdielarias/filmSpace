
<?php
require 'header.php';
require 'databaseConn.php';

?>

<!-- this loads a function called getMovie() -->
<script src = "./javascript/fetchMovieInfo.js"></script>

<?php

if(isset($_SESSION['id'])){
  $id = $_SESSION['id'];
} else $id = null;

echo '
      <div id="recentReviewsBanner">
        <h2 id="recentReviewsLabel">Recent Reviews</h2>
      </div>

      <div class="recentReviewsTimeline">
      ';

//loop through the database userposts and show each of this users posts:
$result = $conn->query("SELECT * FROM userposts WHERE private=0 ORDER BY modified_date DESC");

while($row = $result->fetch_assoc()){

  //setup datetime datetime objects
  $createdDate = date_create($row['post_date']);
  $formattedCreatedDate = date_format($createdDate,"g:i A - M d, Y");
  $modifiedDate = date_create($row['modified_date']);
  $formattedModifiedDate = date_format($modifiedDate,"g:i A - M d, Y");

  //fetch the particular user's profile image
  $userResult = $conn->query("SELECT * FROM users WHERE id=".$row['user_id']);
  $userRow = $userResult->fetch_assoc();
  $imageLocation = $userRow['image'];

  //go into the likes table and find out if this post was liked or disliked. Change opacity of thumbs based on it:
  $opacity100 = 'opacityForce100';
  $hoverOpacity = 'hoverOpacity';
  $likesOpacity = $hoverOpacity;
  $dislikesOpacity = $hoverOpacity;

  //If there is a registered user signed in:
  if($id != null){

    $sql2 = "SELECT * FROM likes WHERE post_id=? and user_id=?";
    $stmt2 = $conn->prepare($sql2);
    $stmt2->bind_param("ii", $row['post_id'], $id);
    $stmt2->execute();
    $likesResult = $stmt2->get_result();
    $likesRow = $likesResult->fetch_assoc();

    //initial loading checks to see what opacity the thumbs need
    if(isset($likesRow['isLiked'])){
      if($likesRow['isLiked'] == 1)
        $likesOpacity = $opacity100;
    }

    if(isset($likesRow['isDisliked'])){
      if($likesRow['isDisliked'] == 1)
        $dislikesOpacity = $opacity100;
    }
  }

  //if this post does not belong to the current user:
  if($row['user_id'] != $id){

    echo
    '
    <div class="postedReviewOnProfile">
      <div class="profileUsersPostImg"><img src='.$imageLocation.'></div>
      <span id="uName">'.$row['username'].'</span>

      <div class="reviewPostContent contentOf'.$row['post_id'].'">
        <script>getMovie('.$row['movie_id'].', '.$row['post_id'].' );</script>
        <span>'.$row['content'].'</span>
      </div>

      <a class="'.$likesOpacity.'" href="#" onclick="thumbUp(event, '.$row['post_id'].', this)"><img id="thumbup" src="images/thumb.png"></a><span class="thumbUpCount">'.$row['num_likes'].'</span>
      &nbsp;&nbsp;&nbsp;&nbsp; <a class="'.$dislikesOpacity.'" href="#" onclick="thumbDown(event, '.$row['post_id'].', this)"><img id="thumbdown" src="images/thumbdown.png"></a><span class="thumbDownCount">'.$row['num_dislikes'].'</span>
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

      &nbsp;&nbsp;&nbsp;&nbsp;
      <span class="timeOnPostedReview"> created: '.$formattedCreatedDate.'&nbsp;&nbsp;&nbsp;&nbsp; last modified: '.$formattedModifiedDate.'</span>
    </div>
    ';
  }
  else{

    //If this post was made by the current user:
    $privacy = $row['private'];
    $privacyString = '';
    if($privacy == "0"){
      $privacyString = '<a href="#" onclick="lockDown(event, '.$row['post_id'].', this)"><img id="privacyLockImg" src="images/lockUp.png"></a>';
    }
    else {
      $privacyString = '<a href="#" onclick="lockUp(event, '.$row['post_id'].', this)"><img id="privacyLockImg" src="images/lockDown.png"></a>';
    }


    echo
    '
    <div class="postedReviewOnProfile">

    <div class="profileUsersPostImg"><img src='.$imageLocation.'></div>
    <span id="uName">'.$row['username'].'</span>
    <span id="deleteIconSpan"><a href="#" onclick="deletePost(event, '.$row['post_id'].', this)"><img src="images/deleteIcon.png"></a></span>
    <span id="editPostIconSpan"><a href="writeReview.php?post_id='.$row['post_id'].'&movieID='.$row['movie_id'].'"><img src="images/pencil.png"></a></span>

    <div class="reviewPostContent contentOf'.$row['post_id'].'">
      <script>getMovie('.$row['movie_id'].', '.$row['post_id'].' );</script>
      <span>'.$row['content'].'</span>
    </div>

    &nbsp;
    <a class="'.$likesOpacity.'" href="#" onclick="thumbUp(event, '.$row['post_id'].', this)"><img id="thumbup" src="images/thumb.png"></a><span class="thumbUpCount">'.$row['num_likes'].'</span>
    &nbsp;&nbsp;&nbsp;&nbsp; <a class="'.$dislikesOpacity.'" href="#" onclick="thumbDown(event, '.$row['post_id'].', this)"><img id="thumbdown" src="images/thumbdown.png"></a><span class="thumbDownCount">'.$row['num_dislikes'].'</span>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    '.$privacyString.'
    &nbsp;&nbsp;&nbsp;&nbsp;
    <span class="timeOnPostedReview"> created: '.$formattedCreatedDate.'&nbsp;&nbsp;&nbsp;&nbsp; last modified: '.$formattedModifiedDate.'</span>


    </div>
    ';

  }

}

echo '</div>';



include 'footer.php';
?>

<script src="thumbs.js"></script>
<script src="deletePost.js"></script>
<script src="privacyLock.js"></script>
