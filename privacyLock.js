
function lockUp(event, post_id, callingElement){

  event.preventDefault();

  var xhr = new XMLHttpRequest();

  xhr.open("POST", "privacyLockOpen.php", true);
  xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

  xhr.onreadystatechange = function(){

    if(xhr.readystate == 4 && xhr.status == 200){

      if(xhr.responseText == "success"){

        xhr.onload = function() {
          callingElement.innerHTML = '<img id="privacyLockImg" src="images/lockUp.png">';
        }
      }

    }
  }
  var params = 'post_id='+post_id;
  xhr.send(params);

  console.log("Lock up");
}

function lockDown(event, post_id, callingElement){
  event.preventDefault();

  var xhr = new XMLHttpRequest();

  xhr.open("POST", "privacyLockClose.php", true);
  xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

  xhr.onreadystatechange = function(){

    if(xhr.readystate == 4 && xhr.status == 200){

      if(xhr.responseText == "success"){

        xhr.onload = function() {
          callingElement.innerHTML = '<img id="privacyLockImg" src="images/lockDown.png">';
        }
      }

    }
  }
  var params = 'post_id='+post_id;
  xhr.send(params);

  console.log("Lock down");
}
