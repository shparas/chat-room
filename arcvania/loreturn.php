<?php 
	//last online giver, send data either by get or post but with 'usr' as name
	
	include "databasehandler.php";
	include "easeFunctions.php";

	$user=varsetcheck($_REQUEST["usr"]);
	
	if (strlen($user+1-1)!=strlen($user)){	//$user is just a id and not user name
		$user=getid($user);
	}

	echo loreturn($user);
?>