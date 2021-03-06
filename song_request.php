<?php
require_once 'partials/header.php';
require_once 'util/db.php';

$song_request_successful = false;
$song_request_error = false;
$db = new Database();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $conn = $db->openDB();
  $stmt = null;
  if ( isset($_POST['song_request_id']) ) { // guest requests someone's else's song
    $request_someone_elses_song_query =
      'INSERT INTO song_request (requested_by_guest_id, song_artist, song_title) SELECT :guestid, song_artist, song_title FROM song_request WHERE song_request_id = :song_request_id limit 1';
    $stmt = $conn->prepare($request_someone_elses_song_query);
    $stmt->bindParam(':song_request_id', $_POST['song_request_id']);
    $stmt->bindParam(':guestid', $_SESSION['guestid']);
  }
  else if ( isset($_POST['song_requester_id']) ) { // guest requests their own song 
    $DB_FIELD_CHAR_LIMIT = 64;
    $request_your_own_song_query =
      'INSERT INTO song_request (requested_by_guest_id, song_artist, song_title) VALUES (:song_requester_id, :song_artist, :song_title)';
    $stmt = $conn->prepare($request_your_own_song_query);
    $song_artist = filter_input(INPUT_POST, 'song_artist', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW);
    $song_artist = ucwords( strtolower( substr($song_artist, 0, $DB_FIELD_CHAR_LIMIT) ) );
    $song_title = filter_input(INPUT_POST, 'song_title', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW);
    $song_title = ucwords( strtolower( substr($song_title, 0, $DB_FIELD_CHAR_LIMIT) ) );
    $stmt->bindParam(':song_requester_id', $_POST['song_requester_id']);
    $stmt->bindParam(':song_artist', $song_artist);
    $stmt->bindParam(':song_title', $song_title);
  }
  try {
    if ($stmt)
      $stmt->execute();
  }
  catch (PDOException $e) { // reject repeat song requests for same song by same guest (primary key is requested_by_guest_id, song_artist, song_title)
    $song_request_error = true;
  }  
  $song_request_successful = true;
  $db->closeDB();
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Natalie + Nic | Song Request</title>
  <?php require_once 'partials/doc_header.php';?>
</head>
<body id="song-request">
  <?php require_once 'partials/menu.php'; ?>

  <div id="main">
    <div class="container">
    <h2>Request a Song &#9834;</h2>
<?php
if ($_SESSION['isconfirmed'] != 1) { // the song request form appears once the RSVP is completed successfully ?>
    <p>Before requesting a song, please complete the <a href="rsvp_start.php">RSVP form</a>.</p>
    <p>Once you have RSVP'ed, check back here for the most requested songs, ranked by popularity.<br> Request your own song or one that's already been requested by others.</p>
    <p>See you on the dancefloor!</p>
<?php
}
else {
  $isconfirmed = $_SESSION['isconfirmed'];
  $guestid = $_SESSION['guestid'];
  $conn = $db->openDB();
  if ($song_request_error) {
?>
    <p class="alert failure text-center">There was error in your song request. Please ensure you're not requesting the same song twice and try again.</p>
<?php
  }
  else if ($song_request_successful) {
?>
    <p class="alert success text-center">We successfully received your song request. Thanks!</p>
<?php  
  } else {
?>
    <p class="alert"><strong>Note:</strong> You may request as many songs as you like but you cannot request the same song more than once.</p>
<?php
  }
?>
    <div class="row">
      <div class="four columns song-request-column">
        <h4>Submit your request</h4>

        <form action="song_request.php" method="post">
          <select name="song_requester_id" class="form-input" required>
            <option value="" selected default disabled>Song requested by...</option>
<?php
	$query = "select * from guest where groupname = (select groupname from guest where guestid = :guestid limit 1) and isattending = 'y';";
	$stmt = $conn->prepare($query);
	$stmt->bindParam(':guestid', $guestid);
	$stmt->execute();
  while ( $row = $stmt->fetch() ) {
?>
            <option value="<?=$row['guestid']?>"><?=$row['guestname']?></option>
<?php
	}
	$db->closeDB();
?>
        </select>

        <input type="text" name="song_artist" placeholder="Song Artist" class="form-input" required>
        <input type="text" name="song_title" placeholder="Song Title" class="form-input" required>

        <button type="submit" class="button-primary">Request This Song</button>
      </form>
    </div>

    <div class="eight columns song-request-column">
      <h3>Most requested songs so far</h3>
      <p class="alert">To choose a song that is ranked below, hover over the song and click 'Request'.</p>
      <table id="song-request-table">
        <thead>
          <tr>
            <th>Rank</th>
            <th>Song Artist</th>
            <th colspan="2">Song Title</th>
          </tr>
        </thead>
        <tbody>
<?php
	$query = "select count(1) as request_count, song_request.* from song_request GROUP BY song_artist, song_title ORDER BY request_count desc, song_request_id desc limit 20";
	$stmt = $conn->prepare($query);
	$stmt->bindParam(':guestid', $guestid);
	$stmt->execute();
  $rank = 1;
  while ( $row = $stmt->fetch() ) {
    $song_request_id = $row['song_request_id'];
    $button_id = 'request_button' . $song_request_id;
?>
          <tr>
            <td><?=$rank++ ?></td>
            <td><?=$row['song_artist'] ?></td>
            <td><?=$row['song_title'] ?></td>
            <td>
              <form action="song_request.php" method="post">
                <button id="<?=$button_id ?>" type="submit" class="button-primary request-button">Request</button>
                <input id="song_request_id" name="song_request_id" type="hidden" value="<?=$song_request_id ?>">
              </form>
            </td>
          </tr>
<?php
  }
?>
        </tbody>
      </table>
    </div>
<?php
}
?>
  </div>
  </div>
  </div>
	<?php require_once 'partials/footer.php';?>
</body>
</html>
