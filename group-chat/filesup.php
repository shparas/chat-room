<?php
include "easeFunctions.php";

if(isset($_POST["submit"]) AND isset($_FILES["file"])){
	$filename=$_FILES["file"]["name"];
	$extension = end((explode(".", $filename))); //file extension
	//$fileType = pathinfo($filename,PATHINFO_EXTENSION);
	$fileid=rand(1111,9999).time();
	$target_file = "files/uploads/".$fileid.".file";
	
	$error='';

	// Check file size
	/*$check = filesize($_FILES["file"]["tmp_name"]);
	if($check > 50000000) {
		$error="ERROR: File can't be more than 50 MB.";
		echo $error;
		exit();
	}*/
	if ($_FILES["file"]["size"] > 50000000) {
		$error= "ERROR: File can't be more than 50 MB.";
		echo $error;
		exit();
	}
	
	
	if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {	//after upload
		echo "fileid=$fileid";
		unset($_FILES);
		exit();
	}else{
		echo "ERROR: Sorry, something went wrong.";
		exit();
	}
}

?>