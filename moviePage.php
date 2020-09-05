<?php
include 'header.php';
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
  </div>

  <div class="moviePage-trailerDiv">
    <div class="iframeWrapper">
    </div>
  </div>
  <div class="moviePage-credits">
    <div class="cast">
    </div>
    <div class="crew">
    </div>
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
  runtime.innerHTML = Math.trunc(movie.runtime/60)+" hr "+movie.runtime%60+" min";

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
  color:white;

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

fetch(creditsURL)
.then((response)=>response.json())
.then((jsonData)=>{

  console.log(jsonData);

})
.catch((err)=>console.log(err));





</script>
