<?php
include 'header.php';
include 'databaseConn.php';

echo '
      <div class="recentReviewsTimeline">
      <h2 id="recentReviewsLabel">Recent Reviews Timeline</h2>
      ';

//loop through the database userposts and show each of this users posts:
$result = $conn->query("SELECT * FROM userposts WHERE private=0 ORDER BY post_date DESC");

while($row = $result->fetch_assoc()){

  //setup datetime datetime objects
  $createdDate = date_create($row['post_date']);
  $formattedCreatedDate = date_format($createdDate,"H:m A - M d, Y");
  $modifiedDate = date_create($row['modified_date']);
  $formattedModifiedDate = date_format($modifiedDate,"H:m A - M d, Y");

  //fetch the particular user's profile image
  $userResult = $conn->query("SELECT * FROM users WHERE id=".$row['user_id']);
  $userRow = $userResult->fetch_assoc();
  $imageLocation = $userRow['image'];

  echo
  '
  <div class="postedReviewOnProfile">
    <div class="profileUsersPostImg"><img src='.$imageLocation.'></div>
    <span id="uName">'.$row['username'].'</span>

    <div id="reviewPostContent">
      <p>Review of: '.$row['subject'].'</p>
      <p>'.$row['content'].'</p>
    </div>

    <a href="#" onclick="thumbUp(event)"><img id="thumbup" src="images/thumb.png"></a> '.$row['num_likes'].'
    <a href="#" onclick="thumbDown(event)"><img id="thumbdown" src="images/thumbdown.png"></a> '.$row['num_dislikes'].'
    &nbsp;&nbsp;&nbsp;&nbsp;
    <span class="timeOnPostedReview"> created: '.$formattedCreatedDate.'&nbsp;&nbsp;&nbsp;&nbsp; last modified: '.$formattedModifiedDate.'</span>
  </div>
  ';
}

echo '</div>';


include 'footer.php';
?>
