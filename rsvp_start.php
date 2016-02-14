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

  	<form class="rsvp" action="rsvp_confirm.php" method="post" autocomplete="off">
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
  <ol>
<?php
	while($row && $guestcount <= $GUEST_MAX) {
		$guestcount++;
		//TODO security: check submission is made on behalf of group and prevent someone who is not logged in on the group from submitting for another group
		$displayname = ($row['guestname'] == $guestname) ? "you (" . $row['guestname'] . ")" : $row['guestname'];
?>
  <li class="question-container">
    <input type="hidden" name="guest[<?php echo $row['guestid'] ?>]" value="<?php echo $row['guestname'] ?>">

    <p class="form-text">Will <?php echo $displayname ?> be attending the <a href="details.php" target="_blank">wedding reception</a>?</p>

    <div class="question-reply-container">
      <label for="is-attending-yes-<?=$guestcount ?>">
        Yes <input type="radio" id="is-attending-yes-<?=$guestcount ?>" name="isattending[<?php echo $row['guestid'] ?>]" value="yes" required>
      </label>

      <label for="is-attending-no-<?=$guestcount ?>">
        No <input type="radio" id="is-attending-no-<?=$guestcount ?>" name="isattending[<?php echo $row['guestid'] ?>]" value="no">
      </label>

      <div data-if="isattending[<?php echo $row['guestid'] ?>] = yes" data-required-fields="meal-beef-<?=$guestcount ?>">
        <p class="form-text-small">Meal:</p>

        <label for="meal-beef-<?=$guestcount ?>">
          Beef <input type="radio" id="meal-beef-<?=$guestcount ?>" name="meal[<?php echo $row['guestid'] ?>]" value="Beef">
        </label>

        <label for="meal-chicken-<?=$guestcount ?>">
          Chicken <input type="radio" id="meal-chicken-<?=$guestcount ?>" name="meal[<?php echo $row['guestid'] ?>]" value="Chicken">
        </label>

        <label for="meal-salmon-<?=$guestcount ?>">
          Salmon <input type="radio" id="meal-salmon-<?=$guestcount ?>" name="meal[<?php echo $row['guestid'] ?>]" value="Salmon">
        </label>

        <label for="meal-vegetarian-<?=$guestcount ?>">
          Vegetarian <input type="radio" id="meal-vegetarian-<?=$guestcount ?>" name="meal[<?php echo $row['guestid'] ?>]" value="Vegetarian">
        </label>
      </div>
<?php
  	if ($row['isallowedplusone'] == 1)
  	{
?>
      <div data-if="isattending[<?php echo $row['guestid'] ?>] = yes" data-required-fields="is-plus-one-attending-yes-<?=$guestcount ?>">
        <p class="form-text-small">Will <?php echo $row['guestname'] ?> be bringing a plus one?</p>

        <label for="is-plus-one-attending-yes-<?=$guestcount ?>">
          Yes <input type="radio" id="is-plus-one-attending-yes-<?=$guestcount ?>" name="isplusoneattending[<?php echo $row['guestid'] ?>]" value="yes">
        </label>

        <label for="is-plus-one-attending-no-<?=$guestcount ?>">
          No <input type="radio" id="is-plus-one-attending-no-<?=$guestcount ?>" name="isplusoneattending[<?php echo $row['guestid'] ?>]" value="no">
        </label>

        <div data-if="isplusoneattending[<?php echo $row['guestid'] ?>] = yes" data-required-fields="plus-one-meal-beef-<?=$guestcount ?>">
          <p class="form-text-small">The plue one's meal:</p>

          <label for="plus-one-meal-beef-<?=$guestcount ?>">
            Beef <input type="radio" id="plus-one-meal-beef-<?=$guestcount ?>" name="plusonemeal[<?php echo $row['guestid'] ?>]" value="Beef">
          </label>

          <label for="plus-one-meal-chicken-<?=$guestcount ?>">
            Chicken <input type="radio" id="plus-one-meal-chicken-<?=$guestcount ?>" name="plusonemeal[<?php echo $row['guestid'] ?>]" value="Chicken">
          </label>

          <label for="plus-one-meal-salmon-<?=$guestcount ?>">
            Salmon <input type="radio" id="plus-one-meal-salmon-<?=$guestcount ?>" name="plusonemeal[<?php echo $row['guestid'] ?>]" value="Salmon">
          </label>

          <label for="plus-one-meal-vegetarian-<?=$guestcount ?>">
            Vegetarian <input type="radio" id="plus-one-meal-vegetarian-<?=$guestcount ?>" name="plusonemeal[<?php echo $row['guestid'] ?>]" value="Vegetarian">
          </label>
			</div>
    </div>
<?php
		}
		else
		{
?>
    	<input type="hidden" name="isplusoneattending[<?php echo $row['guestid'] ?>]" value="no">
    	<input type="hidden" name="plusonemeal[<?php echo $row['guestid'] ?>]" value="">
<?php
		}
?>
    </div>
  </li>
<?php
		$row = $stmt->fetch();
	}
}
catch(PDOException $e)
{
  echo 'ERROR: ' . $e->getMessage();
}
$db->closeDB();
?>
  		</ol>

  		<button type="submit" class="button-primary">Submit The RSVP For Myself And The Entire Group</button>
  	</form>
  </div>

	<?php require_once 'partials/footer.php';?>
</body>
</html>
