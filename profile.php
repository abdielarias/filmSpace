<?php

// This page is only for when login is successful
require 'header.php';
require 'databaseConn.php';

$userName = $_SESSION['userName'];
$id = $_SESSION['id'];
$imageLocation = $_SESSION['image'];

//set default profile pic if none exists. Normally, the image in the users table contains the profilePics/ folder path too.
if($imageLocation == "none" || $imageLocation == NULL){
  $imageLocation = "profilePics/defaultAvatar.png";
}
?>

<div class="profileTopPanel">
  <div class="profile-smallWrapper">
    <div class="profileImgPanel">
      <img id="profileImg" src="<?php echo $imageLocation ?>">

      <div class="uploadCaption">
          <a href="profileEdit.php"><img id="uploadIcon" src="images/uploadIcon.png"></a>
          <?php
          if(isset($_GET['message'])){
            if($_GET['message'] == "success"){
              echo '<p class="success">Profile Picture Saved.</p>';
            }
            else if($_GET['message'] == "ReviewSuccessful") {
                echo '<p class="success">Review Saved.</p>';
            }
          }
           ?>
      </div>
    </div>

    <div class="aboutPanel">

      <h1 class="profileUserName"><?php echo  $userName;?></h1>
      <br><br><br>

      <p>
        <?php
        //loop through the database users and show the user's about me info:

        $sql = "SELECT * FROM users WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        echo $row['about_me'];

         ?>
      </p>
    </div>
  </div>
</div>

<div class="profileReviewsContainer">

  <div class="profileBtnSet">
    <a class="submitButton createPostBtn" href="writeReview.php">Create New Film Review</a>
    <h2>Your recent reviews:</h2>
  </div>

  <?php

  //loop through the database userposts and show each of this users posts:
  $sql = "SELECT * FROM userposts WHERE user_id=? ORDER BY post_date DESC";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("s", $id);
  $stmt->execute();
  $result = $stmt->get_result();

  while($row = $result->fetch_assoc()){

    //setup datetime datetime objects
    $createdDate = date_create($row['post_date']);
    $formattedCreatedDate = date_format($createdDate,"g:i A - M d,Y");
    $modifiedDate = date_create($row['modified_date']);
    $formattedModifiedDate = date_format($modifiedDate,"g:i A - M d,Y");

    $privacy = $row['private'];
    $privacyString = '';
    if($privacy == "0"){
      $privacyString = '<a href="#" onclick="lockDown(event, '.$row['post_id'].', this)"><img id="privacyLockImg" src="images/lockUp.png"></a>';
    }
    else {
      $privacyString = '<a href="#" onclick="lockUp(event, '.$row['post_id'].', this)"><img id="privacyLockImg" src="images/lockDown.png"></a>';
    }

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

   ?>

</div>

<script src="thumbs.js"></script>
<script src="deletePost.js"></script>
<script src="privacyLock.js"></script>

<?php
include 'footer.php';
?>
