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
  <div id="main" class="container">
  	<?php require_once 'partials/menu.php'; ?>

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
    <div>
    column to the right
      <form action="song_request.php" method="post">
        Song requested by 
        <select name="song_requester">
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
        <input type="text" value="Song Artist" onfocus="this.value=''" onfocusout="if(!this.value) this.value='Song Artist'"></input>
        <input type="text" value="Song Title" onfocus="this.value=''" onfocusout="if(!this.value) this.value='Song Title'"></input>
        <br>
        <button type="submit" class="button-primary">Request Song</button>
      </form>
    <div>
    <div>
      column to the left 
      <h2>Songs Requested So Far...</h2>
      Upvote another song 
      
      Songs submitted so far:
        
      *table ordered by submission order, request count*
    </div>
  </div>
<?php
}
?>
	<?php require_once 'partials/footer.php';?>
</body>
</html>
