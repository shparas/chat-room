<?php 
	include "easeFunctions.php";
	include "databasehandler.php";
	session_start();
	include "ipstore.php";
?>
<!DOCTYPE html>
<html>
<head>
	<?php include "favicons.html";?>
	<meta name="viewport" content="user-scalable=no, initial-scale=1.0, width=device-width, maximum-scale=1.0, minimal-ui">
	<meta charset="UTF-8">
	<meta name="keyword" content="ArcVania, Share Ideas, Connect to People">
	<meta name="description" content="Connect to people of same interests and share ideas publicly.">
	<link rel='stylesheet' href='/style.css'>
</head>

<body>
<div id="pagecover">
	<div id='pagecovercontent1' class='bggood center'>
		Unfortunately, this site is still under construction and we hope you don't mind. We'll complete it asap. But, Hey! Nice to see you. Keep visiting us.
	</div>
	<div  id='pagecovercontent2' class='paTRBL10' style='overflow:hidden'>
		<div id='signinbar' class='maL5 bggood brgood' style='float:right;max-width:300px;width:100%;overflow:hidden;box-sizing:border-box;'>
			<?php include("sign_in.php");?>
		</div>
		<div id='pagecoverextra' class='paTRBL10 bggood brgood' style='text-align:center;display:none;width:auto; height:auto;box-sizing:border-box;overflow:hidden'>
			<script>
			//control only using javascript; set the display to table-cell;
				document.body.onload=function(){
					if(parseInt(document.body.clientWidth)>500) document.getElementById('pagecoverextra').style.display='block';
					document.getElementById('pagecoverextra').style.height=(document.getElementById('signinbar').offsetHeight)+'px';
				}
			</script>
			<div class='overflowcontrol' style='font-size:40px'>
				UNDER CONSTRUCTION...
			</div>
		</div>
	</div>
</div>

<?php include("headerbar.php");?>

<div id="page">
	<?php 
		if($_SESSION["signedin"]!=1){
			include "bodyfornew.php";
		}else{
			include "bodyforuser.php";
		}
	?>
</div>

<hr class='break'>
<?php include 'footer.php'; ?>
<script src="script.js"></script>
</body>
</html>