
function lockUp(event, post_id, callingElement){

  event.preventDefault();

  var xhr = new XMLHttpRequest();

  xhr.open("POST", "privacyLockOpen.php", true);
  xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

  var params = 'post_id='+post_id;
  xhr.send(params);

  xhr.onreadystatechange = function(){

    if(xhr.readyState == 4 && xhr.status == 200){


      if(xhr.responseText == "not_signed_in"){
        alert("Must be signed in to change privacy settings.");
      }
      else if(xhr.responseText == "success"){


      callingElement.outerHTML = '<a href="#" onclick="lockDown(event, '+post_id+', this)"><img id="privacyLockImg" src="images/lockUp.png"></a>';
      console.log("lock up");
      
      }
      else {
        console.log("Error encountered. Failed to change privacy settings.");
      }

    }
  }
}


function lockDown(event, post_id, callingElement){

  event.preventDefault();

  var xhr = new XMLHttpRequest();

  xhr.open("POST", "privacyLockClose.php", true);
  xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

  xhr.onreadystatechange = function(){

    if(xhr.readyState == 4 && xhr.status == 200){


      if(xhr.responseText == "not_signed_in"){
        alert("Must be signed in to change privacy settings.");
      }
      else if(xhr.responseText == "success"){


      callingElement.outerHTML = '<a href="#" onclick="lockUp(event, '+post_id+', this)"><img id="privacyLockImg" src="images/lockDown.png"></a>';
      console.log("lock down");
      }
      else {
        console.log("Error encountered. Failed to change privacy settings.");
      }

    }

  }
  var params = 'post_id='+post_id;
  xhr.send(params);
}
