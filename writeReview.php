<?php

// This page is only for when login is successful
include 'header.php';

$userName = $_SESSION['userName'];
$id = $_SESSION['id'];
$imageLocation = $_SESSION['image'];

?>

<div class="writeReviewTopBanner">

</div>

<div class="profileContainer">
  <div class="reviewFormContainer">
    <h5>Review a Film:</h5>
    <form action="writeReviewVerify.php" method="POST">
      <input type="hidden" id="date" name="date" value="">
      <input autofocus id="filmTitleInput" type="text" name="filmTitle" placeholder="Write the title of the film...">
      <textarea id="writingTextArea" placeholder="Write a review here..." name="content"></textarea>
      <label for="isPrivate" id="privacyCheckBoxLabel">This message will post publicly. Select to keep private: </label>
      <input type="checkbox" name="isPrivate" id="privacyCheckbox">
      <br>
      <a href="profile.php" class="submitButton">Previous</a>
      <input type="submit" class="submitButton postBtn" href="writeReview.php" name="submitReviewBtn" value="Submit Post">
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
