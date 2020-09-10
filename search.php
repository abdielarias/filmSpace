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
var pageNum = 1;

//load-more button, I will hide the button fetch more results, then append the button at the end of every new fetch
var loadMoreBtn = document.createElement("a");
loadMoreBtn.classList.add("submitButton");
loadMoreBtn.innerHTML = "load more results";
loadMoreBtn.addEventListener("click", ()=>{
  loadMoreBtn.style.display = "hidden";
  pageNum++;
  console.log(pageNum);
  fetchResults(pageNum);
  loadMoreBtn.style.display = "inline";
});



function fetchResults(pageNum){

  const API_KEY = "6109ef65464c6279114456237b791d38";
  const searchURL = "https://api.themoviedb.org/3/search/movie?api_key="+API_KEY+"&language=en-US&query="+searchQuery+"&page="+pageNum;

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
      resultContainer.appendChild(loadMoreBtn);
    })
    .catch((error) => console.log("error occured: " + error));
  }

  fetchResults(pageNum);





</script>


<?php
include 'footer.php';
?>
