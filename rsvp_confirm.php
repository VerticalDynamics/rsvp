<?php
// if no *mandatory* selections were made in rsvp_start, bounce them back (TODO: UI should grey out submit button until selections have been made)
require_once 'partials/header.php';
require_once 'util/db.php';

$ENABLE_MEAL_SELECTION = $_SESSION['ENABLE_MEAL_SELECTION'];
$hasatleastoneplusone = false; // show the +1 section of the table on this page only if any guest has confirmed at least one +1

// if group has already RSVP'ed, redirect to the RSVP completion page
$isconfirmed = $_SESSION['isconfirmed'];
if($isconfirmed) {
	header('location:rsvp_complete.php');
}
else {
	$db = new Database();
	$conn = $db->openDB();
	$query =
		"UPDATE guest SET isattending = :isattending, meal = :meal, datemodified = now(), isplusoneattending = :isplusoneattending, plusonemeal = :plusonemeal, email = :email, address_street = :address_street, address_city = :address_city, address_state = :address_state, address_zip = :address_zip WHERE guestid = :guestid;";
  // at the confirmation screen, the RSVP is submitted but the isconfirmed flag is not set - this way if a guest abandons the process at this stage, at least the info is captured and the reason they did not confirm the RSVP can be troubledshooted later
	foreach ($_POST['isattending'] as $guestid => $isattending) {
		$isattending = $_POST['isattending'][$guestid] == 'yes' ? 'y' : 'n' ;
    $meal = $ENABLE_MEAL_SELECTION ? isset($_POST['meal'][$guestid]) ? $_POST['meal'][$guestid] : '' : '';
		$isplusoneattending = $_POST['isplusoneattending'][$guestid] == 'yes'? 'y' : 'n' ;
		$hasatleastoneplusone |= ($isplusoneattending == 'y'); //to hide the +1's Meal section if no one in the group is taking a +1
		$plusonemeal = $_POST['plusonemeal'][$guestid];
		$email = $_POST['email-address'][$guestid];
		$street = $_POST['mailing-address-street'][$guestid];
		$city = $_POST['mailing-address-city'][$guestid];
		$state = $_POST['mailing-address-state'][$guestid];
		$zip = $_POST['mailing-address-postalcode'][$guestid];
		$stmt = $conn->prepare($query);
		$stmt->bindParam(':isattending', $isattending);
		$stmt->bindParam(':meal', $meal);
		$stmt->bindParam(':isplusoneattending', $isplusoneattending);
		$stmt->bindParam(':plusonemeal', $plusonemeal);
		$stmt->bindParam(':email', $email);
		$stmt->bindParam(':address_street', $street);
		$stmt->bindParam(':address_city', $city);
		$stmt->bindParam(':address_state', $state);
		$stmt->bindParam(':address_zip', $zip);
		$stmt->bindParam(':guestid', $guestid);
		$stmt->execute();
	}
	$db->closeDB();
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Natalie + Nic | RSVP Confirmation</title>
  <?php require_once 'partials/doc_header.php'; ?>
</head>
<body id="rsvp-confirm">
  <?php require_once 'partials/menu.php'; ?>
  <div id="main">
    <div class="container">
  	<h2>Confirm Your RSVP</h2>

    <p>Is your RSVP information below correct?</p>

    <table>
      <tbody>
    		<tr>
    			<td><strong>Invitee Name:</strong></td>
<?php
  foreach ($_POST['guest'] as $guestid => $guestname) {
?>
    			<td><?=$guestname; ?></td>
<?php
  }
?>
    		</tr>
				<tr>
  			  <td><strong>Attending:</strong></td>
<?php
  foreach ($_POST['isattending'] as $guestid => $isattendingresponse) {
?>
          <td><?=$isattendingresponse; ?></td>
<?php
  }
?>
  		  </tr>
<?php
  if ($ENABLE_MEAL_SELECTION) {
?>
		    <tr>
          <td><strong>Meal:</strong></td>
<?php
    foreach ($_POST['isattending'] as $guestid => $isattendingresponse) {
?>
          <td><?= ($isattendingresponse == 'yes') ? $_POST['meal'][$guestid] : '&ndash;' ;?></td>
<?php
    }
  }
?>
  		  </tr>
<?php
  if ($hasatleastoneplusone) {
?>
  		  <tr>
          <td><strong>+1 Attending:</strong></td>
<?php
    foreach ($_POST['isplusoneattending'] as $guestid => $hasplusone) {
?>
          <td><?=$hasplusone; ?></td>
<?php
    }
?>
        </tr>
<?php
    if ($ENABLE_MEAL_SELECTION) {
?>
        <tr>
          <td><strong>+1's Meal:</strong></td>
<?php
      foreach ($_POST['isplusoneattending'] as $guestid => $hasplusone) {
?>
          <td><?= ($hasplusone == 'yes') ? $_POST['plusonemeal'][$guestid] : '&ndash;' ;?></td>
<?php
      }
?>
        </tr>
<?php
    }
  }
?>
      </tbody>
    </table>
		<p>Is your contact information below also correct?</p>
		<table>
		<?php
		$contact_info = [
			'Invitee Name' => 'guest',
			'E-mail' => 'email-address',
			'Street' => 'mailing-address-street',
			'City' => 'mailing-address-city',
			'State' => 'mailing-address-state',
			'Postal Code' => 'mailing-address-postalcode',
		];
		foreach ($contact_info as $contact_type_label => $contact_type) { ?>
						<tr>
		  			  <td><strong><?=$contact_type_label ?>:</strong></td>
		<?php
		  foreach ($_POST[$contact_type] as $guestid => $data_value) {
		?>
		          <td><?=$data_value ? $data_value : '&ndash;'; ?></td>
		<?php
		  }
		?>
		  		  </tr>
		<?php
			} ?>
		</table>
    <form method="post">
      <h2>Additional Comments</h2>
      <p> If you or your group members have any accessibility or special needs such as dietary restrictions, please inform us here:</p>

      <div class="form-group">
        <textarea name="additional_comments" maxlength="200" rows="5" placeholder="list any dietary restrictions here"></textarea>
      </div>

      <button formaction="rsvp_start.php" type="submit" class="button-secondary">REDO RSVP</button>
      <button formaction="rsvp_complete.php" type="submit" class="button-primary">CONFIRM RSVP</button>
    </form>
  </div>
  </div>

	<?php require_once 'partials/footer.php';?>
</body>
</html>
