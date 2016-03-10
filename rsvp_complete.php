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

  <div id="main">
    <div class="container">
    <h2>RSVP Submission Complete</h2>
<?php
  	$isconfirmed = $_SESSION['isconfirmed'];
  	$guestid = $_SESSION['guestid'];
  	$db = new Database();
  	$conn = $db->openDB();
  	if ($_SERVER['REQUEST_METHOD'] == 'POST' && $isconfirmed == 0 && $guestid != null) {
      $additional_comments = filter_var($_POST['additional_comments'], FILTER_SANITIZE_STRING);
      $query =
        'SET @groupname = (SELECT groupname FROM guest WHERE guestid = :guestid limit 1);
        UPDATE guest
        SET isconfirmed = 1
        WHERE groupname = @groupname;
        UPDATE guest
        SET additionalcomments = :additional_comments
        WHERE guestid = :guestid';
      $stmt = $conn->prepare($query);
      $stmt->bindParam(':guestid', $guestid);
      $stmt->bindParam(':additional_comments', $additional_comments);
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

    <p class="alert success" align="center">Your RSVP has been received and is now locked.<br><br>For any issues with the RSVP process, or if you need more information, <br>please call Nic (613-618-4474) or Natalie (613-400-6289), or email Nic at <a href="mailto:nkoutros@gmail.com">nkoutros@gmail.com</a></p>

    <p>Thank you!</p>

		<a href="song_request.php" class="button button-secondary">Request a song!</a>
		<a href="welcome.php" class="button button-primary">Go to Main Page</a>
  </div>
  </div>

	<?php require_once 'partials/footer.php';?>
</body>
</html>
