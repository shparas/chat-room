<?php 
	include_once "easeFunctions.php";
	include_once "databasehandler.php";
	include_once "ipstore.php";
?>
<!DOCTYPE html>
<html>
<head>
	<title>Sorry!</title>
	<?php include "favicons.html";?>
	<meta name="viewport" content="user-scalable=no, initial-scale=1.0, width=device-width, maximum-scale=1.0, minimal-ui">
	<meta charset="UTF-8">
	<meta name="description" content="Content not available.">
	<link rel='stylesheet' href='/style.css'>
</head>
<body>
<h1 class='maTRL10 maB5 paTRBL10 bggoodhead brgood center'>
	Page Content Not Available!
</h1>
<div class='maRBL10 paTRBL5 bggood brgood center' style='overflow:hidden'>
	<img src="/images/error.png" style='display:inline-block; width: 125px; height: 125px;'><br>
	<div style='max-width: 500px; display:inline-block; block; margin:auto 0 auto 0;'>
		<p class='overflowcontrol'>
			Awww, it looks like you are lost. The content at the <a href='<?php echo 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];?>'><?php echo $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];?></a> is not available. It might be because there is no such page or you might not have enough permission to access this page. We are extremely sorry for your inconvinience.
		</p><br>
		
		<p class='overflowcontrol'>
		Check the adress of the page you are trying to access.<br>If you think you aren't supposed to see this, don't forget to email us at <a href='mailto:support@arcvania.com?Subject=Content%20Not%20Available'>support@arcvania.com</a> to let us know. We will see what we can do.
		</p><br>
	</div>
</div>
<hr class=break>
<?php include 'include/footer.php'; ?>
</body>
</html>
<?php exit(); ?>