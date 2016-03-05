<?php
  require_once 'partials/header.php';

  $guestname = $_SESSION['guestname'];
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

      <div class="info-column welcome-column">
        <p>Follow the menu to RSVP, get important details on how to attend and to view and share photos from the wedding day. We hope to see you soon!</p>
        <small>&ndash; Natalie and Nic</small>
      </div>

<?php if (!$_SESSION['isconfirmed']) { ?>
      <a href="rsvp_start.php" class="button button-primary">RSVP NOW</a>
<?php } ?>
    </div>
  </div>

  <?php require_once 'partials/footer.php';?>
</body>
</html>
