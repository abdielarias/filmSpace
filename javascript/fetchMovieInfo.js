//fetch the movie info from the API (poster, title)----------------------------

function getMovie(movieID, postID){


  var content = document.querySelector(".contentOf"+postID);
  const API_KEY = "6109ef65464c6279114456237b791d38";
  const movieURL = "https://api.themoviedb.org/3/movie/"+movieID+"?api_key="+API_KEY+"&language=en-US";

  fetch(movieURL)
  .then((res)=>res.json())
  .then((movie)=>{


    //main div
    var posterAndTitleDiv = document.createElement("div");
    posterAndTitleDiv.classList.add("posterAndTitleDiv");
    // posterAndTitleDiv.style.backgroundImage = 'url("https://image.tmdb.org/t/p/w154/'+movie.poster_path+'")';

    var title = document.createElement("div");
    title.style="float:left;width:160px;margin-top:10px;margin-left:10px;text-decoration: underline;font-size:.7em;"
    title.innerHTML = movie.title;
    posterAndTitleDiv.appendChild(title);


    var poster = document.createElement("img");
    poster.src = "https://image.tmdb.org/t/p/w92/"+movie.poster_path;
    posterAndTitleDiv.appendChild(poster);
    posterAndTitleDiv.style = `cursor:pointer;`;
    posterAndTitleDiv.addEventListener("click", ()=>{window.location.href = "moviePage.php?movieID="+movieID;});

    content.prepend(posterAndTitleDiv);


  })
  .catch((err)=>console.log(err));


}
