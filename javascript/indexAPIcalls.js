const API_KEY = "6109ef65464c6279114456237b791d38";
const nowPlayingUrl = "https://api.themoviedb.org/3/movie/now_playing?api_key=" + API_KEY + "&language=en-US&page=1";

var movies;
const carousel = document.querySelector(".carousel");
const carouselBackground = document.querySelector(".carouselBackground");
var imagesArray = new Array();
var imageIndex = 0;
var rightButton;
var leftButton;
var currentImage = document.querySelector("#currentImage");
var currentTitle = document.querySelector("#carouselTitle");
var clickableMovieImage = document.querySelector(".imgWrapper");
currentImage.addEventListener("click", ()=>{window.location.href = "moviePage.php?movieID="+imagesArray[imageIndex].movieID;});


fetch(nowPlayingUrl)
  .then((response) => response.json())
  .then((jsonData) => {
    movies = jsonData.results;
    console.log(movies);
    createMovieCarousel();

  })
  .catch((error) => console.log("error occured: " + error));

//loop through json data and display contents in a banner
function createMovieCarousel(){

  movies.forEach((movie, i) => {

    let newImg = document.createElement("img");
    newImg.src = "https://image.tmdb.org/t/p/w780/" + movie.backdrop_path;
    newImg.movieID = movie.id;
    newImg.movieTitle = movie.title;
    imagesArray.push(newImg);
  });

  leftButton = document.querySelector(".leftCarouselButton");
  leftButton.addEventListener("click", leftClick);

  //initialize first element
  currentImage.src = imagesArray[imageIndex].src;
  //blurry background version of the image
  carouselBackground.style.backgroundImage = `url('${imagesArray[imageIndex].src}')`;

  currentImage.style = `
  display:block;
  height: 100%;
  transition: .1s;
  `;

  imageAnimate();

  rightButton = document.querySelector(".rightCarouselButton");
  rightButton.addEventListener("click", rightClick);

  cycleCarousel();
  currentTitle.innerHTML = imagesArray[imageIndex].movieTitle;
}

function rightClick(){

  imageIndex++;
  if(imageIndex>imagesArray.length-1){
     imageIndex=0;
  }

  console.log("index: " + imageIndex + " and image url: "+imagesArray[imageIndex].src);

  currentImage.src = imagesArray[imageIndex].src;
  carouselBackground.style.backgroundImage = `url('${imagesArray[imageIndex].src}')`;
  imageAnimate();

  currentTitle.innerHTML = imagesArray[imageIndex].movieTitle;
}

function leftClick(){

  imageIndex--;
  if(imageIndex<0){
     imageIndex=imagesArray.length-1;
  }

  console.log("index: "+ imageIndex+" and image url: "+imagesArray[imageIndex].src);

  currentImage.src = imagesArray[imageIndex].src;
  carouselBackground.style.backgroundImage = `url('${imagesArray[imageIndex].src}')`;

  imageAnimate();

  currentTitle.innerHTML = imagesArray[imageIndex].movieTitle;
}

function imageAnimate(){

  currentImage.classList.add("fade");
  setTimeout(()=>{currentImage.classList.remove("fade")}, 300);
}

function cycleCarousel(){

// setInterval(rightClick, 4000);
}
