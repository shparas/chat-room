<?php 
//has a inside block for sign in purpose.
//lets begin the code
	$signinid = varsetcheck($_POST["signinid"]);
	$signinpass = varsetcheck($_POST["signinpass"]);
	$signin = varsetcheck($_POST["signin"]);
	$remember=1; 
	
	if($signin=="Sign In!"){
		if (varsetcheck($_POST['remember'])==0) $remember=0;
		if(strpos($signinid, "@")!==false){ $type="email"; }
		else{ $type="username";}
		
		if((validate_usrname($signinid)===true || validate_email($signinid)===true) && validate_pswd($signinpass)===true){
			$sql = "SELECT id, fname, lname, email, country, username, password, dob, sex, type, status FROM users WHERE $type='$signinid' AND password='$signinpass'";
			$records = selectrecord($sql);
			if ($records[0][0]==1){			//get to index as member if record is there
				$_SESSION["id"]=$records[1]["id"];
				$_SESSION["fname"]=$records[1]["fname"];
				$_SESSION["lname"]=$records[1]["lname"];
				$_SESSION["username"]=$records[1]["username"];
				$_SESSION["email"]=$records[1]["email"];
				$_SESSION["country"]=$records[1]["country"];
				$_SESSION["dob"]=$records[1]["dob"];
				$_SESSION["sex"]=$records[1]["sex"];
				$_SESSION["type"]=$records[1]["type"];
				$_SESSION["status"]=$records[1]["status"];
				$_SESSION["signedin"]=1;
				if ($remember==1){		//SET COKIE HERE
				
				
						//SET COOKIE here
				
				
				}
				header('Location: ');
			}
			else{
				$error["signinid"]["class"]="formerror";
				$error["signinpass"]["class"]="formerror";
				$error["1"]["1"]="Wrong $type and password!";
				//$records = selectrecord("SELECT id FROM users WHERE $type='$signinid'");
				//if($records[0][0]==1) {
				//	$error["signinid"]["class"]="";
				//	$error["1"]["1"]="Wrong password!";
				//}
			}
		}
		else{
			$error["signinid"]["class"]="formerror";
			$error["signinpass"]["class"]="formerror";
			$error["1"]["1"]="Wrong $type and password!";
			//$records = selectrecord("SELECT id FROM users WHERE $type='$signinid'");
			//if($records[0][0]==1) {
			//	$error["signinid"]["class"]="";
			//	$error["1"]["1"]="Wrong password!";
			//}
		}
	}
	if(varsetcheck($_SESSION["signedin"])==0){
?>
		<div class='paTRBL10' style='overflow:hidden'>
			<form id="signin" method="POST" action="<?php echo htmlspecialchars(str_ireplace("index.php","",$_SERVER["PHP_SELF"]));?>">
				
				<?php if (varsetcheck($error["1"]["1"])){ ?> <div class="errortext block paTRBL3"><?php echo $error["1"]["1"];?></div>	<?php } ?>
								
				<input type="text" name="signinid" value="<?php echo varsetcheck($signinid);?>" placeholder="EmailID or Username" autofocus class='<?php echo varsetcheck($error["signinid"]["class"]);?>  block paTRBL5'>
				<input type="password" name="signinpass" value="<?php echo varsetcheck($signinpass);?>" placeholder="Password" class='<?php echo varsetcheck($error["signinpass"]["class"]);?> block paTRBL5'>
				
				<a class='paT5 left' href='fpass.php?ps=lost<?php if (varsetcheck($signinid)) echo "&id=".urldecode(varsetcheck($signinid));?>'>Forgot Password?</a><br>
				
				<div class='block right'>
					<input class='paTRBL10' id='remember' type="checkbox" name="remember" value="1" <?php if ($remember) echo 'checked';?>><label for="remember">Remember</label>
				</div>
				<input class='block right' type="submit" name="signin" value="Sign In!">
			</form>
		</div>
	</dsiv>
<?php 	
	}
?>