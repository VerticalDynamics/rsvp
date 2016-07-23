<?php
  require_once 'partials/header.php';

  $guestname = 'Wedding Guest';
  $greetings = array(
    "Welcome",
    "Hello",
    "Good to see you",
    "Hey there"
  );
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
    <div class="container text-center">
      <h2><?=$greetings[array_rand($greetings)] ?>, <?=$guestname ?>!</h2>
    </div>
  </div>

  <?php require_once 'partials/footer.php';?>
</body>
</html>
