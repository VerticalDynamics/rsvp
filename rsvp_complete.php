<?php
require_once 'partials/header.php';
require_once 'util/db.php';
?>
<!DOCTYPE html>
<html>
<head>
  <title>Natalie + Nic | RSVP Complete</title>
  <?php require_once 'partials/doc_header.php';?>
</head>
<body id="rsvp-complete">
  <?php require_once 'partials/menu.php'; ?>

  <div id="main" class="container">
    <h1>RSVP Submission Complete</h1>
<?php
  	$isconfirmed = $_SESSION['isconfirmed'];
  	$guestid = $_SESSION['guestid'];
  	$db = new Database();
  	$conn = $db->openDB();
  	if ($isconfirmed == 0 && $guestid != null)
  	{
		$query =
		"set @groupname = (select groupname from guest where guestid = :guestid limit 1);
		update guest
		set guest.isconfirmed = 1
		where guest.groupname = @groupname;";
		$stmt = $conn->prepare($query);
		$stmt->bindParam(':guestid', $guestid);
		$stmt->execute();
		$_SESSION['isconfirmed'] = 1;
		$db->closeDB();
  	}
?>
	 <p>The following invitees have successfully RSVP'ed:</p>

   <ul>
<?php
	$query = "select * from guest where groupname = (select groupname from guest where guestid = :guestid limit 1)";
	$stmt = $conn->prepare($query);
	$stmt->bindParam(':guestid', $guestid);
	$stmt->execute();

	$attendance_status_message;
  $attendance_class_name;

  while ($row = $stmt->fetch())
	{
		$attendance_status_message =
			"is" .
			($row['isattending'] == "n" ? " not" : "") . " attending" .
			($row['isplusoneattending'] == 'y' ? " (and will be bringing a guest)." : ".") .
      ($row['isattending'] == "n" ? " &#10005;" : " &#10003;");
    $attendance_class_name = ($row['isattending'] == "n" ? 'is-not-attending' : 'is-attending');
?>
      <li>
        <span class="<?=$attendance_class_name?>">
          <strong><?=$row['guestname'];?></strong> <?=$attendance_status_message ?>
        </span>
      </li>
<?php
	}
	$db->closeDB();
?>
    </ul>

    <p>Your RSVP has been received and is now locked.</p>
    <p>For any issues with the RSVP process, or if you need more information, please call Nic (613-618-4474) or Natalie (613-400-6289), or email Nic at <a href="mailto:nkoutros@gmail.com">nkoutros@gmail.com</a><p>

    <form method="post">
  		<button formaction="song_request.php" type="submit" class="button-secondary">Pssst... Now's your chance to request a song!</button>
  		<button formaction="welcome.php" type="submit" class="button-primary">You're Done, Thanks! Return to Main Page</button>
  	</form>
  </div>

	<?php require_once 'partials/footer.php';?>
</body>
</html>
