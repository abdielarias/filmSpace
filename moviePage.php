<?php
include 'header.php';
require 'databaseConn.php';
$movieID = $_GET["movieID"];
?>

<div class="moviePage">
  <div class="moviePage-topBanner">
    <div class="moviePage-poster"></div>
    <div class="moviePage-desc">
      <div>
        <span class="moviePage-title">
        </span>
        <span id="year"></span>
        <div id="genres"></div>
      </div>
      <div class="moviePage-cert">
        <span id="cert"></span>
        <span id="runtime"></span>
        <span id="releaseDate"></span>
      </div>
      <div id="desc">
      </div>
    </div>
    <div class="reviewCorner">
      <!-- IF we are logged in, php will check if a post from you for this movie exists. If it does, show it, otherwise show Create New Review BTN.
      BUT if we are not signed in, we will show simply label that says sign in to post a review of this film -->
      <?php
      //Search to see if post exists
      if(isset($_SESSION['id'])){
        $SIGNED_IN = true;
        $user_id = $_SESSION['id'];
      } else {
        $SIGNED_IN = false;
      }

      if($SIGNED_IN){

        //check database for $HAS_POST = false;
        $sql = "SELECT * FROM userposts WHERE user_id=? and movie_id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $user_id, $movieID);
        $stmt->execute();
        $results = $stmt->get_result();
        $row = $results->fetch_assoc();
        $HAS_POST = false;
        if($row) $HAS_POST = true;

        if($HAS_POST){

          //setup datetime datetime objects
          $createdDate = date_create($row['post_date']);
          $formattedCreatedDate = date_format($createdDate,"g:i A - M d,Y");
          $modifiedDate = date_create($row['modified_date']);
          $formattedModifiedDate = date_format($modifiedDate,"g:i A - M d,Y");

          echo '
          <div id="userName">'.$_SESSION['userName'].'\'s review:</div>
          <div id="contentText">
            '.$row['content'].'
            <span id="editPostIconSpan" class="bottomRight">
              <a href="writeReview.php?post_id='.$row['post_id'].'&movieID='.$row['movie_id'].'">
                <img src="images/pencil.png">
              </a>
            </span>
          </div>
          <span class="timeOnPostedReview">last modified: '.$formattedModifiedDate.'</span>

          ';
        }
        else{
          echo '<a class="submitButton createPostBtn" id="createReviewBtn" href="writeReview.php?movieID='.$movieID .'">Create a New Film Review</a>
          <div id="contentText">
            What did you think of this film?

          </div>
          ';
        }
      }
      else {

        echo '
        <div id="userName">Your review:</div>
        <div id="contentText">
          Sign in or create a free account to write a review. On each film page, you will readily be able to look back at your opinion of a previously seen film.
          <span id="editPostIconSpan" class="bottomRight">


          </span>
        </div>


        ';
      }



       ?>
    </div>
  </div>

  <div class="moviePage-trailerDiv">
    <div class="iframeWrapper">
    </div>
  </div>

  <div class="moviePage-credits">

  </div>
</div>

<script>

const API_KEY = "6109ef65464c6279114456237b791d38";
var topBanner = document.querySelector(".moviePage-topBanner");
var trailerDiv = document.querySelector(".moviePage-trailerDiv");
var iframeWrapper = document.querySelector(".iframeWrapper");
var posterDiv = document.querySelector(".moviePage-poster");
var descDiv = document.querySelector(".moviePage-desc");
var title = document.querySelector(".moviePage-title");
var movieCertDiv = document.querySelector(".moviePage-cert");
var runtime = document.querySelector("#runtime");
var genres = document.querySelector("#genres");
var year = document.querySelector("#year");
var releaseDate = document.querySelector("#releaseDate");
var desc = document.querySelector("#desc");
// var reviewCorner = document.querySelector(".reviewCorner");
var movieID = <?php echo $movieID; ?>;
var movieRating = document.querySelector("#cert");
const movieURL = "https://api.themoviedb.org/3/movie/"+movieID+"?api_key="+API_KEY+"&language=en-US";

fetch(movieURL)
.then((response) => response.json())
.then((movie) => {
//All html rendering and updating must be done in here.
  console.log(movie);

  //movie poster
  var poster = document.createElement("img");
  poster.src = "https://image.tmdb.org/t/p/w780/"+movie.poster_path;
  posterDiv.appendChild(poster);

  //title

  title.innerHTML = movie.title;
  year.innerHTML = "("+movie.release_date.split("-")[0]+")";

  //movie rating
  fetch("https://api.themoviedb.org/3/movie/"+movieID+"/release_dates?api_key="+API_KEY)
  .then((response) => response.json())
  .then((certs) => {

      let resultsArray;
      let releaseDatesArray;
      resultsArray = certs.results;

      //loop through resultsArray to find the US releaseDates
      for(let i = 0; i< certs.results.length; i++){
        if(certs.results[i].iso_3166_1 == "US"){
          //we have found the US release dates
          releaseDatesArray = certs.results[i].release_dates;
        }
      }

      if(releaseDatesArray){
        //print the final release date rating
        let lastIndex = releaseDatesArray.length-1;
        if(releaseDatesArray[lastIndex].certification == ""){
          movieRating.innerHTML = "NR"
        }
        else{
          movieRating.innerHTML = releaseDatesArray[lastIndex].certification;
        }
      }
      else {
        movieRating.innerHTML = "NR";
      }
  })
  .catch((err) => console.log(err));

  //movie length
  if(movie.runtime > 0){
    if(movie.runtime > 60){
      runtime.innerHTML = Math.trunc(movie.runtime/60)+" hr "+movie.runtime%60+" min";
    } else {
      runtime.innerHTML = movie.runtime+" min";
    }
  }
  else{
    runtime.innerHTML = "runtime: TBA";
  }

  //movie description
  desc.innerHTML = movie.overview;

  //movie genres
  for(let i=0; i<movie.genres.length; i++){
    //if the element is the final element, do not add a comma after the word
    if(i==movie.genres.length-1){
      genres.innerHTML += movie.genres[i].name+" ";
    }else{
      genres.innerHTML += movie.genres[i].name+", ";
    }
  }


  //movie release date

  let dateArray = movie.release_date.split("-");
  let date = new Date(dateArray[0], dateArray[1]-1, dateArray[2]);
  releaseDate.innerHTML = date.getDate() + " " + date.toLocaleString('default', { month: 'long' }) + " "+ date.getFullYear() + " (USA)";


  //Create post button <a class="submitButton createPostBtn" href="writeReview.php?movieID">Create New Film Review</a>
  // var postBtn = document.createElement("a");
  // postBtn.classList.add("submitButton");
  // postBtn.classList.add("createPostBtn");
  // postBtn.href = "writeReview.php?movieID="+movieID;
  // postBtn.innerHTML = "Write a Review";
  // reviewCorner.appendChild(postBtn);

  //make request to get a json object with a possible trailer attached. Check if it's youtube, then show trailer with a youtube link
  fetch("https://api.themoviedb.org/3/movie/"+movieID+"/videos?api_key="+API_KEY+"&language=en-US")
  .then((res) => res.json())
  .then((trailer) => {
    var site = trailer.results[0].site;
    if(site == "YouTube"){
      //create iframe & embed
      var iframe = document.createElement("iframe");
      iframe.src = "https://www.youtube.com/embed/"+trailer.results[0].key;
      iframeWrapper.appendChild(iframe);
      console.log("width"+iframe.width);
      iframe.style = `
      position: absolute;
      border: 0;
      height: 100%;
      left: 0;
      top: 0;
      width: 100%;
      `;

    }
  });

  //run a new stylesheet for the new elements:
  poster.style = `
  display: block;
  box-sizing: border-box;
  width: 100%;
  padding:20px;
  
  `;

  title.style =  `
  box-sizing: border-box;
  color:#ffe9d4;

  `;

  descDiv.style = `
  box-sizing: border-box;
  font-family: arial;
  width: 100%;
  `

})
.catch((error)=>{console.log(error);});



//Credits--------------------------------------------------------------------
var creditsURL = "https://api.themoviedb.org/3/movie/"+movieID+"/credits?api_key="+API_KEY;
var castArray;
var crewArray;
var creditsDiv = document.querySelector(".moviePage-credits");


fetch(creditsURL)
.then((response)=>response.json())
.then((jsonData)=>{
  castArray = jsonData.cast;
  crewArray = jsonData.crew;
  console.log(castArray);
  console.log(crewArray);

  //add a label that says CAST
  let castLabel = document.createElement("h2");
  castLabel.innerHTML = "CAST: ";
  castLabel.style = `padding: 20px;`;
  creditsDiv.appendChild(castLabel);

  for(let i=0; i<castArray.length; i++){
    //create a crewPanel for each person.
    let personPanel = document.createElement("div");
    personPanel.classList.add("cast");
    let personImg = document.createElement("img");


    personImg.src = "https://image.tmdb.org/t/p/w45/" + castArray[i].profile_path;
    if(personImg.src == "https://image.tmdb.org/t/p/w45/null"){
      personImg.src = "./profilePics/defaultPersonPic.jpg";
    }

    personPanel.appendChild(personImg);
    personPanel.innerHTML += castArray[i].name;
    if(castArray[i].character){
      personPanel.innerHTML += " as " + castArray[i].character;
    }


    creditsDiv.appendChild(personPanel);
  }

  //add a label that says CREW
  let crewLabel = document.createElement("h2");
  crewLabel.innerHTML = "CREW: ";
  crewLabel.style = `padding: 20px;`;
  creditsDiv.appendChild(crewLabel);

  for(let i=0; i<crewArray.length; i++){
    //create a crewPanel for each person.
    let personPanel = document.createElement("div");
    personPanel.classList.add("crew");

    personPanel.innerHTML += crewArray[i].name + " - " + crewArray[i].job;


    creditsDiv.appendChild(personPanel);
  }



  var castClassElements = document.querySelectorAll(".cast");

  for(let i=0;i<castClassElements.length;i++){
    castClassElements[i].style = `

      display: grid;
      grid-template-columns: 1fr 8fr;
      padding: 10px;
      margin-left:40px;
      align-items: center;
    `;
  }

  var crewClassElements = document.querySelectorAll(".crew");

  for(let i=0;i<crewClassElements.length;i++){
    crewClassElements[i].style = `

    padding: 10px;
    margin-left:40px;
    `;
  }

  //finally, add the footer:
  var footer = document.createElement("footer");
  footer.innerHTML = "This product uses the TMDb API but is not endorsed or certified by TMDb.";
  footer.style = `margin-top: 20px;`;
  creditsDiv.appendChild(footer);

})
.catch((err)=>console.log(err));

</script>
