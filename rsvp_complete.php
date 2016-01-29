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
	<body>
		<?php require_once 'partials/menu.php'; ?>
		<h1>Submission Complete</h1>
		<br>
		<?php
		$isconfirmed = $_SESSION['isconfirmed'];
		$guestid = $_SESSION['guestid'];
		$db = new Database();
		$conn = $db->openDB();
		if ($isconfirmed == 0)
		{
			if ($guestid != null)
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
		}
		else
		{
			$query = "select * from guest where groupname = (select groupname from guest where guestid = :guestid limit 1)";
			$stmt = $conn->prepare($query);
			$stmt->bindParam(':guestid', $guestid);
			$stmt->execute();

			echo "The following invitees have successfully RSVP'ed: <br><br>";

			while ($row = $stmt->fetch())
			{
				echo $row['guestname'] . " ";
				$not = ($row['isattending'] == "n" ? "not" : "");
				echo " is " . $not . " attending";
				if ($row['isplusoneattending'] == 'y')
					echo " (and will be bringing a guest).";
				else
					echo ".";
				echo "<br>";
			}
			echo "<br>";
			$db->closeDB();
		}
		?>

		<p>If you need to change your selection, please email nkoutros@googlemail.com</p>
		<br>
		<form action="welcome.php" method="post">
			<input type="submit" value="You're Done, Thanks! Return to Main Page">
		</form>
		<?php require_once 'partials/footer.php';?>
	</body>
</html>
