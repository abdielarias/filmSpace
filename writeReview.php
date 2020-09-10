<?php

// This page is only for when login is successful, and we only arrive here if we have a movieID GET variable in the url
include 'header.php';
require 'databaseConn.php';

if(!isset($_SESSION['id'])){
  header("Location: index.php?error=accessDenied");
  exit();
}

if(!isset($_GET['movieID'])){
  echo "Location: index.php?error=accessDeniedNoMovieID";
  exit();
}

$userName = $_SESSION['userName'];
$user_id = $_SESSION['id'];
$imageLocation = $_SESSION['image'];
$postExists = false;
$movie_id = $_GET['movieID'];

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
        echo '<input type="hidden" id="movie_id" name="movie_id" value="">';
        echo '<img src="" id="moviePoster">';
        echo '<h2 id="movieTitle"></h2>';
        echo '<br>';
        echo '<textarea id="writingTextArea" name="content" onkeyup="displayWordCount()" maxlength="500">'.$row['content'].'</textarea>';
        echo '<br>';
        echo '<div style="text-align:right; font-family:arial; float:right;"><span id="wordCount">0 characters. </span>(limit: 500 characters)';
        echo '<br>';
        if($row['private'] == 1){
          echo '<br>';
          echo '<label for="isPrivate" id="privacyCheckBoxLabel">This post is private. Unselect to make public: </label>';
          echo '<input type="checkbox" name="isPrivate" id="privacyCheckbox" checked></div>';
        } else {
          echo '<br>';
          echo '<label for="isPrivate" id="privacyCheckBoxLabel">This message will post publicly. Select to keep private: </label>';
          echo '<input type="checkbox" name="isPrivate" id="privacyCheckbox"></div>';
        }

      } else{
        echo '<input type="hidden" id="post_id" name="post_id" value="new">';
        echo '<input type="hidden" id="movie_id" name="movie_id" value="">';
        echo '<img src="" id="moviePoster">';
        echo '<h2 id="movieTitle"></h2>';
        echo '<br>';
        echo '<textarea id="writingTextArea" placeholder="Write a review here..." name="content" onkeyup="displayWordCount()" maxlength="500"></textarea>';
        echo '<br>';
        echo '<div style="text-align:right; font-family:arial; display: inline-block; float:right;">';
        echo '<span id="wordCount">0 characters. </span>(limit: 500 characters) ';
        echo '<br>';
        echo '<br>';
        echo '<label for="isPrivate" id="privacyCheckBoxLabel">This message will post publicly. Select to keep private: </label>';
        echo '<input type="checkbox" name="isPrivate" id="privacyCheckbox"></div>';
      }
       ?>


      <br>
      <div class="buttonCenter">
        <a href="profile.php" class="submitButton">Cancel</a>
        <input type="submit" class="submitButton postBtn"  name="submitReviewBtn" value="Submit Post">
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

displayWordCount();

//word counter.
function displayWordCount(){
  var wordCountDisplayer = document.querySelector("#wordCount");
  var charCount = document.querySelector("#writingTextArea").value.length;
  wordCountDisplayer.innerHTML = charCount+" characters. ";
}

//fetch the movie info from the API (poster, title)----------------------------

  var movieID = <?php echo $_GET['movieID'] ?>;
  const API_KEY = "6109ef65464c6279114456237b791d38";
  const movieURL = "https://api.themoviedb.org/3/movie/"+movieID+"?api_key="+API_KEY+"&language=en-US";
  var moviePoster = document.querySelector("#moviePoster");
  var movieTitle = document.querySelector("#movieTitle");

  var hiddenInputMovieID = document.querySelector("#movie_id");
  hiddenInputMovieID.value = movieID;

  fetch(movieURL)
  .then((res)=>res.json())
  .then((movie)=>{
    console.log(movie);
    moviePoster.src = "https://image.tmdb.org/t/p/w780/"+movie.poster_path;
    movieTitle.innerHTML = movie.title;
    moviePoster.style=`cursor:pointer;`;
    //href=
    moviePoster.addEventListener("click",()=>{
      window.location.href="moviePage.php?movieID="+movieID;
    });


  })
  .catch((err)=>console.log(err));






//Time information-----------------------------------------------------------
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
