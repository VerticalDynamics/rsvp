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
  	<br>
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
	The following invitees have successfully RSVP'ed:
	<br>
	<br>
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
	<?php echo $attendance_status_message ?><br>
	<?php
	}
	$db->closeDB();
  	?>
	<br>
  	Your RSVP has been received and is now locked.<br>
	<br>
	If you need to change your selection, please email nkoutros@googlemail.com for assistance.<br>
	<br>
  	<form action="welcome.php" method="post">
  		<input type="submit" value="You're Done, Thanks! Return to Main Page">
  	</form>
  </div>
	<?php require_once 'partials/footer.php';?>
</body>
</html>
