<?php
// if no *mandatory* selections were made in rsvp_start, bounce them back (TODO: UI should grey out submit button until selections have been made)
require_once 'partials/header.php';
require_once 'util/db.php';

$hasatleastoneplusone = false; // show the +1 section of the table on this page only if any guest has confirmed at least one +1

// if group has already RSVP'ed, redirect to the RSVP completion page
$isconfirmed = $_SESSION['isconfirmed'];
if($isconfirmed)
{
	header('location:rsvp_complete.php');
}
else
{
	$db = new Database();
	$conn = $db->openDB();
	$query = "update guest set isattending = :isattending, meal = :meal, datemodified = now(), isplusoneattending = :isplusoneattending, plusonemeal = :plusonemeal where guestid = :guestid;";
	foreach ($_POST['isattending'] as $guestid => $isattending)
	{
		$isattending = $_POST['isattending'][$guestid] == 'yes' ? 'y' : 'n' ;
		$meal = $_POST['meal'][$guestid];
		$isplusoneattending = $_POST['isplusoneattending'][$guestid] == 'yes'? 'y' : 'n' ;
		$hasatleastoneplusone |= ($isplusoneattending == 'y'); //to hide the +1's Meal section if no one in the group is taking a +1
		$plusonemeal = $_POST['plusonemeal'][$guestid];
		$stmt = $conn->prepare($query);
		$stmt->bindParam(':isattending', $isattending);
		$stmt->bindParam(':meal', $meal);
		$stmt->bindParam(':isplusoneattending', $isplusoneattending);
		$stmt->bindParam(':plusonemeal', $plusonemeal);
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
  <?php require_once 'partials/doc_header.php';?>
</head>
<body id="rsvp-confirm">
  <div id="main" class="container">
  	<?php require_once 'partials/menu.php'; ?>
  	<h1>Is the Information Below Correct?</h1>
  	<h2>Please confirm your RSVP selections for your group.</h2>
  	<h2>When ready, please press the CONFIRM RSVP button at the bottom of the page.</h2>
  	<table>
  		<tr>
  			<td>
  				Name:
  			</td>
  			<?php foreach($_POST['guest'] as $guestid => $guestname):?>
  			<td>
  				<?php echo $guestname; ?>
  			</td>
  			<?php endforeach;?>
  		</tr>
  		<tr>
  			<td>
  				Attending?
  			</td>
  			<?php 
				if ( $_POST['isattending'] )
				{
					foreach($_POST['isattending'] as $guestid => $isattendingresponse):
			?>
  			<td>
  				<?php echo $isattendingresponse; ?>
  			</td>
  			<?php 
					endforeach;
				}?>
  		</tr>
  		<tr>
  			<td>
  				Meal:
  			</td>
  			<?php foreach($_POST['meal'] as $guestid => $mealselection):?>
  			<td>
  				<?php echo $mealselection; ?>
  			</td>
  			<?php endforeach;?>
  		</tr>
  		<?php if ($hasatleastoneplusone):?>
  		<tr>
  			<td>
  				Bringing +1?
  			</td>
  			<?php
				foreach($_POST['isplusoneattending'] as $guestid => $hasplusone):
			?>
  			<td>
  				<?php echo $hasplusone; ?>
  			</td>
  			<?php endforeach;?>
  		</tr>
  		<tr>
  			<td>
  				+1's Meal:
  			</td>
  			<?php foreach($_POST['plusonemeal'] as $guestid => $plusonemeal):?>
  			<td>
  				<?php echo $plusonemeal != ''? $plusonemeal : "(none selected)"; ?>
  			</td>
  			<?php endforeach;?>
  		</tr>
  		<?php endif; ?>
  	</table>
  	<form action="rsvp_start.php" method="post">
  		<input type="submit" value="REDO RSVP">
  	</form>
  	<form action="rsvp_complete.php" method="post">
  		<input type="submit" value="CONFIRM RSVP">
  	</form>
  </div>
	<?php require_once 'partials/footer.php';?>
</body>
</html>
