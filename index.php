<?php
if ( !session_start() )
  die("Couldn't start session.");
else if ( isset($_SESSION['isLoggedIn']) )
	header('location:welcome.php');
?>
<!DOCTYPE html>
<html>
<head>
  <title>Natalie + Nic</title>
  <?php require_once 'partials/doc_header.php';?>
</head>
<body id="home">
  <div class="splash-header">
    <div class="container">
      <h1>Natalie&nbsp;+&nbsp;Nic&rsquo;s Wedding&nbsp;<span class="heart pink">&#9825;</span></h1>
    </div>

    <form class="guest-code-form" name="input" action="util/authentication.php" method="post" autocomplete="off">
      <div class="guest-code-container container">
        <p>Please enter your invitation code</p>

        <label for="guest-password" class="sr-only">Invitation Code</label>
        <input type="password" id="guest-password" name="guestpassword" placeholder="Your invitation code" autofocus required>
        <button type="submit" class="button-primary">Login</button>
      </div>
    </form>
  </div>
	<?php require_once 'partials/footer.php';?>
</body>
</html>
