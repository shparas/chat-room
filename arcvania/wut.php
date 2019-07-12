<?php 
	include "easeFunctions.php";
	include "databasehandler.php";
	session_start();
	include "ipstore.php";
?>

<!DOCTYPE html>
<html>
<head>
	<meta name="viewport" content="user-scalable=no, initial-scale=1.0, width=device-width, maximum-scale=1.0, minimal-ui">
	<meta charset="UTF-8">
	<meta name="keyword" content="Seagull, Stranger's bay">
	<meta name="description" content="Connect with people of same mind">
	<?php include "favicons.html";?>
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
		<div class='linetabshead' onclick='tabclick(); this.className+=" linetabsheadactive"'>Donate</div>
		<div class='linetabsbody'>BODYGOeSHERE</div>
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