<?php 
	include "easeFunctions.php";
	include "databasehandler.php";
	include "session.php";
	include "ipstore.php";
?>
<!DOCTYPE html>
<html>
<head>
	<?php include "favicons.html";?>
	<meta name="viewport" content="user-scalable=no, initial-scale=1.0, width=device-width, maximum-scale=1.0, minimal-ui">
	<meta charset="UTF-8">
	<meta name="keyword" content="Seagull, Stranger's bay">
	<meta name="description" content="Connect with people of same mind">
	<style>
		
	</style>
	<link rel="stylesheet" href="style.css">
</head>

<body>
<div id="applicationcover">
		<?php include("sign_in.php"); ?>
</div>

<?php include("headerbar.php");?>

<div id="mainbody">
<div id="onmiddle">
	<div class="linetabs">
		<div class='linetabshead' onclick='tabclick(); this.className+=" linetabsheadactive"'>Set Display Image</div>
		<div class='linetabsbody'>
			<form action='<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>' method="POST" enctype="multipart/form-data" name="myForm">
				<input type="file" name="fileToUpload" id="fileToUpload" style="display:none;" onchange="filesent(this)">
				<div id="uploadfilebtn" onclick="document.getElementById('fileToUpload').click()"><b>Click to select image</b></div>
				<input type="submit" style="display:none;" id='sub' name="submit" value="Upload Image">
			</form>

			<?php 
				$target_file = $_SESSION["username"]."\\"."profilepic.jpg";
				if(isset($_POST["submit"])){
					$uploadOk = 1;
					$error="";
					$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);

					// Check if image file is a actual image or fake image
					$check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
					if($check === false) {
						$error="File is not an image. Please upload a valid one.";
						$uploadOk = 0;
					}

					// Check file size
					if ($_FILES["fileToUpload"]["size"] > 5000000) {
						$error= "Sorry, your image is too large.";
						$uploadOk = 0;
					}

				/*	//Allow certain file formats
					if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
						$error= "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
						$uploadOk = 0;
					} */
					
					// Check if $uploadOk is set to 0 by an error
					if ($uploadOk !== 0) {
						if (file_exists($target_file)) unlink($target_file);
						if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
							//after upload'
							//echo "<div id='propic1'><img src='$target_file'></div>";
						}
					}
					
					//prints error if any
					echo "$error <br>";
				}
				if (file_exists($target_file)) echo "<div id='propic1'><img src='$target_file'></div>";
			?>
		</div>
	</div><div class="linetabs">
		<div class='linetabshead' onclick='tabclick(); this.className+=" linetabsheadactive"'>Advertise About Us</div>
		<div class='linetabsbody'>BODYGOeSHERE</div>
	</div><div class="linetabs">
		<div class='linetabshead' onclick='tabclick(); this.className+=" linetabsheadactive"'>Advertise With Us</div>
		<div class='linetabsbody'>BODYGOeSHERE</div>
	</div><div class="linetabs">
		<div class='linetabshead' onclick='tabclick(); this.className+=" linetabsheadactive"'>Report Bug</div>
		<div class='linetabsbody'>BODYGOeSHERE</div>
	</div>
	<div class="linetabs">
		<div class='linetabshead' onclick='tabclick(); this.className+=" linetabsheadactive"'>About Us</div>
		<div class='linetabsbody'>BODYGOeSHERE</div>
	</div>
	<div class="linetabs">
		<div class='linetabshead' onclick='tabclick(); this.className+=" linetabsheadactive"'>Contact Us</div>
		<div class='linetabsbody'>BODYGOeSHERE</div>
	</div>
	<div class="linetabs">
		<div class='linetabshead' onclick='tabclick(); this.className+=" linetabsheadactive"'>Give Feedbacks</div>
		<div class='linetabsbody'>BODYGOeSHERE</div>
	</div>
</div>

<div id="rightbar">  
</div>

</div>
<script src="script.js"></script>
<script>
function tabclick(){
	var ele=document.getElementsByClassName('linetabshead');
	for(var a=0; a<ele.length; a++){
			ele[a].className="linetabshead";
	}
}
</script>
</body>
</html>