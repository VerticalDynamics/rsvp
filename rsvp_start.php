<?php
require_once 'partials/header.php';

// if group has already RSVP'ed, redirect
if($_SESSION['isconfirmed'])
	header('location:rsvp_complete.php');

$guestname = $_SESSION['guestname'];
$guestid = $_SESSION['guestid'];
$isheadofhousehold = $_SESSION['isheadofhousehold'];

//assumes groups of max 10 therefore no need for paging results
$GUEST_MAX = 10;
$ENABLE_MEAL_SELECTION = $_SESSION['ENABLE_MEAL_SELECTION'];
?>
<!DOCTYPE html>
<html>
<head>
  <title>Natalie + Nic | RSVP Start</title>
  <?php require_once 'partials/doc_header.php';?>
</head>
<body id="rsvp-start">
  <?php require_once 'partials/menu.php'; ?>
  
  <div id="main" class="container">
  	<h2><?=$isheadofhousehold ? 'Please RSVP for each member of your group:' : 'RSVP now to join the celebration!'?> </h2>
    <p><strong>Please Note:</strong> if multiple people in your household or family group received an invitation, someone was (perhaps randomly) designated the head of household or group. When that person logs in to the site, they have the ability to RSVP for the entire group.</p> 
  	<form class="rsvp" action="rsvp_confirm.php" method="post" autocomplete="off">
<?php
require_once 'util/db.php';

$db = new Database();
try {
	$conn = $db->openDB();
	//TODO: make it so you are at top of guest list
	//TODO make select * only the required fields, not everything in the row

  // only the head of household may RSVP for other guests
	$query = $isheadofhousehold ? 
    "SELECT * FROM guest WHERE guest.groupname = (SELECT guest.groupname FROM guest WHERE guest.guestid = :guestid LIMIT 1) and isconfirmed <> 1 ORDER BY guest.guestname"
    : "SELECT * FROM guest WHERE guest.guestid = :guestid and isconfirmed <> 1";
     
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
		$displayname = ($row['guestid'] == $guestid) ? "you (" . $row['guestname'] . ")" : $row['guestname'];
?>
  <li class="question-container">
    <input type="hidden" name="guest[<?=$row['guestid'] ?>]" value="<?=$row['guestname'] ?>">

    <p class="form-text">Will <?=$displayname ?> be attending the <a href="details.php" target="_blank">wedding</a>?</p>

    <div class="question-reply-container">
      <label for="is-attending-yes-<?=$guestcount ?>">
        Yes <input type="radio" id="is-attending-yes-<?=$guestcount ?>" name="isattending[<?=$row['guestid'] ?>]" value="yes" required>
      </label>

      <label for="is-attending-no-<?=$guestcount ?>">
        No <input type="radio" id="is-attending-no-<?=$guestcount ?>" name="isattending[<?=$row['guestid'] ?>]" value="no">
      </label>
<?php 
    if ($ENABLE_MEAL_SELECTION) 
    {
?>
      <div data-if="isattending[<?=$row['guestid'] ?>] = yes" data-required-fields="meal-beef-<?=$guestcount ?>">
        <p class="form-text-small">Meal:</p>

        <label for="meal-beef-<?=$guestcount ?>">
          Beef <input type="radio" id="meal-beef-<?=$guestcount ?>" name="meal[<?=$row['guestid'] ?>]" value="Beef">
        </label>

        <label for="meal-chicken-<?=$guestcount ?>">
          Chicken <input type="radio" id="meal-chicken-<?=$guestcount ?>" name="meal[<?=$row['guestid'] ?>]" value="Chicken">
        </label>

        <label for="meal-salmon-<?=$guestcount ?>">
          Salmon <input type="radio" id="meal-salmon-<?=$guestcount ?>" name="meal[<?=$row['guestid'] ?>]" value="Salmon">
        </label>

        <label for="meal-vegetarian-<?=$guestcount ?>">
          Vegetarian <input type="radio" id="meal-vegetarian-<?=$guestcount ?>" name="meal[<?=$row['guestid'] ?>]" value="Vegetarian">
        </label>
      </div>
<?php
    }
  	if ($row['isallowedplusone'] == 1)
  	{
?>
      <div data-if="isattending[<?=$row['guestid'] ?>] = yes" data-required-fields="is-plus-one-attending-yes-<?=$guestcount ?>">
        <p class="form-text-small">Will <?=$row['guestname'] ?> be bringing a plus one?</p>

        <label for="is-plus-one-attending-yes-<?=$guestcount ?>">
          Yes <input type="radio" id="is-plus-one-attending-yes-<?=$guestcount ?>" name="isplusoneattending[<?=$row['guestid'] ?>]" value="yes">
        </label>

        <label for="is-plus-one-attending-no-<?=$guestcount ?>">
          No <input type="radio" id="is-plus-one-attending-no-<?=$guestcount ?>" name="isplusoneattending[<?=$row['guestid'] ?>]" value="no">
        </label>

        <div data-if="isplusoneattending[<?=$row['guestid'] ?>] = yes" data-required-fields="plus-one-meal-beef-<?=$guestcount ?>">
          <p class="form-text-small">The plus one's meal:</p>

          <label for="plus-one-meal-beef-<?=$guestcount ?>">
            Beef <input type="radio" id="plus-one-meal-beef-<?=$guestcount ?>" name="plusonemeal[<?=$row['guestid'] ?>]" value="Beef">
          </label>

          <label for="plus-one-meal-chicken-<?=$guestcount ?>">
            Chicken <input type="radio" id="plus-one-meal-chicken-<?=$guestcount ?>" name="plusonemeal[<?=$row['guestid'] ?>]" value="Chicken">
          </label>

          <label for="plus-one-meal-salmon-<?=$guestcount ?>">
            Salmon <input type="radio" id="plus-one-meal-salmon-<?=$guestcount ?>" name="plusonemeal[<?=$row['guestid'] ?>]" value="Salmon">
          </label>

          <label for="plus-one-meal-vegetarian-<?=$guestcount ?>">
            Vegetarian <input type="radio" id="plus-one-meal-vegetarian-<?=$guestcount ?>" name="plusonemeal[<?=$row['guestid'] ?>]" value="Vegetarian">
          </label>
			</div>
    </div>
<?php
		}
		else
		{
?>
    	<input type="hidden" name="isplusoneattending[<?=$row['guestid'] ?>]" value="no">
    	<input type="hidden" name="plusonemeal[<?=$row['guestid'] ?>]" value="">
<?php
		}
?>
      <div data-if="isattending[<?=$row['guestid'] ?>] = yes" data-required-fields="mailing-address-<?=$guestcount ?>">
        <p class="form-text-small">Contact Info:</p>
        <label for="mailing-address-<?=$guestcount ?>">
          Mailing Address
          <input type="text" placeholder="Apt #/House #/Street" id="mailing-address-street-<?=$guestcount ?>" name="mailing-address-street[<?=$row['guestid'] ?>]">
          <input type="text" placeholder="City or Town" id="mailing-address-city-<?=$guestcount ?>" name="mailing-address-city[<?=$row['guestid'] ?>]">
          <input type="text" placeholder="Province or State" id="mailing-address-state-<?=$guestcount ?>" name="mailing-address-state[<?=$row['guestid'] ?>]">
          <input type="text" placeholder="ZIP or Postal Code" id="mailing-address-postalcode-<?=$guestcount ?>" name="mailing-address-postalcode[<?=$row['guestid'] ?>]">
        </label>
        <!-- todo: need to minimize space: hide this text and have it fade in after mouseover or appear as a tooltip or a round ?-mark button or something -->
        <p><i>We kindly ask for your mailing address so that we may show our appreciation for your presence.</i></p>
        <label for="email-address-<?=$guestcount ?>">
          E-mail Address
          <input type="email" placeholder="this@that.com" id="email-address-<?=$guestcount ?>" name="email-address[<?=$row['guestid'] ?>]">
        </label>
        <!-- todo: need to minimize space: hide this text and have it fade in after mouseover or appear as a tooltip or a round ?-mark button or something -->
        <p><i>Worry not --we don't spam our family &amp; friends. Or anyone else for that matter.</i></p>
      </div>
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

  		<button type="submit" class="button-primary"><?=$isheadofhousehold ? 'Submit The RSVP For Myself And The Entire Group' : 'Submit My RSVP'?></button>
  	</form>
  </div>

	<?php require_once 'partials/footer.php';?>
</body>
</html>
