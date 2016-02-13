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
  <div id="main" class="container">
    <h1>Welcome to Natalie and Nic's Wedding Site!</h1>
  	<form class="enter" name="input" action="util/authentication.php" method="post">
      Please enter your code below <i>(you can find it on your invitation)</i>
      <br>
      <br>
  		Code: <input type="password" name="guestpassword"> <input type="submit" value="Login">
  	</form>
  </div>
	<?php require_once 'partials/footer.php';?>
</body>
</html>
