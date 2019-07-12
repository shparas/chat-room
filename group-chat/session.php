<?php 
	session_start();
	$_SESSION["visit"]=varsetcheck($_SESSION["visit"],0)+1;
	if ($_SESSION["visit"]==50){
		session_regenerate_id();
	}
?>