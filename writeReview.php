<?php

// This page is only for when login is successful
include 'header.php';
require 'databaseConn.php';

if(!isset($_SESSION['id'])){
  header("Location: index.php?error=accessDenied");
  exit();
}

$userName = $_SESSION['userName'];
$user_id = $_SESSION['id'];
$imageLocation = $_SESSION['image'];
$postExists = false;

//if this is set, then we know that we are editing an existing post
if(isset($_GET['post_id'])){
  $post_id = $_GET['post_id'];
  $postExists = true;

  //go into the database and pull some details for autofill
  $sql = "SELECT * FROM userposts WHERE user_id=? and post_id=?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("ii", $user_id, $post_id);
  $stmt->execute();
  $results = $stmt->get_result();
  $row = $results->fetch_assoc();
}


?>


<div class="reviewFormContainer">
  <div class="reviewWrapper">
    <h3>Review a Film:</h3>
    <form action="writeReviewVerify.php" method="POST">
      <input type="hidden" id="date" name="date" value="">


      <?php
      if($postExists){
        echo '<input type="hidden" id="post_id" name="post_id" value="'.$row['post_id'].'">';
        echo '<input autofocus id="filmTitleInput" type="text" name="filmTitle" value="'.$row['subject'].'">';
        echo '<br>';
        echo '<textarea id="writingTextArea" name="content">'.$row['content'].'</textarea>';
        if($row['private'] == 1){
          echo '<br>';
          echo '<label for="isPrivate" id="privacyCheckBoxLabel">This post is private. Unselect to make public: </label>';
          echo '<input type="checkbox" name="isPrivate" id="privacyCheckbox" checked>';
        } else {
          echo '<br>';
          echo '<label for="isPrivate" id="privacyCheckBoxLabel">This message will post publicly. Select to keep private: </label>';
          echo '<input type="checkbox" name="isPrivate" id="privacyCheckbox">';
        }

      } else{
        echo '<input type="hidden" id="post_id" name="post_id" value="new">';
        echo '<input autofocus id="filmTitleInput" type="text" name="filmTitle" placeholder="Write the title of the film...">';
        echo '<br>';
        echo '<textarea id="writingTextArea" placeholder="Write a review here..." name="content"></textarea>';
        echo '<br>';
        echo '<label for="isPrivate" id="privacyCheckBoxLabel">This message will post publicly. Select to keep private: </label>';
        echo '<input type="checkbox" name="isPrivate" id="privacyCheckbox">';
      }
       ?>


      <br>
      <div class="buttonCenter">
        <a href="profile.php" class="submitButton">Previous</a>
        <input type="submit" class="submitButton postBtn" href="writeReview.php" name="submitReviewBtn" value="Submit Post">
      </div>
    </form>

    <?php
    if(isset($_GET['message'])){
      if($_GET['message'] == "success"){
        echo "<p class='success'>Message Saved!</p>";
      }
    }
     ?>
   </div>
</div>



<script>
  let datetime = new Date();

  let year = datetime.getFullYear();
  let month = datetime.getMonth() + 1;
  month.toString();
  if(month<10){
    month = "0"+month;
  }
  let dayOfMonth = datetime.getDate();
  dayOfMonth.toString();
  if(dayOfMonth<10){
    dayOfMonth = "0"+dayOfMonth;
  }

  let hour = datetime.getHours();
  hour.toString();
  if(hour<10){
    hour = "0"+hour;
  }

  let minutes = datetime.getMinutes();
  minutes.toString();
  if(minutes<10){
    minutes = "0"+minutes;
  }

  let seconds = datetime.getSeconds();
  seconds.toString();
  if(seconds<10){
    seconds = "0"+seconds;
  }

  let dateTimeFormat = year + "-" + month + "-" + dayOfMonth + " " + hour + "-"+ minutes + "-" + seconds;

  document.querySelector("#date").setAttribute("value", dateTimeFormat);

</script>

<?php
include 'footer.php';
?>
