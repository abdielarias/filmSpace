<?php
// This page is only for when login is successful
require 'header.php';
require 'databaseConn.php';

if(!isset($_SESSION['userName'])){
  header("Location: index.php?error=accessDenied");
  exit();
}

$userName = $_SESSION['userName'];
$id = $_SESSION['id'];
$imageLocation = $_SESSION['image'];

//set default profile pic if none exists. Normally, the image in the users table contains the profilePics/ folder path too.
if($imageLocation == "none" || $imageLocation == NULL){
  $imageLocation = "profilePics/defaultAvatar.png";
}
?>



<div class="profileTopPanel">

  <div class="profileImgPanel">
    <a href="profileEdit.php"><img id="profileImg" src="<?php echo $imageLocation ?>"></a>

    <div class="uploadCaption">

        <h1 class="profileUserName"><?php echo  $userName;?></h1>
        <p id="picToolTip">Click to change profile pic.</p>

        <script>
          var img = document.querySelector("#profileImg");
          var tooltip = document.querySelector("#picToolTip");

          img.addEventListener("mouseover", ()=>{
            console.log("hovered");
              tooltip.style=`visibility: visible;`;
          });

          img.addEventListener("mouseout", ()=>{
            console.log("hovered");
              tooltip.style=`visibility: hidden;`;
          });

        </script>


        <?php
        if(isset($_GET['message'])){
          if($_GET['message'] == "success"){
            echo '<p class="success">Profile Picture Saved.</p>';
          }
          else if($_GET['message'] == "ReviewSuccessful") {
              echo '<p class="success">Review Saved.</p>';
          }
          else if($_GET['message'] == "newReviewCompleted") {
              echo '<p class="success">Review Saved.</p>';
          }
        }
         ?>
    </div>
  </div>


</div>

<script src = "./javascript/fetchMovieInfo.js"></script>
<br>
<div class="yourReviewsBanner">
  <h2>Your Film Reviews</h2>
</div>

<div class="profileReviewsContainer">

  <!-- <div class="profileBtnSet">

    <h2>Your recent reviews:</h2>
  </div> -->

  <?php

  //loop through the database userposts and show each of this users posts:
  $sql = "SELECT * FROM userposts WHERE user_id=? ORDER BY modified_date DESC";
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

    $opacity100 = 'opacityForce100';
    $hoverOpacity = 'hoverOpacity';
    $likesOpacity = $hoverOpacity;
    $dislikesOpacity = $hoverOpacity;

    //initial loading checks to see what opacity the thumbs need
    if(isset($likesRow['isLiked'])){
      if($likesRow['isLiked'] == 1){
        $likesOpacity = $opacity100;
      }
    }

    if(isset($likesRow['isDisliked'])){
      if($likesRow['isDisliked'] == 1){
        $dislikesOpacity = $opacity100;
      }
    }

    echo
    '
    <div class="postedReviewOnProfile">

    <div style="overflow:auto;">
      <div class="profileUsersPostImg"><img src='.$imageLocation.'></div>
      <span id="uName">'.$row['username'].'</span>
      <span id="deleteIconSpan"><a href="#" onclick="deletePost(event, '.$row['post_id'].', this)"><img src="images/deleteIcon.png"></a></span>
      <span id="editPostIconSpan"><a href="writeReview.php?post_id='.$row['post_id'].'&movieID='.$row['movie_id'].'"><img src="images/pencil.png"></a></span>
    </div>

    <div class="reviewPostContent contentOf'.$row['post_id'].'">
      <script>getMovie('.$row['movie_id'].', '.$row['post_id'].' );</script>
      <div class="reviewText">'.$row['content'].'</div>
    </div>

    &nbsp;
    <a class="'.$likesOpacity.'" href="#" onclick="thumbUp(event, '.$row['post_id'].', this)"><img id="thumbup" src="images/thumb.png"></a><span class="thumbUpCount">'.$row['num_likes'].'</span>
    &nbsp;&nbsp;&nbsp;&nbsp; <a class="'.$dislikesOpacity.'" href="#" onclick="thumbDown(event, '.$row['post_id'].', this)"><img id="thumbdown" src="images/thumbdown.png"></a><span class="thumbDownCount">'.$row['num_dislikes'].'</span>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    '.$privacyString.'
    &nbsp;&nbsp;&nbsp;&nbsp;
    <div class="timeOnPostedReview">modified: '.$formattedModifiedDate.'<br>created: '.$formattedCreatedDate.'</div>


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
