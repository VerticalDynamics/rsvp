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
    <div class="container text-center">
      <h2>Welcome, <?=$guestname ?>!</h2>

      <!-- TODO: style this page -->
      <!-- <p>Our wedding site will allow you (and the other invitees in your group) to RSVP, get important details on how to attend, and allow everyone to share photos on the day of the wedding. Choose from the menu above to get started!</p>

      <p>Sincerely,</p>

      <p>Natalie and Nic</p> -->
<?php if (!$_SESSION['isconfirmed']) { ?>
      <a href="rsvp_start.php" class="button button-primary">RSVP NOW</a>
<?php } ?>
    </div>
  </div>

  <?php require_once 'partials/footer.php';?>
</body>
</html>
