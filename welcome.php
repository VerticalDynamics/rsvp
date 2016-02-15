<?php
  require_once 'partials/header.php';

  $guestname = $_SESSION['guestname'];
?>
<!DOCTYPE html>
<html>
<head>
	<title>Natalie + Nic | Welcome</title>
  <?php require_once 'partials/doc_header.php';?>
</head>
<body id="welcome">
  <?php require_once 'partials/menu.php'; ?>

  <div class="splash-header">
    <div class="container">
      <h2>Welcome <?=$guestname ?></h2>
      <h3>Windsor, Ontario August 27, 2016</h3>

      <a href="rsvp_start.php" class="button button-primary">RSVP</a>
    </div>
  </div>

  <?php require_once 'partials/footer.php';?>
</body>
</html>
