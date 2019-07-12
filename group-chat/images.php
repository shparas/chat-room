<?php
	$id=varsetcheck($_REQUEST['id']);
	$sql='SELECT id, filetype FROM _IMAGES WHERE id='.$id;
	$result=selectrecords($sql);
	if ($result[0][0]!=1) include ('lostpage.php');
	$path="images/uploads/$id.".$result[0]['filetype'];
	if (file_exists($path)){
		header('Content-type:image/png');
		readfile($path);
	} else include("lostpage.php");
?>