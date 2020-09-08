<?php
include 'header.php';
?>


<div id="searchResults">

  </div>

</div>


<script>

var searchQuery = "<?php echo $_GET['search'] ?>";
var resultContainer = document.querySelector("#searchResults");

const API_KEY = "6109ef65464c6279114456237b791d38";
const searchURL = "https://api.themoviedb.org/3/search/movie?api_key="+API_KEY+"&language=en-US&query="+searchQuery+"&page=1";

fetch(searchURL)
  .then((response) => response.json())
  .then((jsonData) => {
    var movies = jsonData.results;
    console.log(movies);

    movies.forEach((movie, i) => {

      let resultPanel = document.createElement("div");
      resultPanel.classList.add("resultPanel");
      let newImg = document.createElement("img");
      let title = document.createElement("h2");
      let year = document.createElement("h3");

      newImg.src = "https://image.tmdb.org/t/p/w92/" + movie.poster_path;
      title.innerHTML = movie.title;
      year.innerHTML = movie.release_date.split("-")[0];

      resultPanel.appendChild(newImg);
      resultPanel.appendChild(title);
      resultPanel.appendChild(year);

      resultContainer.appendChild(resultPanel);

      resultPanel.addEventListener("click", ()=>{
        window.location.href="moviePage.php?movieID="+movie.id;
      });

    });





  })
  .catch((error) => console.log("error occured: " + error));




</script>


<?php
include 'footer.php';
?>
