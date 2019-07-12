<?php 
	include_once "easeFunctions.php";
	include_once "databasehandler.php";
	session_start();
	include_once "ipstore.php";

	$ps=varsetcheck($_REQUEST['ps']);
	$mail=varsetcheck($_REQUEST['mail']);
	$id=trim(urldecode(varsetcheck($_REQUEST['id'])));
	$pin=varsetcheck($_REQUEST['pin'])+0;	if ($pin===0) $pin='';
	$confcode=varsetcheck($_REQUEST['confcode']);
	$newpassword=varsetcheck($_REQUEST['pswd']);
	$newrepassword=varsetcheck($_REQUEST['repswd']);
	$stat=varsetcheck($_REQUEST['stat']);
	$verification=false;
	$step=1;
	$email='';
	$error='';
	
	//1:ask user for email	2:verify then send the code, ask for resend or pin 	3:ask for password 4:Celebrate
	
	if (!($ps=='lost')){include_once "lostpage.php";exit();}  //kill if not forgot password
	
	//get email if there is one matching, give error if wrong else nothing
	if (strlen($id)>0){
		//get the valid email of id given by user
		if (!strpos(varsetcheck($id),'@'))
			$records=selectrecord("SELECT username, email FROM users WHERE username='$id'");
		else
			$records=selectrecord("SELECT username, email FROM users WHERE email='$id'");
		
		//check if email is there
		if($records[0][0]==1){
			$email=$records[1]['email'];
		}
		else{ 
			$error="Sorry! The username or email doesn't link to any user account. Please check your input.";
		}
	}

	
	//make files and email the users if its the first time
	if ($email!='' and $mail==1){
		//for file
		$conffile=$records[1]['username']."/".md5($email."Its lovely, ArcVania. ");
		$createdtime=time();
		$pin=rand(111111,999999);
		$pinattempts=0;
		$lastpattempt=0;
		$string="abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		$confcode='';
		for($x = 1; $x <= 30; $x += 1) {	//no. of characters
			$confcode.=$string[rand(0, strlen($string)-1)];
		}
		$confattempts=0;
		$lastcattempt=0;
		$string=$createdtime."_".$pin."_".$pinattempts."_".$lastpattempt."_".$confcode."_".$confattempts."_".$lastcattempt;
		file_put_contents($conffile,$string);
		$createdtime='';$pins='';$pinattempts='';$lastpattempt='';$confcode='';$confattempts='';$lastcattempt='';
		//EMAIL THE CONTENTS AFTER HERE;
		$step=2;
	}
	
	
	if ($email!='' and $mail!=1 and ($pin!='' or $confcode!='')){
		$conffile=$records[1]['username']."/".md5($email."Its lovely, ArcVania. ");
		if (file_exists($conffile)){
			$data=explode("_",file_get_contents($conffile));
			$time=time();
			$pinorig=$data[1];
			$pinattempts=$data[2];
			$lastpattempt=$data[3];
			$confcodeorig=$data[4];
			$confattempts=$data[5];
			$lastcattempt=$data[6];
			
			//for confid
			if(strlen($confcode)>0){
				if (time()-$lastcattempt>300){
					$confattempts=0;
					$data[5]=0;
					file_put_contents($conffile,implode("_",$data));
				}
				if($confattempts<16 and $confcode==$confcodeorig){
					$verification=true;
				}
				else if($confattempts>15){
					$error="Sorry, you've tried enough wrong links already. Please try back after ". ceil((300-(time()-$lastcattempt))/60) ." minutes.";
				}
				else{
					$data[5]+=1;
					$data[6]=time();
					file_put_contents($conffile,implode("_",$data));
					include_once "lostpage.php";exit();
				}
			}
			//for pin
			else if(strlen($pin)>0) {
				if (time()-$lastpattempt>300){
					$pinattempts=0;
					$data[2]=0;
					file_put_contents($conffile,implode("_",$data));
				}
				if($pinattempts<6 and $pin==$pinorig){
					$verification=true;
				}
				else if($pinattempts>5){
					$error="Sorry, you've entered enough wrong pins. Please try back after ". ceil((300-(time()-$lastpattempt))/60) ." minutes.";
				}
				else{
					$error="Sorry, you've entered wrong pin. Please try again.";
					$data[2]+=1;
					$data[3]=time();
					file_put_contents($conffile,implode("_",$data));
				}
			}
		}
		else{
			include_once "lostpage.php";exit();
		}
	}

	
	
	if ($email=='') $step=1; else $step=2;
	if ($email!='' and $mail==1) $step=2;
	if ($verification===true) $step=3;
	if ($stat=='done') $step=4;

	
	if($verification===true and $newpassword!=''){
		if(validate_pswd($newpassword)){
			if ($newpassword==$newrepassword){
				$sql = "UPDATE users SET password='$newpassword' WHERE email='$email'";
				updaterecord($sql);
				$step=4;
			}
			else {
				$error='Please enter matching passwords.';
				$step=3;
			}
		}
		else{
			$error=validate_pswd($newpassword);
			$step=3;
		}
	}
	
	
	
	
	
	
	
?>
<!DOCTYPE html>
<html>
<head>
	<title>Password Reset</title>
	<?php include "favicons.html";?>
	<meta name="viewport" content="user-scalable=no, initial-scale=1.0, width=device-width, maximum-scale=1.0, minimal-ui">
	<meta charset="UTF-8">
	<meta name="description" content="Content not available.">
	<link rel='stylesheet' href='/style.css'>
</head>
<body>
<h1 class='maTRL10 maB5 paTRBL10 bggoodhead brgood center'>


<?php 
	$error=trim($error);
	if ($error!='') echo "Sorry something went wrong!";

	if ($step==1 and $error=='') echo "Oopsie! Forgot your password?";	
	if ($step==2 and $error=='') echo "Nearly done!";
	if ($step==3 and $error=='') echo "And finally...";
	if ($step==4 and $error=='') echo "Phew! That wasn't hard was that?";
?>

</h1>
<div class='maRL10 paTRBL10 bggood brgood' style='overflow:hidden; margin-bottom:0px;'>
	<div style='max-width: 500px; display:inline-block;'>
		<h3 class='overflowcontrol'>


		<?php 
			if ($step==1) echo "Step 1: Getting to know your account!";	
			if ($step==2) echo "Step 2: Expecting you to check your mail and respond accordingly!";
			if ($step==3) echo "Step 3: Changing the forgotten password!";
			if ($step==4) echo "Step 4: Done!";
			echo '</h3>';
			if ($error!='') echo "<br><p class='errortext maT0 paT0'>$error</p>";
		?>

		
	</div>
</div>
<center>
<div class='maRBL10 paTRBL5 bggood brgood center' style='overflow:hidden; margin-top:0px; max-width: 400px; border-top-left-radius:0; border-top-right-radius:0;'>

		<p class='overflowcontrol'>
		
		<?php 
			if ($step==1) echo "Please enter your username or email address of your account. We will use this information to get/verify your email. We'll then email you with the confirmation code or pin that will lead us to next step.";	
			if ($step==2) echo "Great! We just sent you an email containing password reset link or 6-digit pin, use whichever you find easy. Oh and don't forget to check the junk folder. Wait for few moments and if you don't see you mail anywhere, click the resend button below. If you wish to reset using pin, enter the 6-digit pin in the box.";
			if ($step==3) echo "Please enter the new password for your account.";
			if ($step==4) echo "Yay! You may start using your account with the new password. <a href='/'>Click here</a> to go to sign in page.";			
		?>
		
		</p><br>	
		<form>
			<input type='hidden' name='flost' value='true'>
		<?php 
			if ($step==1){
				echo "<input class='fullw' type='text' placeholder='Username or Email' name='id' value='";
				if(!strpos(varsetcheck($id),' ') and !strpos(varsetcheck($id),"'") and !strpos(varsetcheck($id),'"')) echo "$id";
				echo "'>";
				echo "<input type='hidden' name='mail' value='1'>";
			}
			else if ($step==2){
				echo "<input type='hidden' name='id' value='$id'>";
				echo "<input class='fullw' type='text' placeholder='Pin' name='pin' value='$pin'>";
			}
			else if ($step==3){
				echo "<input class='fullw' type='text' placeholder='Password' name='pswd' value=''>";
				echo "<input class='fullw' type='text' placeholder='Retype Password' name='repswd' value=''>";
				echo "<input type='hidden' name='id' value='$id'>";
				echo "<input type='hidden' name='pin' value='$pin'>";
				if ($confcode!='') echo "<input type='hidden' name='confcode' value='$confcode'>";
			}
			else {}
		?>
		<br><input class='button' type='submit' value='Next'>
		
	</form>
	
</div>
</center>



<hr class=break>
<?php include 'footer.php'; ?>
</body>
</html>
	