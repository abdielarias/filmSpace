const API_KEY = "6109ef65464c6279114456237b791d38";
const url = "https://api.themoviedb.org/3/movie/popular?api_key=" + API_KEY + "&language=en-US&page=1";

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
var timer;
currentImage.addEventListener("click", ()=>{window.location.href = "moviePage.php?movieID="+imagesArray[imageIndex].movieID;});


fetch(url)
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
    newImg.src = "https://image.tmdb.org/t/p/w1280/" + movie.backdrop_path;
    newImg.movieID = movie.id;
    newImg.movieTitle = movie.title;
    newImg.vote_average = movie.vote_average;
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
  currentTitle.innerHTML = imagesArray[imageIndex].movieTitle + "<br>" + "<span style='font-size:.7em;'><span style='color:#454545'>TMDb Score: </span><span style='color:#8c2800'>"+imagesArray[imageIndex].vote_average+"</span></span>";
}


function rightClick(){

  clearInterval(timer);
  cycleCarousel();


  imageIndex++;
  if(imageIndex>imagesArray.length-1){
     imageIndex=0;
  }

  console.log("index: " + imageIndex + " and image url: "+imagesArray[imageIndex].src);

  currentImage.src = imagesArray[imageIndex].src;
  carouselBackground.style.backgroundImage = `url('${imagesArray[imageIndex].src}')`;
  imageAnimate();

  currentTitle.innerHTML = imagesArray[imageIndex].movieTitle + "<br>" + "<span style='font-size:.7em;'><span style='color:#454545'>TMDb Score: </span><span style='color:#8c2800'>"+imagesArray[imageIndex].vote_average+"</span></span>";
}

function leftClick(){

  clearInterval(timer);
  cycleCarousel();

  imageIndex--;
  if(imageIndex<0){
     imageIndex=imagesArray.length-1;
  }

  console.log("index: "+ imageIndex+" and image url: "+imagesArray[imageIndex].src);

  currentImage.src = imagesArray[imageIndex].src;
  carouselBackground.style.backgroundImage = `url('${imagesArray[imageIndex].src}')`;

  imageAnimate();

  currentTitle.innerHTML = imagesArray[imageIndex].movieTitle + "<br>" + "<span style='font-size:.7em;'><span style='color:#454545'>TMDb Score: </span><span style='color:#8c2800'>"+imagesArray[imageIndex].vote_average+"</span></span>";
}

function imageAnimate(){

  currentImage.classList.add("fade");
  setTimeout(()=>{currentImage.classList.remove("fade")}, 300);
}



function cycleCarousel(){

  timer = setInterval(rightClick, 5000);

}

// Touch Events:

const carouselCon = document.querySelector(".carouselContainer");

var start = null;
carouselCon.addEventListener("touchstart", function(event){
 if(event.touches.length === 1){
    //just one finger touched
    start = event.touches.item(0).clientX;
  }else{
    //a second finger hit the screen, abort the touch
    start = null;
  }
});

carouselCon.addEventListener("touchend", function(event){

  var offset = 50;
  if(start){
    //the only finger that hit the screen left it
    var end = event.changedTouches.item(0).clientX;

    if(end > start + offset){
     //rightward swipe
     rightClick();
    }
    if(end < start - offset ){
     //leftward swipe
     leftClick();
    }
  }
});
