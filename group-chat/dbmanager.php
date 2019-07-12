<?php
	include_once "easeFunctions.php";
	
	if(!file_exists('dbinfo.txt')){
		$string='~_~_~_';
		file_put_contents('dbinfo.txt',$string);
	}
	if (isset($_POST['Change'])){
		$dbservername=$_POST['servername'];
		$dbuser=$_POST['user'];
		$dbpass=$_POST['pass'];
		$dbname=$_POST['dbname'];
		$string=$dbservername.'~_'.$dbuser.'~_'.$dbpass.'~_'.$dbname;
		file_put_contents('dbinfo.txt',$string);
	}
	$string=explode("~_",file_get_contents('dbinfo.txt'));			
	$dbservername=$string[0];
	$dbuser=$string[1];
	$dbpass=$string[2];
	$dbname=$string[3];
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
</head>

<body>
<div style='text-align:center;margin:3% auto 0 auto;'>
	<div class='paTRBL10' style='display:inline-block;text-align:left;'>
		<div class='paTRBL10 maB5 bggood brgood' style='display:block;text-align:center;font-size:25px;'>
			Database Manager
		</div>
		<div class='bggood brgood paTRBL10'>
			<table>
				<form method='POST' action=''>
				<tr>
					<td style='width:100px;'>Server Name:</td>
					<td><input type='text' name='servername' value='<?php echo $dbservername; ?>' placeholder='Server Name' class='block'></td>
				</tr>
				<tr>
					<td>Uername:</td>
					<td><input type='text' name='user' value='<?php echo $dbuser; ?>' placeholder='User Name' class='block'></td>
				</tr>
				<tr>
					<td>Password:</td>
					<td><input type='password' name='pass' value='<?php echo $dbpass; ?>' placeholder='Password' class='block'></td>
				</tr>
				<tr>
					<td>Database Name:</td>
					<td><input type='text' name='dbname' value='<?php echo $dbname; ?>' placeholder='Database Name' class='block'></td>
				</tr>
				<tr>
					<td colspan=2><input type='submit' name='Change' value='Change' class='block'>
				</tr>
				</form>
			</table>
		<?php if (isset($_POST['Change'])){ ?>
			<div class='bggood paTRBL5' style='text-align:center;font-size:25px;'>
				Changes Saved!
			</div>
		<?php } ?>
		</div>
		<hr class='break'>
		<?php include 'include/footer.php'; ?>
	</div>
</div>

</body>
</html>
<?php exit(); ?>