<?php
	include "easeFunctions.php";
	include "databasehandler.php";
	session_start();
	include "ipstore.php";
	include "include/create.php";
	include "include/join.php";
?>
<!DOCTYPE html>
<html>
<head>
	<?php include "favicons.html";?>
	<meta name="viewport" content="user-scalable=no, initial-scale=1.0, width=device-width, maximum-scale=1.0, minimal-ui">
	<meta charset="UTF-8">
	<meta name="keyword" content="Annonymous Chat">
	<meta name="description" content="Talk Annonymously">
	<link rel='stylesheet' href='style.css'>
	<link rel='stylesheet' href='center.css'>
</head>

<body>
	<div id='getinfo1' class='background' onclick="document.getElementById('getinfo1').style.display='none';removeHash();" style='display:none;position:fixed;'>
		<div class='container'>
			<div class='content' onclick="document.getElementById('getinfo1').style.display='table';cancelProp(event);" style='width:100%;max-width:400px;height:auto;max-height:100%;overflow-y:auto;'>
				<div class='cross' onclick="document.getElementById('getinfo1').style.display='none';cancelProp(event);removeHash();"></div>
				<div class='paTB10' style='text-align:left;'>
					<?php include 'include/create2.php'; ?>
				</div>	
			</div>
		</div>
	</div>
	<div id='getinfo2' class='background' onclick="document.getElementById('getinfo2').style.display='none';removeHash();" style='display:none;position:fixed;'>
		<div class='container'>
			<div class='content' onclick="document.getElementById('getinfo2').style.display='table';cancelProp(event);" style='width:100%;max-width:400px;height:auto;max-height:100%;overflow-y:auto;'>
				<div class='cross' onclick="document.getElementById('getinfo2').style.display='none';cancelProp(event);removeHash();"></div>
				<div class='paTB10' style='text-align:left;'>
					<?php include 'include/join2.php'; ?>
				</div>	
			</div>
		</div>
	</div>
	


	
<div id="pagecover">	
	<div class='overflowcontrol paTRBL10 bggood brgood' style='font-size:40px;background:rgba(0,0,0,0.8);text-align:center;overflow:hidden;'>
		Instant, Secure and Anonymous Chat <br>
		Share texts, images and files...
	</div>
	<div id='pagecoverextra' style='text-align:center;overflow:hidden'>
		<div class='paTRBL10' style='text-align:center;'>
			<div style='display:inline-block;border:green solid 1px;max-width:500px;text-align:left;'>
				<div class='paTRBL10' style='float:left;'>
					<button style='min-width:250px; padding:20px;font-size:50px;' onclick='document.getElementById("getinfo1").style.display="table";window.location.hash="Create"'>Create<br>Portal
					</button>
				</div>
				<div class='paTRBL10' style="display:table-cell;vertical-align:top;">
					Create port if you're new or if you want to own it! Its easy, you just need a new portal name, and your username.
				</div>
			</div>
		
			<div style='display:inline-block;border:green solid 1px;max-width:500px;text-align:left;'>
				<div class='paTRBL10' style='float:left;'>
					<button style='min-width:250px; padding:20px;font-size:50px;' onclick='document.getElementById("getinfo2").style.display="table";window.location.hash="Join"'>Join<br>Portal
					</button>
				</div>
				<div class='paTRBL10' style="display:table-cell">
					Need to join the port started earlier? Get ready with portal address and click the 'Join Portal' button. Oh and remember! your username won't be same as earlier. We'll tweak it a little bit to maintain privacy and security. ENJOY!
				</div>
			</div>
		</div>
	</div>
</div>


<hr class='break'>
<?php include 'include/footer.php'; ?>


<script>
var hash=window.location.hash.substr(1).trim();
if(hash.length>0){
	if (hash=='Create') document.getElementById('getinfo1').style.display='table';
	if (hash=='Join')   document.getElementById('getinfo2').style.display='table';
}
function cancelProp(e){
	if (!e) var e = window.event;
	e.cancelBubble = true;
	if (e.stopPropagation) e.stopPropagation();
}
function removeHash(){
	//window.location.hash='';
	//history.replaceState('', document.title, window.location.pathname);
	history.pushState('', document.title, window.location.pathname + window.location.search);
}
</script>

</body>
</html>