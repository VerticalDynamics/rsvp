<?php 
require_once 'header.php';
$guestname = $_SESSION['guestname'];
$guestid = $_SESSION['guestid'];
$isconfirmed = $_SESSION['isconfirmed'];

// if group has already RSVP'ed, redirect
if($isconfirmed)
	header('location:rsvp_complete.php');

//assumes groups of max 10 therefore no need for paging results
$GUEST_MAX = 10;
?>
<html>
	<body>			
		<?php require_once 'menu.php'; ?>
		<h1><?php echo "Welcome, " . $guestname . "!" ?></h1>
		<h2>Please RSVP for each member of your group: </h2>		
		<form class="rsvp" action="rsvp_confirm.php" method="post">		
<?php 
require_once 'db.php';

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
	
	while($row && $guestcount <= $GUEST_MAX) 
	{
		//TODO do data validation to make sure no fields were missed, put little red stars to say it's a required field
		$guestcount++;
		//TODO make it so the meal and plus one prompt only appears after you've selected yes
		//TODO security: check submission is made on behalf of group and prevent someone who is not logged in on the group from submitting for another group
		$displayname = $row['guestname'] == $guestname ? "Are you (" . $row['guestname'] . ")" : "Is " . $row['guestname'];
		print("<span class=\"bold\">" . $guestcount . ". </span>");
		print($displayname . " attending? <input type=\"radio\" name=\"isattending[" . $row['guestid'] ."]\" value=\"yes\"> Yes <input type=\"radio\" name=\"isattending[" . $row['guestid'] ."]\" value=\"no\"> No
		<br>
		<input type = \"hidden\" name=\"guest[". $row['guestid'] ."]\" value=\"" . $row['guestname'] . "\">
		Meal: <input type = \"radio\" name=\"meal[". $row['guestid'] ."]\" value=\"Beef\"> Beef
		<input type = \"radio\" name=\"meal[". $row['guestid'] ."]\" value=\"Chicken\"> Chicken
		<input type = \"radio\" name=\"meal[". $row['guestid'] ."]\" value=\"Salmon\"> Salmon
		<input type = \"radio\" name=\"meal[". $row['guestid'] ."]\" value=\"Vegetarian\"> Vegetarian		
		<br/>");
		
		if ($row['isallowedplusone'] == 1)
		{
			print("And will " . $row['guestname'] . " be bringing a plus one? <input type=\"radio\" name=\"isplusoneattending[" . $row['guestid'] ."]\" value=\"yes\"> Yes <input type=\"radio\" name=\"isplusoneattending[" . $row['guestid'] ."]\" value=\"no\"> No ");
			print("<br>The plue one's meal: <input type = \"radio\" name=\"plusonemeal[". $row['guestid'] ."]\" value=\"Beef\"> Beef
				  <input type = \"radio\" name=\"plusonemeal[". $row['guestid'] ."]\" value=\"Chicken\"> Chicken
				  <input type = \"radio\" name=\"plusonemeal[". $row['guestid'] ."]\" value=\"Salmon\"> Salmon
				  <input type = \"radio\" name=\"plusonemeal[". $row['guestid'] ."]\" value=\"Vegetarian\"> Vegetarian
				  <br/><br/>");
		}
		else
		{
			print("<br><input type=\"hidden\" name=\"isplusoneattending[" . $row['guestid'] ."]\" value=\"no\">");
			print("<br><input type=\"hidden\" name=\"plusonemeal[" . $row['guestid'] ."]\" value=\"\">");
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
		<?php require_once 'footer.php';?>
	</body>
</html>