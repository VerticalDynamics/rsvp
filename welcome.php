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
        <p>Welcome to our wedding site. Please use the menu above to get details on how to attend, RSVP now to tell us you're coming, and, later on, share photos taken on the wedding day.</p>
      </div>

<?php if (!$_SESSION['isconfirmed']) { ?>
      <br>
      <a href="rsvp_start.php" class="button button-pink big">RSVP NOW</a>
<?php } ?>
    </div>
  </div>

  <?php require_once 'partials/footer.php';?>
</body>
</html>
