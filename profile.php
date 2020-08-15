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

        <h1 class="profileUserName"><?php echo  $userName ?></h1>
        <br><br><br>

        <p>
          <?php
          //loop through the database users and show the user's aboutme info:
          $sql = "SELECT * FROM users WHERE id=?";
          $stmt = $conn->prepare($sql);
          $stmt->bind_param("s", $id);
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
  $sql = "SELECT * FROM userposts WHERE user_id=?";
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
    <a href="#" onclick="thumbUp(event, '.$row['post_id'].', this)"><img id="thumbup" src="images/thumb.png"></a> '.$row['num_likes'].'
    <a href="#" onclick="thumbDown(event, '.$row['post_id'].')"><img id="thumbdown" src="images/thumbdown.png"></a> '.$row['num_dislikes'].'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
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

  var calledElement = callingElement;
  var xhr = new XMLHttpRequest();
  var file = "thumbUp.php";

  xhr.open("POST", file, true);
  xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

  xhr.onload = function() {
    calledElement.innerHTML = '<img style="opacity: 100%;" id="thumbup" src="images/thumb.png">';
  }

  var params = "postID="+post_id;
  xhr.send(params);

  //check for error:
  xhr.onreadystatechange = function () {
    var DONE = 4; // readyState 4 means the request is done.
    var OK = 200; // status 200 is a successful return.
    if (xhr.readyState === DONE) {
      if (xhr.status === OK) {
        console.log(xhr.responseText); // 'This is the returned text.'
      } else {
        console.log('Error: ' + xhr.status); // An error occurred during the request.
      }
    }

    //get the new number of likes from php headers and update it with javascript here
  };

}

function thumbDown(event, post_id){
  event.preventDefault();


  console.log(post_id);
}

</script>

<?php
include 'footer.php';
?>
