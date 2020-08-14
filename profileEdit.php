<?php
session_start();
include 'header.php';

$userName = $_SESSION['userName'];
//default image
$imageName = $_SESSION['image'];
$profilePicFolder = "profilePics/";

//set default profile pic if none exists
if($imageName == "none" OR $imageName == NULL){
  $imageName = $profilePicFolder."defaultAvatar.png";
}
?>


<div class="profileContainer">

  <div class="profileTopPanel">
    <div class="profileImgPanel">
      <img id="profileImg" src="<?php echo $imageName ?>">
      <h1 class="profileUserName"><?php echo  $userName ?></h1>
      <div class="uploadCaption">

          <form action="profileEditVerify.php" method="POST" enctype="multipart/form-data">
            <input type="file" name="file"><br>
            <button type="submit" name="uploadProfileImgBtn">Save</button>
          </form>


      </div>
    </div>
  </div>

  <div class="aboutPanel">

    <h1 id="aboutMe">Bio:</h1>
    <textarea type="text" name="bio" style="display:inline-block;">
        Lorem ipsum dolor sit amet, consectetur adipiscing elit,
        sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
        quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
        Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat
        nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia
        deserunt mollit anim id est laborum.
    </textarea>

  </div>


        <?php
        //Check for errors and print them
        if(isset($_GET['error'])){
          if($_GET['error'] == "fileTypeNotSupported"){
            echo '<p class="warning">Image file type type not supported.</p>';
          } else
          if($_GET['error'] == "fileCouldNotOpen"){
            echo '<p class="warning">An error was encountered with this image. Could not upload.</p>';
          }else
          if($_GET['error'] == "fileTooLarge"){
            echo '<p class="warning">This image is too large.</p>';
          }
        }

         ?>



</div>

<?php
include 'footer.php';
?>
