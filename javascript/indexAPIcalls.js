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

fetch(nowPlayingUrl)
  .then((response) => response.json())
  .then((jsonData) => {
    movies = jsonData.results;
    createMovieCarousel();

  })
  .catch((error) => console.log("error occured: " + error));

//loop through json data and display contents in a banner
function createMovieCarousel(){

  movies.forEach((movie, i) => {

    let newImg = document.createElement("img");
    newImg.src = "https://image.tmdb.org/t/p/w780/" + movie.backdrop_path;
    newImg.movieID = movie.id;
    // newImg.classList.add("currentImage");
    // carousel.appendChild(newImg);
    imagesArray.push(newImg);
  });

  //maybe add the left button here instead of on the main index
  //add the right carousel button <span class="leftCarouselButton">&#10094;</span>


  // carousel.innerHTML = ``;
  leftButton = document.querySelector(".leftCarouselButton");
  leftButton.addEventListener("click", leftClick);

  //initialize first element
  currentImage.src = imagesArray[imageIndex].src;
  //blurry background version of the image
  carouselBackground.style.backgroundImage = `url('${imagesArray[imageIndex].src}')`;


  // carousel.appendChild(imagesArray[0]);
  currentImage.style = `
  display:block;
    height: 95%;
    transition: .1s;
    justify-self: center;
    align-self: center;
    transition: 1s;
  `;

  //blurry background version of the image
  // carouselBackground.style.backgroundImage = `url('${imagesArray[0].src}')`;

  //add the right carousel button <span class="leftCarouselButton">&#10094;</span>
  // let span = document.createElement("span");
  // span.classList.add("rightCarouselButton");
  // span.innerHTML = "&#10095;";
  // carousel.appendChild(span);
  rightButton = document.querySelector(".rightCarouselButton");
  rightButton.addEventListener("click", rightClick);



}


function rightClick(){

  imageIndex++;
  if(imageIndex>imagesArray.length-1){
     imageIndex=0;
  }

  // if(imageIndex<imagesArray.length-1){imageIndex++; console.log("add 1");}
  // else{ if(imageIndex==imagesArray.length-1){console.log(imageIndex); imageIndex = 0;console.log(" was set to 0");}}
  console.log("index: " + imageIndex + " and image url: "+imagesArray[imageIndex].src);



  currentImage.src = imagesArray[imageIndex].src;
  //blurry background version of the image
  carouselBackground.style.backgroundImage = `url('${imagesArray[imageIndex].src}')`;

  imageAnimate();

}

function leftClick(){

  imageIndex--;
  if(imageIndex<0){
     imageIndex=imagesArray.length-1;
  }

  // if(imageIndex>0){imageIndex--; console.log("sub 1");}
  // else if(imageIndex == 0){console.log("equaled "+imageIndex); imageIndex = imagesArray.length-1; console.log(", set to:");}
  console.log("index: "+ imageIndex+" and image url: "+imagesArray[imageIndex].src);


  currentImage.src = imagesArray[imageIndex].src;
  //blurry background version of the image
  carouselBackground.style.backgroundImage = `url('${imagesArray[imageIndex].src}')`;

  imageAnimate();
}

function imageAnimate(){

  currentImage.classList.add("fade");
  setTimeout(()=>{currentImage.classList.remove("fade")}, 300);
}
