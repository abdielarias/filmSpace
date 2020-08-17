// code for deleting post elements and the post from the userpost database
var deleteConfirmation = false;
var globalCallingElement = null;

function deletePost(event, post_id, callingElement){

  event.preventDefault();
  globalCallingElement = callingElement;

  if(deleteConfirmation == false){

    deleteConfirmation = true;

    var div = document.createElement("div");
    div.setAttribute("id", "confirmDelete");
    var textnode = document.createTextNode("Are you sure you want to delete this post?");
    var br = document.createElement("br");
    var deletePostBtn = document.createElement("button");
    var cancelBtn = document.createElement("button");

    deletePostBtn.setAttribute("type", "button");
    deletePostBtn.setAttribute("onclick", "deleteApproved("+post_id+")");
    deletePostBtn.innerText = "Delete Post";

    cancelBtn.setAttribute("type", "button");
    cancelBtn.setAttribute("onclick", "deleteDenied()");
    cancelBtn.innerText = "Cancel";

    div.appendChild(textnode);
    div.appendChild(br);
    div.appendChild(deletePostBtn);
    div.appendChild(cancelBtn);
    div.style.cssText = "position: fixed; top:40%; left:34%; height: 200px;width: 500px;background-color: black;color: white;border-radius:15px;text-align:center;padding-top:100px;";
    document.querySelector("body").appendChild(div);
  } else {
    console.log("confirm button error");
  }
}

function deleteApproved(post_id){

  document.querySelector("#confirmDelete").remove();

  // call up an http request for php to delete the actual userpost from the database
  var xhr = new XMLHttpRequest();

  xhr.open("POST", "deletePostVerify.php", true);
  xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
  var params = "postID="+post_id;
  xhr.send(params);

  xhr.onreadystatechange = function(){

    if(xhr.status == 200 && xhr.readyState == 4){

      //console.log(xhr.responseText);

      if(xhr.responseText = "success"){

        //now delete the div of the actual post based on traveral of the callingElement up the main parent div
        globalCallingElement.parentElement.parentElement.remove();

        //show confirmation dialog box with an ok button:
        var div = document.createElement("div");
        div.setAttribute("id", "confirmDelete");
        var textnode = document.createTextNode("This post has been deleted.");
        var br = document.createElement("br");
        var okBtn = document.createElement("button");

        okBtn.setAttribute("type", "button");
        okBtn.setAttribute("onclick", "deleteDenied()");
        okBtn.innerText = "OK";

        div.appendChild(textnode);
        div.appendChild(br);
        div.appendChild(okBtn);
        div.style.cssText = "position: fixed; top:40%; left:34%; height: 200px;width: 500px;background-color: black;color: white;border-radius:15px;text-align:center;padding-top:100px;";
        document.querySelector("body").appendChild(div);

      } else if(xhr.responseText = "failed"){

        console.log("php command failed");

      } else {
        console.log("Error. Could not delete post. Reported by delete.js Line 61");
      }


    }

  }

  //then show a confimation of deletion and an OK button they can click to close the box



}

function deleteDenied(){

  //remove this cancel confirm div element:
  document.querySelector("#confirmDelete").remove();

  deleteConfirmation = false;
}
