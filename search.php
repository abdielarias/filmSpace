<?php
include 'header.php';
?>


<div id="#searchResults">
  <div class="search-panel">
    <div class=".search-poster">
    </div>
    <div class="search-desc">
      <div class="search-genre">
      </div>
    </div>
  </div>

</div>


<script>

var searchQuery = <?php $_GET['search'] ?>;

const API_KEY = "6109ef65464c6279114456237b791d38";
const nowPlayingUrl = "https://api.themoviedb.org/3/movie/now_playing?api_key=" + API_KEY + "&language=en-US&page=1";

fetch(nowPlayingUrl)
  .then((response) => response.json())
  .then((jsonData) => {
    var movies = jsonData.results;
    console.log(movies);


  })
  .catch((error) => console.log("error occured: " + error));




</script>


<?php
include 'footer.php';
?>
