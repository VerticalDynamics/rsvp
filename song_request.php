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
    <h1>Request A Song</h1>
    <p>Vote for another person's song request <i>(hover over a song on the list below)</i> or request your own <i>(right-side of the page)</i>.</p>
    <p><strong>Note:</strong> You may request as many songs as you like but you cannot request any song more than once.</p>
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
?>
    <div class="row">
      <div class="eight columns song-request-column">
        <h2>Most Requested Songs So Far...</h2>
<?php 
	$query = "select count(1) as request_count, song_request.* from song_request group by song_artist, song_title order by request_count desc, song_request_id desc limit 20";
	$stmt = $conn->prepare($query);
	$stmt->bindParam(':guestid', $guestid);
	$stmt->execute();  
?>
        <form action="song_request.php" method="post">
          <table>
            <tr>
              <td>
                <strong>Rank</strong>
              <td>
              <td>
                <strong>Song Artist</strong>
              <td>          
              <td>
                <strong>Song Title</strong>
              <td>
              <td>
              <td>
            </tr>
<?php
  $rank = 1;
  while ( $row = $stmt->fetch() )
  {
    $song_request_id = $row['song_request_id'];
    $button_id = 'request_button' . $song_request_id;
?>
            <tr onmouseover="<?php echo $button_id ?>.style.visibility = 'visible'" onmouseout="<?php echo $button_id ?>.style.visibility = 'hidden'">
              <td><?php echo $rank++ ?>.<td>
              <td><?php echo $row['song_artist'] ?><td>          
              <td><?php echo $row['song_title'] ?><td>
              <td>
                  <input id="<?php echo $button_id ?>" type="button" value="Request" class="button-primary" style="visibility:hidden" >
                  <input id="song_request_id" type="hidden" value="<?php echo $song_request_id ?>">
              </td>
            </tr>
<?php 
  }
?>
          </table>
        </form>
      </div>
      <div class="four columns song-request-column">
        <h2>Make Your Own Request</h2>
        <form action="song_request.php" method="post">
          <select name="song_requester" class="form-input">
            <option value="" selected default disabled>Song requested by...</option>
<?php
	$query = "select * from guest where groupname = (select groupname from guest where guestid = :guestid limit 1) and isattending = 'y';";
	$stmt = $conn->prepare($query);
	$stmt->bindParam(':guestid', $guestid);
	$stmt->execute();
  while ( $row = $stmt->fetch() )
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

          <button type="submit" class="button-primary">Request This Song</button>
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
