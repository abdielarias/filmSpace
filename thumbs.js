

function thumbUp(event, post_id, callingElement){
  event.preventDefault();


  var xhr = new XMLHttpRequest();

  xhr.open("POST", "thumbUp.php", true);
  xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

  xhr.onload = function() {
    callingElement.innerHTML = '<img style="opacity: 100%;" id="thumbup" src="images/thumb.png">';
  }

  var params = "postID="+post_id;
  xhr.send(params);
  var num_likes = '';
  var response = '';

  //check for error:
  xhr.onreadystatechange = function () {
    var done = 4;
    var ok = 200;
    if (xhr.readyState === done) {
      if (xhr.status === ok) {

        //All actions expected 'after' the arrival of the http request must be here.
        //Code after this block may occur out of sync and prior to the arrival of the http response.

        response = xhr.responseText.split("-");
        num_dislikes = response[0];
        num_likes = response[1];


        console.log("likes: "+num_likes);
        console.log("dislikes: "+num_dislikes);

        var likeSpanElement = callingElement.nextSibling;
        likeSpanElement.innerHTML = num_likes;


        //change the other thumb button's opacity:
        var dislikeButton = callingElement.nextSibling.nextSibling.nextSibling;
        dislikeButton.innerHTML = '<img style="opacity: 50%;" id="thumbdown" src="images/thumbDown.png">';

        var dislikeCountSpan = callingElement.nextSibling.nextSibling.nextSibling.nextSibling;
        dislikeCountSpan.innerHTML = num_dislikes;


      } else {
        console.log('xhr error: ' + xhr.status);
      }
    }
  }



}

function thumbDown(event, post_id, callingElement){
  event.preventDefault();

  var xhr = new XMLHttpRequest();

  xhr.open("POST", "thumbDown.php", true);
  xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

  xhr.onload = function() {
    callingElement.innerHTML = '<img style="opacity: 100%;" id="thumbdown" src="images/thumbDown.png">';
  }

  var params = "postID="+post_id;
  xhr.send(params);
  var num_dislikes = '';
  var num_likes = '';
  var response;

  //check for error:
  xhr.onreadystatechange = function () {
  var done = 4;
  var ok = 200;
    if (xhr.readyState === done) {
      if (xhr.status === ok) {

        //All actions expected 'after' the arrival of the http request must be here.
        //Code after this block may occur out of sync and prior to the arrival of the http response.

        //update the span element showing number of dislikes

        response = xhr.responseText.split("-");
        num_dislikes = response[0];
        num_likes = response[1];

        console.log("likes: "+num_likes);
        console.log("dislikes: "+num_dislikes);
        var dislikeSpanElement = callingElement.nextSibling;
        dislikeSpanElement.innerHTML = num_dislikes;


        //change the other thumb button's opacity:
        var likeButton = callingElement.previousSibling.previousSibling.previousSibling;
        likeButton.innerHTML = '<img style="opacity: 50%;" id="thumbup" src="images/thumb.png">';

        var likeCountSpan = callingElement.previousSibling.previousSibling;
        likeCountSpan.innerHTML = num_likes;


      } else {
        console.log('xhr error: ' + xhr.status);
      }
    }
  }
}
