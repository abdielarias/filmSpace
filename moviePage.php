<?php
include 'header.php';
$movieID = $_GET["movieID"];
?>

<div class="moviePage">
  <div class="moviePage-topBanner">
    <div class="moviePage-poster"></div>
    <div class="moviePage-desc">
      <div class="moviePage-title"></div>
      <div class="moviePage-cert"><span id="cert"></span><span id="runtime"></span></div>
    </div>
  </div>

  <div class="moviePage-trailerDiv">
    <div class="iframeWrapper">
    </div>
  </div>
  <div class="moviePage-mid">

  </div>
</div>

<script>

const API_KEY = "6109ef65464c6279114456237b791d38";
var topBanner = document.querySelector(".moviePage-topBanner");
var trailerDiv = document.querySelector(".moviePage-trailerDiv");
var iframeWrapper = document.querySelector(".iframeWrapper");
var posterDiv = document.querySelector(".moviePage-poster");
var descDiv = document.querySelector(".moviePage-desc");
var titleDiv = document.querySelector(".moviePage-title");
var movieCertDiv = document.querySelector(".moviePage-cert");
var runtime = document.querySelector("#runtime");

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
  var title = document.createElement("h1");
  title.innerHTML = movie.title+" ("+movie.release_date.split("-")[0]+")";
  titleDiv.appendChild(title);

  //movie rating
  fetch("https://api.themoviedb.org/3/movie/"+movieID+"/release_dates?api_key="+API_KEY)
  .then((response) => response.json())
  .then((certs) => {
      movieRating.innerHTML = certs.results[0].release_dates[0].certification;
  })
  .catch((err) => console.log(err));

  //movie length
  runtime.innerHTML = Math.trunc(movie.runtime/60)+" hr "+movie.runtime%60+" min";

  //movie description
  var desc = document.createElement("p");
  desc.innerHTML = movie.overview;
  descDiv.appendChild(desc);





  //movie genres


  //movie release date


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

  //Director(s)

  //Writer(s)

  //stars




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






</script>
