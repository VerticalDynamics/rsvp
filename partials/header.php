<?php
header("Content-Security-Policy: script-src 'self'; style-src 'self' https://fonts.googleapis.com; frame-src https://www.google.com; form-action 'self'");

if ( !session_start() )
{
    die("Couldn't start session.");
}
else if ( !isset($_SESSION['isLoggedIn']) )
{
	header('location:index.php');
	exit();
}
?>
