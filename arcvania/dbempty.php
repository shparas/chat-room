<?php 	
	include "easeFunctions.php";
	include "databasehandler.php";
	
	$sql="TRUNCATE users"; singlequery($sql);
	$sql="TRUNCATE ipstore"; singlequery($sql);
	$sql="TRUNCATE lastonline"; singlequery($sql);
?>