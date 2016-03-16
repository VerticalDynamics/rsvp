<?php
require_once 'partials/header.php';

// if group has already RSVP'ed, redirect
if ($_SESSION['isconfirmed'])
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

  <div id="main">
    <div class="container">
  	<h2><?=$isheadofhousehold ? 'Please RSVP for each member of your group' : 'RSVP now to join the celebration'?></h2>

    <p class="alert"><strong>Note:</strong> The first person listed on the invitation envelope has been designated to RSVP for every person in their party. All of the wedding guests are listed on the envelope and will also be listed below, once the designated person initiates the RSVP process.</p>

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

    <p class="form-text">Will <strong class="emphasize"><?=$displayname ?></strong> be attending <a href="schedule.php" target="_blank">the wedding</a>?</p>

    <div class="question-reply-container">
      <label for="is-attending-yes-<?=$guestcount ?>">
        Yes <input type="radio" id="is-attending-yes-<?=$guestcount ?>" name="isattending[<?=$row['guestid'] ?>]" value="yes" required>
      </label>

      <label for="is-attending-no-<?=$guestcount ?>">
        No <input type="radio" id="is-attending-no-<?=$guestcount ?>" name="isattending[<?=$row['guestid'] ?>]" value="no">
      </label>

      <p data-if="isattending[<?=$row['guestid'] ?>] = yes" class="is-attending">We look forward to seeing you there!</p>
      <p data-if="isattending[<?=$row['guestid'] ?>] = no">Sorry to hear you cannot attend. We'll miss you!</p>

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
        <p class="form-text-small">Will <strong><?=$row['guestname'] ?></strong> be bringing a plus one?</p>

        <label for="is-plus-one-attending-yes-<?=$guestcount ?>">
          Yes <input type="radio" id="is-plus-one-attending-yes-<?=$guestcount ?>" name="isplusoneattending[<?=$row['guestid'] ?>]" value="yes">
        </label>

        <label for="is-plus-one-attending-no-<?=$guestcount ?>">
          No <input type="radio" id="is-plus-one-attending-no-<?=$guestcount ?>" name="isplusoneattending[<?=$row['guestid'] ?>]" value="no">
        </label>
<?php
      if ($ENABLE_MEAL_SELECTION)
      {
?>
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
<?php
      }
?>
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

        <div class="tooltip alert alert-tooltip" data-show-for="mailing-address-<?=$guestcount ?>">We kindly ask for your mailing address so that we may show our appreciation for your presence.</div>

        <div class="form-group">
          <label for="mailing-address-street-<?=$guestcount ?>">Street address</label>
          <input type="text" placeholder="street address" id="mailing-address-street-<?=$guestcount ?>" class="mailing-address-<?=$guestcount ?>" name="mailing-address-street[<?=$row['guestid'] ?>]">
        </div>

        <div class="form-group">
          <label for="mailing-address-city-<?=$guestcount ?>">City</label>
          <input type="text" placeholder="city or town" id="mailing-address-city-<?=$guestcount ?>" class="mailing-address-<?=$guestcount ?>" name="mailing-address-city[<?=$row['guestid'] ?>]">
        </div>

        <div class="form-group">
          <label for="mailing-address-state-<?=$guestcount ?>">Province</label>
          <input type="text" placeholder="province or state" id="mailing-address-state-<?=$guestcount ?>" class="mailing-address-<?=$guestcount ?>" name="mailing-address-state[<?=$row['guestid'] ?>]">
        </div>

        <div class="form-group">
          <label for="mailing-address-postalcode-<?=$guestcount ?>">Postal code</label>
          <input type="text" placeholder="postal code or ZIP" id="mailing-address-postalcode-<?=$guestcount ?>" class="mailing-address-<?=$guestcount ?>" name="mailing-address-postalcode[<?=$row['guestid'] ?>]">
        </div>

        <div class="tooltip alert alert-tooltip" data-show-for="email-address-<?=$guestcount ?>">Worry not &ndash; we don't spam our family &amp; friends, or anyone else for that matter.</div>

        <div class="form-group">
          <label for="email-address-<?=$guestcount ?>">E-mail Address</label>
          <input type="email" placeholder="this@that.com" id="email-address-<?=$guestcount ?>" class="email-address-<?=$guestcount ?>" name="email-address[<?=$row['guestid'] ?>]">
        </div>
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

  		<button type="submit" class="button-primary"><?=$isheadofhousehold ? 'Submit RSVP For Myself And my group' : 'Submit My RSVP'?></button>
  	</form>
  </div>
  </div>

	<?php require_once 'partials/footer.php';?>
</body>
</html>
