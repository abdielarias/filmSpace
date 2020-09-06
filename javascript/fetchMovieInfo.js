//fetch the movie info from the API (poster, title)----------------------------

function getMovie(movieID, postID){

  console.log("fuck");
  var content = document.querySelector(".contentOf"+postID);
  const API_KEY = "6109ef65464c6279114456237b791d38";
  const movieURL = "https://api.themoviedb.org/3/movie/"+movieID+"?api_key="+API_KEY+"&language=en-US";

  fetch(movieURL)
  .then((res)=>res.json())
  .then((movie)=>{
    console.log(movie);


    var poster = document.createElement("img");
    poster.src = "https://image.tmdb.org/t/p/w780/"+movie.poster_path;

    content.appendChild(poster);
    poster.style = `width:100px;`;


    var title = document.createElement("p");
    title.innerHTML = movie.title;
    content.appendChild(title);


  })
  .catch((err)=>console.log(err));


}
