<?php require_once 'partials/header.php';?>
<!DOCTYPE html>
<html>
<head>
  <title>Natalie + Nic | Wedding Photos</title>
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.js"></script>
  <script src="galleria/galleria-1.4.2.min.js"></script>
  <style>
  .grid-item {
    float: left;
    width: 20%;
    height: 20%;
    padding: 5px 5px 5px 5px;
  }
  .grid-item img {
    width: 100%;
    height: 100%;
  }
  /* The Modal (background) */
  .modal {
      display: none; /* Hidden by default */
      position: fixed; /* Stay in place */
      z-index: 100; /* Sit on top */
      left: 0;
      top: 0;
      width: 100%; /* Full width */
      height: 100%; /* Full height */
      overflow: auto; /* Enable scroll if needed */
      background-color: rgb(0,0,0); /* Fallback color */
      background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
  }
  /* Modal Content/Box */
  .modal-content {
      background-color: #fefefe;
      margin: 3% auto; /* 3% from the top and centered */
      padding: 20px;
      border: 1px solid #888;
      width: 50%; /* Could be more or less, depending on screen size */
  }
  /* The Close Button */
  .close {
      color: #aaa;
      float: right;
      font-size: 28px;
      font-weight: bold;
  }
  .close:hover,
  .close:focus {
      color: black;
      text-decoration: none;
      cursor: pointer;
  }
  </style>
  <?php require_once 'partials/doc_header.php';?>
</head>
<body id="photos">
  <?php require_once 'partials/menu.php'; ?>
  <div id="main">
    <div class="container">
      <h2>Share the Love <span class="dark-pink">&#9825;</span> with Photos</h2>
      <?php
        $instagram_tag = 'NKbigday2016';
      ?>
      <p class="alert">
        View our photo galley below!<br><br>
        Double-click on any image to view the image fullscreen. Right-click "Save image as..." to save.<br><br>
        <strong>P.S.</strong> Tag your photos <strong>#<?=$instagram_tag?></strong> on Instagram and they'll appear <a href="https://www.instagram.com/explore/tags/<?=$instagram_tag?>/">on this page</a>.
      </p>
      <div class="galleria" ondblclick="zoomImage()">
      <?php
        $dir    = './photos/';
        $photos = scandir($dir);
        $photosCount = count($photos);
        // eliminate "." and ".." returned by scandir
        unset($photos[$photosCount-1]);
        unset($photos[$photosCount-2]);

        foreach ($photos as $photo) {
      ?>
      		<img src="/photos/<?=$photo?>" />
  <?php } ?>
      </div>

    </div>

    <!-- The Modal -->
    <div id="myModal" class="modal">
      <!-- Modal content -->
      <div class="modal-content">
        <span class="close">x</span>
        <img id="modalImage" width="100%" height="100%" >
      </div>
    </div>

  </div>
  <script>
    Galleria.loadTheme('galleria/themes/classic/galleria.classic.min.js');
    Galleria.run('.galleria');

function zoomImage() {
    var galleriaImages = document.getElementsByClassName('galleria-image active');
    for (var i = 0; i < galleriaImages.length; i++)
    {
      var galleriaImage = galleriaImages[i];
      var img = galleriaImage.firstChild;
      modal.style.display = "block";
      modalImage.src = img.src;
    }
}

    // Get the modal
    var modal = document.getElementById('myModal');
    var modalImage = document.getElementById('modalImage');

    // Get the <span> element that closes the modal
    var span = document.getElementsByClassName("close")[0];

    // When the user clicks on <span> (x), close the modal
    span.onclick = function() {
        modal.style.display = "none";
    }

    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }

  </script>
  <?php require_once 'partials/footer.php';?>
</body>
</html>
