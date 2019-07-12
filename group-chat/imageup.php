<?php
include "easeFunctions.php";

if(isset($_POST["submit"]) AND isset($_FILES["file"])){
	
	$sendtype=strtolower(varsetcheck($_REQUEST['sendtype']));
	$sendtype="";
	
	$currtime=rand(1111,9999).time();
	$target_file = "images/uploads/".$currtime.".jpg";
	
	$error="";
	$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
		
	// Check if image file is a actual image or fake image
	$check = getimagesize($_FILES["file"]["tmp_name"]);
	if($check === false) {
		$error="ERROR: File is not an image. Please upload a valid one.";
		echo $error;
		exit();
	}
	// Check file size
	if ($_FILES["file"]["size"] > 5000000) {
		$error= "ERROR: Sorry, your image is too large.";
		echo $error;
		exit();
	}
	//Allow certain file formats
	if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
		$error= "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
		exit();
	}

	
	if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {	//after upload
		if ($sendtype=='userprofile'){			//copy to main folder as well for profile pictures
			$target_file=$_SESSION["username"]."/"."profilepic.jpg";
			if (file_exists($target_file)){
				unlink($targetfile);
			}
			move_uploaded_file($_FILES["file"]["tmp_name"], $target_file);
		}
		echo "$currtime";
		unset($_FILES["file"]["tmp_name"]);
	}else{
		$error="ERROR: Sorry, something went wrong.";
		echo $error;
		exit();
	}
}

?>