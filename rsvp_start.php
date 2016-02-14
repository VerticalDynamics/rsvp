<?php
require_once 'partials/header.php';
$guestname = $_SESSION['guestname'];
$guestid = $_SESSION['guestid'];
$isconfirmed = $_SESSION['isconfirmed'];

// if group has already RSVP'ed, redirect
if($isconfirmed)
	header('location:rsvp_complete.php');

//assumes groups of max 10 therefore no need for paging results
$GUEST_MAX = 10;
?>
<!DOCTYPE html>
<html>
<head>
  <title>Natalie + Nic | RSVP Start</title>
  <?php require_once 'partials/doc_header.php';?>
</head>
<body id="rsvp-start">
  <div id="main" class="container">
  	<?php require_once 'partials/menu.php'; ?>
  	<h1>Welcome, <?php echo $guestname ?>!</h1>
  	<h2>Please RSVP for each member of your group: </h2>
  	<form class="rsvp" action="rsvp_confirm.php" method="post">
<?php
require_once 'util/db.php';

$db = new Database();
try {
	$conn = $db->openDB();
	//TODO: make it so you are at top of guest list and not included in query return, will need your guest id to be returned
	//TODO make select * only the required fields, not everything in the row
	$query = "SELECT * FROM guest WHERE guest.groupname = (SELECT guest.groupname FROM guest WHERE guest.guestid = :guestid LIMIT 1) ORDER BY guest.guestname";

	$stmt = $conn->prepare($query);
	$stmt->bindParam(':guestid', $guestid);
	$stmt->execute();
	$row = $stmt->fetch();
	$guestcount = 0;
?>
<?php
	while($row && $guestcount <= $GUEST_MAX) {
		//TODO do data validation to make sure no fields were missed, put little red stars to say it's a required field
		$guestcount++;
		//TODO make it so the meal and plus one prompt only appears after you've selected yes
		//TODO security: check submission is made on behalf of group and prevent someone who is not logged in on the group from submitting for another group
		$displayname = ($row['guestname'] == $guestname) ? "you (" . $row['guestname'] . ")" : $row['guestname'];
?>	
	<span class="bold"> <?php echo $guestcount ?>. </span>
	Will <?php echo $displayname ?> be attending the <a href="details.php">wedding reception</a>? <input type="radio" name="isattending[<?php echo $row['guestid'] ?>]" value="yes"> Yes <input type="radio" name="isattending[<?php echo $row['guestid'] ?>]" value="no"> No
	<br>
	<input type = "hidden" name="guest[<?php echo $row['guestid'] ?>]" value="<?php echo $row['guestname'] ?>">
	Meal: <input type = "radio" name="meal[<?php echo $row['guestid'] ?>]" value="Beef"> Beef
		<input type = "radio" name="meal[<?php echo $row['guestid'] ?>]" value="Chicken"> Chicken
		<input type = "radio" name="meal[<?php echo $row['guestid'] ?>]" value="Salmon"> Salmon
		<input type = "radio" name="meal[<?php echo $row['guestid'] ?>]" value="Vegetarian"> Vegetarian
		<br/>
	<?php
		if ($row['isallowedplusone'] == 1)
		{
	?>
			And will <?php echo $row['guestname'] ?> be bringing a plus one? <input type="radio" name="isplusoneattending[<?php echo $row['guestid'] ?>]" value="yes"> Yes <input type="radio" name="isplusoneattending[<?php echo $row['guestid'] ?>]" value="no"> No
			<br>The plue one's meal: <input type = "radio" name="plusonemeal["<?php echo $row['guestid'] ?>]" value="Beef"> Beef
			<input type = "radio" name="plusonemeal[<?php echo $row['guestid'] ?>]" value="Chicken"> Chicken
			<input type = "radio" name="plusonemeal[<?php echo $row['guestid'] ?>]" value="Salmon"> Salmon
			<input type = "radio" name="plusonemeal[<?php echo $row['guestid'] ?>]" value="Vegetarian"> Vegetarian
			<br/>
			<br/>
	<?php
		}
		else
		{
	?>
		<br><input type="hidden" name="isplusoneattending[<?php echo $row['guestid'] ?>]" value="no">
		<br><input type="hidden" name="plusonemeal[<?php echo $row['guestid'] ?>]" value="">
	<?php
		}
		$row = $stmt->fetch();
	}
}
catch(PDOException $e)
{
  echo 'ERROR: ' . $e->getMessage();
}
$db->closeDB();
?>
  		<br>
  		<input type="submit" value="Submit The RSVP For Myself And The Entire Group">
  	</form>
  </div>
	<?php require_once 'partials/footer.php';?>
</body>
</html>
