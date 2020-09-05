<?php
include 'header.php';
$movieID = $_GET["movieID"];
?>

<div class="moviePage">
  <div class="moviePage-topBanner">

  </div>

  <div class="moviePage-bodyDiv">
    <div class="iframeWrapper">
    </div>
  </div>
  <div class="moviePage-mid">

  </div>
</div>

<script>

const API_KEY = "6109ef65464c6279114456237b791d38";
var topBanner = document.querySelector(".moviePage-topBanner");
var bodyDiv = document.querySelector(".moviePage-bodyDiv");
var iframeWrapper = document.querySelector(".iframeWrapper");
var movieID = <?php echo $movieID; ?>;
const movieURL = "https://api.themoviedb.org/3/movie/"+movieID+"?api_key="+API_KEY+"&language=en-US";

fetch(movieURL)
.then((response) => response.json())
.then((movie) => {
//All html rendering and updating must be done in here.
  console.log(movie);

  //movie poster
  var poster = document.createElement("img");
  poster.src = "https://image.tmdb.org/t/p/w780/"+movie.poster_path;

  topBanner.appendChild(poster);

  //title
  var title = document.createElement("h1");
  title.innerHTML = movie.title+" ("+movie.release_date.split("-")[0]+")";
  topBanner.appendChild(title);

  //movie description
  var desc = document.createElement("p");
  desc.innerHTML = movie.overview;
  topBanner.appendChild(desc);

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
  grid-row-start: 1;
  grid-row-end: 3;

  `;

  title.style =  `
  box-sizing: border-box;
  color:white;

  `;

  desc.style = `
  box-sizing: border-box;
  font-family: arial;
  grid-column-start: 2;
  grid-column-end: 3;
  width: 100%;
  `

})
.catch((error)=>{console.log(error);});






</script>
