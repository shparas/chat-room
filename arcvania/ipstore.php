<?php
	$ip=getIP();
	$date=time();
	$page=htmlspecialchars($_SERVER["PHP_SELF"]);
	$user=varsetcheck($_SESSION["username"]);
	$sql = "INSERT INTO ipstore (ip, date, page, user) 
			VALUES ('$ip', '$date', '$page', '$user')";
	$result=singlequery($sql);
?>