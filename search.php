<?php
include 'header.php';
?>

<div class="fullscreen">
  <div id="searchResults">

  </div>

  <div class="pagination">
  </div>
</div>


<script>

var searchQuery = "<?php echo $_GET['search'] ?>";
var resultContainer = document.querySelector("#searchResults");
var pagination = document.querySelector(".pagination");

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

      if(movie.poster_path){
        newImg.src = "https://image.tmdb.org/t/p/w92/" + movie.poster_path;
      }
      else {
        newImg.src = "./images/noPoster.jpg";
      }
      title.innerHTML = movie.title;

      if(movie.release_date){
        year.innerHTML = movie.release_date.split("-")[0];
      }
      else {
        year.innerHTML = "year unknown";
      }

      resultPanel.appendChild(newImg);
      resultPanel.appendChild(title);
      resultPanel.appendChild(year);

      resultContainer.appendChild(resultPanel);

      resultPanel.addEventListener("click", ()=>{
        window.location.href="moviePage.php?movieID="+movie.id;
      });


    });

    //pagination
    let nextPageBtn = document.createElement("a");
    nextPageBtn.innerHTML = "next page";
    nextPageBtn.href = "";
    let prevPageBtn = document.createElement("a");
    prevPageBtn.innerHTML = "previous page";
    prevPageBtn.href = "";

    pagination.appendChild(prevPageBtn);
    pagination.appendChild(nextPageBtn);



  })
  .catch((error) => console.log("error occured: " + error));




</script>


<?php
include 'footer.php';
?>
