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
    $formattedCreatedDate = date_format($createdDate,"H:m A - M d,Y");
    $modifiedDate = date_create($row['modified_date']);
    $formattedModifiedDate = date_format($modifiedDate,"H:m A - M d,Y");

    $privacy = $row['private'];
    $privacyString = '';
    if($privacy == "0"){
      $privacyString = '<a href=""><img id="privacyLockImg" src="images/lockUp.png"></a>';
    }
    else {
      $privacyString = '<a href=""><img id="privacyLockImg" src="images/lockDown.png"></a>';
    }

    echo
    '
    <div class="postedReviewOnProfile">

    <div class="profileUsersPostImg"><img src='.$imageLocation.'></div>
    <span id="uName">'.$row['username'].'</span>
    <span id="deleteIconSpan"><a href=""><img src="images/deleteIcon.png"></a></span>
    <span id="editPostIconSpan"><a href=""><img src="images/pencil.png"></a></span>

    <div id="reviewPostContent">
      <p>Review of: '.$row['subject'].'</p>
      <p>'.$row['content'].'</p>
    </div>

    &nbsp;
    <a href="#" onclick="thumbUp(event, '.$row['post_id'].', this)"><img id="thumbup" src="images/thumb.png"></a><span class="thumbUpCount">'.$row['num_likes'].'</span>
    <a href="#" onclick="thumbDown(event, '.$row['post_id'].', this)"><img id="thumbdown" src="images/thumbdown.png"></a><span class="thumbDownCount">'.$row['num_dislikes'].'</span>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    '.$privacyString.'
    &nbsp;&nbsp;&nbsp;&nbsp;
    <span class="timeOnPostedReview"> created: '.$formattedCreatedDate.'&nbsp;&nbsp;&nbsp;&nbsp; last modified: '.$formattedModifiedDate.'</span>


    </div>
    ';
  }

   ?>

</div>

<script>

//post_id is an integer
function thumbUp(event, post_id, callingElement){
  event.preventDefault();


  var xhr = new XMLHttpRequest();

  xhr.open("POST", "thumbUp.php", true);
  xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

  xhr.onload = function() {
    callingElement.innerHTML = '<img style="opacity: 100%;" id="thumbup" src="images/thumb.png">';
  }

  var params = "postID="+post_id;
  xhr.send(params);
  var num_likes = '';
  var response = '';

  //check for error:
  xhr.onreadystatechange = function () {
    var done = 4;
    var ok = 200;
    if (xhr.readyState === done) {
      if (xhr.status === ok) {

        //All actions expected 'after' the arrival of the http request must be here.
        //Code after this block may occur out of sync and prior to the arrival of the http response.

        response = xhr.responseText.split("-");
        num_dislikes = response[0];
        num_likes = response[1];


        console.log("likes: "+num_likes);
        console.log("dislikes: "+num_dislikes);
        
        var likeSpanElement = callingElement.nextSibling;
        likeSpanElement.innerHTML = num_likes;


        //change the other thumb button's opacity:
        var dislikeButton = callingElement.nextSibling.nextSibling.nextSibling;
        dislikeButton.innerHTML = '<img style="opacity: 50%;" id="thumbdown" src="images/thumbDown.png">';

        var dislikeCountSpan = callingElement.nextSibling.nextSibling.nextSibling.nextSibling;
        dislikeCountSpan.innerHTML = num_dislikes;


      } else {
        console.log('xhr error: ' + xhr.status);
      }
    }
  }



}

function thumbDown(event, post_id, callingElement){
  event.preventDefault();

  var xhr = new XMLHttpRequest();

  xhr.open("POST", "thumbDown.php", true);
  xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

  xhr.onload = function() {
    callingElement.innerHTML = '<img style="opacity: 100%;" id="thumbdown" src="images/thumbDown.png">';
  }

  var params = "postID="+post_id;
  xhr.send(params);
  var num_dislikes = '';
  var num_likes = '';
  var response;

  //check for error:
  xhr.onreadystatechange = function () {
  var done = 4;
  var ok = 200;
    if (xhr.readyState === done) {
      if (xhr.status === ok) {

        //All actions expected 'after' the arrival of the http request must be here.
        //Code after this block may occur out of sync and prior to the arrival of the http response.

        //update the span element showing number of dislikes

        response = xhr.responseText.split("-");
        num_dislikes = response[0];
        num_likes = response[1];

        console.log("likes: "+num_likes);
        console.log("dislikes: "+num_dislikes);
        var dislikeSpanElement = callingElement.nextSibling;
        dislikeSpanElement.innerHTML = num_dislikes;


        //change the other thumb button's opacity:
        var likeButton = callingElement.previousSibling.previousSibling.previousSibling;
        likeButton.innerHTML = '<img style="opacity: 50%;" id="thumbup" src="images/thumb.png">';

        var likeCountSpan = callingElement.previousSibling.previousSibling;
        likeCountSpan.innerHTML = num_likes;


      } else {
        console.log('xhr error: ' + xhr.status);
      }
    }
  }
}

</script>

<?php
include 'footer.php';
?>
