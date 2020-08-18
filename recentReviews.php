<?php
require 'header.php';
require 'databaseConn.php';

$id = $_SESSION['id'];

echo '
      <div class="recentReviewsTimeline">
      <h2 id="recentReviewsLabel">Recent Reviews</h2>
      ';

//loop through the database userposts and show each of this users posts:
$result = $conn->query("SELECT * FROM userposts WHERE private=0 ORDER BY post_date DESC");

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
  $sql2 = "SELECT * FROM likes WHERE post_id=? and user_id=?";
  $stmt2 = $conn->prepare($sql2);
  $stmt2->bind_param("ii", $row['post_id'], $id);
  $stmt2->execute();
  $likesResult = $stmt2->get_result();
  $likesRow = $likesResult->fetch_assoc();

  $opacity100 = 'style="opacity: 100%;"';
  $opacity50 = 'style="opacity: 50%;"';
  $likesOpacity = '';
  $dislikesOpacity = '';


  //initial loading checks to see what opacity the thumbs need
  if(isset($likesRow['isLiked'])){
    if($likesRow['isLiked'] == 1)
      $likesOpacity = $opacity100;
  }else{
    $likesOpacity = $opacity50;
  }

  if(isset($likesRow['isDisliked'])){
    if($likesRow['isDisliked'] == 1)
      $dislikesOpacity = $opacity100;
  }else{
    $dislikesOpacity = $opacity50;
  }

  if($row['user_id'] != $_SESSION['id']){

    echo
    '
    <div class="postedReviewOnProfile">
      <div class="profileUsersPostImg"><img src='.$imageLocation.'></div>
      <span id="uName">'.$row['username'].'</span>

      <div id="reviewPostContent">
        <p>Review of: '.$row['subject'].'</p>
        <p>'.$row['content'].'</p>
      </div>

      <a href="#" onclick="thumbUp(event, '.$row['post_id'].', this)"><img id="thumbup" '.$likesOpacity.' src="images/thumb.png"></a><span class="thumbUpCount">'.$row['num_likes'].'</span>
      <a href="#" onclick="thumbDown(event, '.$row['post_id'].', this)"><img id="thumbdown" '.$dislikesOpacity.' src="images/thumbdown.png"></a><span class="thumbDownCount">'.$row['num_dislikes'].'</span>
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

      &nbsp;&nbsp;&nbsp;&nbsp;
      <span class="timeOnPostedReview"> created: '.$formattedCreatedDate.'&nbsp;&nbsp;&nbsp;&nbsp; last modified: '.$formattedModifiedDate.'</span>
    </div>
    ';
  }
  else{
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
    <span id="editPostIconSpan"><a href="#" onclick="editPost(event, '.$row['post_id'].', this)"><img src="images/pencil.png"></a></span>

    <div id="reviewPostContent">
      <p>Review of: '.$row['subject'].'</p>
      <p>'.$row['content'].'</p>
    </div>

    &nbsp;
    <a href="#" onclick="thumbUp(event, '.$row['post_id'].', this)"><img id="thumbup" '.$likesOpacity.' src="images/thumb.png"></a><span class="thumbUpCount">'.$row['num_likes'].'</span>
    <a href="#" onclick="thumbDown(event, '.$row['post_id'].', this)"><img id="thumbdown" '.$dislikesOpacity.' src="images/thumbdown.png"></a><span class="thumbDownCount">'.$row['num_dislikes'].'</span>
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
