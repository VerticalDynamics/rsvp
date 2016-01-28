<?php 
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