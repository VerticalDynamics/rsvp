<?php
require_once 'partials/header.php';
require_once 'util/db.php';
?>
<!DOCTYPE html>
<html>
<head>
  <title>Natalie + Nic | Song Request</title>
  <?php require_once 'partials/doc_header.php';?>
</head>
<body id="song-request">
  <?php require_once 'partials/menu.php'; ?>

  <div id="main" class="container">
    <h1>Request-A-Song</h1>
<?php
if ($_SESSION['isconfirmed'] != 1)
{ // the song request form appears once the RSVP is completed successfully ?>
    <p>Before requesting a song, please complete the <a href="rsvp_start.php">RSVP form</a>.</p>
<?php
}
else
{
  $isconfirmed = $_SESSION['isconfirmed'];
  $guestid = $_SESSION['guestid'];
	$db = new Database();
  $conn = $db->openDB();
	$query = "select * from guest where groupname = (select groupname from guest where guestid = :guestid limit 1) and isattending = 'y';";
	$stmt = $conn->prepare($query);
	$stmt->bindParam(':guestid', $guestid);
	$stmt->execute();
?>
    <div class="row">
      <div class="eight columns song-request-column">
        <h2>Songs Requested So Far...</h2>
        Upvote another song

        <p>Songs submitted so far:</p>
        *unordered list of songs*

        *table ordered by submission order, request count*
      </div>
      <div class="four columns song-request-column">
        <form action="song_request.php" method="post">
          <select name="song_requester" class="form-input">
            <option value="" selected default disabled>Song requested by...</option>
<?php
  while ($row = $stmt->fetch())
	{
?>
            <option value="<?php echo $row['guestid']?>"><?php echo $row['guestname']?></option>
<?php
	}
	$db->closeDB();
?>
          </select>

          <input type="text" placeholder="Song Artist" class="form-input">
          <input type="text" placeholder="Song Title" class="form-input">

          <button type="submit" class="button-primary">Request A Song</button>
        </form>
      </div>
    </div>
  </div>
<?php
}
?>
	<?php require_once 'partials/footer.php';?>
</body>
</html>
