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
  <div id="main" class="container">
  	<?php require_once 'partials/menu.php'; ?>

    <h1>Submission Complete</h1>
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
	while ($row = $stmt->fetch())
	{
		$attendance_status_message =
			$row['guestname'] . " is" .
			($row['isattending'] == "n" ? " not" : "") . " attending" .
			($row['isplusoneattending'] == 'y' ? " (and will be bringing a guest)." : ".");
?>
      <li><?php echo $attendance_status_message ?></li>
<?php
	}
	$db->closeDB();
?>
    </ul>

    <p>Your RSVP has been received and is now locked.</p>

    <p>If you need to change your selection, please email <a href="mailto:nkoutros@googlemail.com">nkoutros@googlemail.com</a> for assistance.<p>

    <form action="welcome.php" method="post">
  		<button type="submit" class="button-primary">You're Done, Thanks! Return to Main Page</button>
  	</form>
  </div>

	<?php require_once 'partials/footer.php';?>
</body>
</html>
