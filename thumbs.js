

function thumbUp(event, post_id, callingElement){
  event.preventDefault();


  var xhr = new XMLHttpRequest();

  xhr.open("POST", "thumbUp.php", true);
  xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

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

        if(xhr.responseText=="not_signed_in"){
          alert("Please sign in to vote on posts.");
        }
        else{

          response = xhr.responseText.split("-");
          num_dislikes = response[0];
          num_likes = response[1];

          var likeSpanElement = callingElement.nextSibling;
          likeSpanElement.innerHTML = num_likes;


          //change the other thumb button's opacity:
          var dislikeButton = callingElement.nextSibling.nextSibling.nextSibling;
          dislikeButton.innerHTML = '<img style="opacity: 50%;" id="thumbdown" src="images/thumbDown.png">';

          var dislikeCountSpan = callingElement.nextSibling.nextSibling.nextSibling.nextSibling;
          dislikeCountSpan.innerHTML = num_dislikes;


          callingElement.innerHTML = '<img style="opacity: 100%;" id="thumbup" src="images/thumb.png">';

        }

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

        if(xhr.responseText=="not_signed_in"){
          alert("Please sign in to vote on posts.");
        } else {

          response = xhr.responseText.split("-");
          num_dislikes = response[0];
          num_likes = response[1];

          var dislikeSpanElement = callingElement.nextSibling;
          dislikeSpanElement.innerHTML = num_dislikes;

          //change the other thumb button's opacity:
          var likeButton = callingElement.previousSibling.previousSibling.previousSibling;
          likeButton.innerHTML = '<img style="opacity: 50%;" id="thumbup" src="images/thumb.png">';

          var likeCountSpan = callingElement.previousSibling.previousSibling;
          likeCountSpan.innerHTML = num_likes;

          callingElement.innerHTML = '<img style="opacity: 100%;" id="thumbdown" src="images/thumbDown.png">';
        }

      } else {
        console.log('xhr error: ' + xhr.status);
      }
    }
  }
}
