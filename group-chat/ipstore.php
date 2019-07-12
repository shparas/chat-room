<?php
	$ip=getIP();
	$date=time();
	$page=htmlspecialchars($_SERVER["PHP_SELF"]);
	$roomname=varsetcheck($_SESSION["roomname"]);
	$username=varsetcheck($_SESSION["username"]);
	$sql = "INSERT INTO _ipstore (ip, date, page, roomname, username) 
			VALUES ('$ip', '$date', '$page', '$roomname', '$username')";
	$result=singlequery($sql);
?>