<?php 
	//last online update just call it and it'll be done if user is in session thing
	
	include_once "databasehandler.php";
	include_once "easeFunctions.php";
	session_start();
	
	$user=varsetcheck($_SESSION["id"]);
	loupdate($user);	
?>