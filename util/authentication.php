<?phprequire_once 'db.php';$db = new Database();$conn = $db->openDB();if ($conn == null)	die();$query = "SELECT guestid, guestname, isheadofhousehold, isconfirmed from guest where guestpassword = :guestpassword limit 1;";$stmt = $conn->prepare($query);$guestpassword = $_POST['guestpassword'];$stmt->bindParam('guestpassword', $guestpassword);$stmt->execute();$row = $stmt->fetch();$loginSuccessful = ($stmt->rowCount() == 1);$db->closeDB();if ($loginSuccessful && session_start() ) {	$_SESSION['isLoggedIn'] = $loginSuccessful;	$_SESSION['guestid'] = $row['guestid'];	$_SESSION['isconfirmed'] = $row['isconfirmed'];	$_SESSION['guestname'] = $row['guestname'];	$_SESSION['isheadofhousehold'] = $row['isheadofhousehold'];  // configuration options: 	$_SESSION['ENABLE_MEAL_SELECTION'] = false; //some users will not want meal selection as an RSVP option	header('location:../welcome.php');}else { // write to log-in attempt table on log-in failure  $conn = $db->openDB();  $query = "INSERT INTO login_attempt (user_ip_address, guestpassword, login_attempt_date) VALUES (:user_ip_address, :guestpassword, now());";  $stmt = $conn->prepare($query);  $stmt->bindParam('user_ip_address', $_SERVER['REMOTE_ADDR']);  $stmt->bindParam('guestpassword', $guestpassword);  $stmt->execute();  $db->closeDB();  	header('location:../index.php');}?>