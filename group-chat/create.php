<?php
//has a block of 300px for sign up purpose.
//lets begin the code
	//preventing the emptiness of variable
	$roomname=trim(varsetcheck($_POST["roomname"]));
	$usepassword=varsetcheck($_POST["usepassword"]);
	$password=varsetcheck($_POST["password"]);
	$repassword=varsetcheck($_POST["repassword"]);
	$pin=trim(varsetcheck($_POST["pin"]));
	$username=trim(varsetcheck($_POST["username"]));
	$capvalue=trim(varsetcheck($_POST["capvalue"]));
	$agree=varsetcheck($_POST["agree"]);
	$agree='yes';
	$create=varsetcheck($_POST["create"]);
	
	varsetcheck($error["roomname"]["class"]);	varsetcheck($error["roomname"]["error"]);
	varsetcheck($error["password"]["class"]);	varsetcheck($error["password"]["error"]);
	varsetcheck($error["repassword"]["class"]);	varsetcheck($error["repassword"]["error"]);
	varsetcheck($error["pin"]["class"]);varsetcheck($error["pin"]["error"]);
	varsetcheck($error["username"]["class"]);varsetcheck($error["username"]["error"]);
	varsetcheck($error["capvalue"]["class"]); 	varsetcheck($error["capvalue"]["error"]);
	varsetcheck($error["agree"]["class"]); 	varsetcheck($error["agree"]["error"]);
	
	//executing if submitted
	if ($create=="Create"){	
		if(($error["roomname"]["error"]=validate_roomname($roomname))!==true){
			$error["roomname"]["class"]="formerror";
			$skip=1;
		}else{
			$records = selectrecord("SELECT roomname FROM rooms WHERE roomname='$roomname'");
			if($records[0][0]==1) {
				$error["roomname"]["error"]="Sorry! Room not available!";
				$error["roomname"]["class"]="formerror";
				$skip=1;
			}
		}
		if(($error["password"]["error"]=validate_password($password))!==true){
			$error["password"]["class"]="formerror";
			$skip=1;
		}
		if(($error["repassword"]["error"]=validate_password($repassword))!==true){
			$error["repassword"]["class"]="formerror";
			$skip=1;
		} else if ($password!=$repassword){
			$error["repassword"]["class"]="formerror";
			$error["repassword"]["error"]="Please enter matching password combination!";
			$skip=1;
		}
		if(!(((($pin+0)==$pin) && strlen($pin)==5) || strlen($pin)==0)){
			$error["pin"]["class"]="formerror";
			$error["pin"]["error"]="Pin must be 5-digit number or nothing!";
			$skip=1;
		}
		if(($error["username"]["error"]=validate_username($username))!==true){
			$error["username"]["class"]="formerror";
			$skip=1;
		}
		if(($error["capvalue"]["error"]=validate_captcha($capvalue))!==true){
			$error["capvalue"]["class"]="formerror";
			$skip=1;
		}
		if ($agree!="yes"){
			$error["agree"]["error"]="You have to agree to our terms and policies!";
			$skip=1;
		}

		//remove all those 1 got from the validation check
		ifchange($error["roomname"]["error"],true,"",true);
		ifchange($error["password"]["error"],true,"",true);
		ifchange($error["repassword"]["error"],true,"",true);
		ifchange($error["pin"]["error"],true,"",true);
		ifchange($error["username"]["error"],true,"",true);
		ifchange($error["capvalue"]["error"],true,"",true);
		ifchange($error["agree"]["error"],true,"",true);
		
		if($skip!=1){			//if everything's great
			$timenow=time();
			$expdate=time()+3600*24*15;
			if ($pin=='') $pin='NULL';
			$sql = "INSERT INTO rooms (roomname, password, pin, date, expdate, type, status) 
				VALUES ('$roomname', '$password', $pin, $timenow, $expdate, 'user', 'active')";
			$status_raw=insertrecord($sql);
			
			mkdir("$roomname");
			mkdir("$roomname\users");
			
			$_SESSION[$roomname]['password']=$password;
			$_SESSION[$roomname]['pin']=$pin;
			$_SESSION[$roomname]['type']='user';
			$_SESSION[$roomname]['status']='active';
			$_SESSION[$roomname]['username']=$name;
			
			$content=$roomname."_".$password."_".$pin."_".$type."_".$status;
			file_put_contents("$roomname\info",$content);
			
			
			exit(header("Location: msg.php?rname=$roomname"));

			
			
		}
	}
?>
	<div class='paTRBL10' style='overflow:hidden'>
		<form method="POST" action="">
			<div class='paTB5'>
				<?php if ($error["roomname"]["error"]){ ?> <div class="block paTRBL3 errortext"><?php echo $error["roomname"]["error"];?></div>	<?php } ?>
				<input type="text" name="roomname" value="<?php echo $roomname; ?>" placeholder="Room Name" class='<?php echo $error["roomname"]["class"];?> block'>
			</div>
			<div class='paTB5'>
				<input id='usepassword' type='checkbox' name='usepassword' value='yes' onchange='togglepassword()' <?php if($usepassword) echo 'checked';?>><label for='usepassword'>Protect Using Password</label>
				<div id='passwordcontainer' class='paB10' style='display:none'>	
					<?php if ($error["password"]["error"]){ ?><div class="block paTRBL3 errortext"><?php echo $error["password"]["error"];?></div><?php } ?>
					<input id='password' type="password" name="password" value="<?php echo $password; ?>" placeholder="Password" class='<?php echo $error["password"]["class"];?> block'>
										<input id='repassword' type="password" name="repassword" value="<?php echo $repassword; ?>" placeholder="Retype Password"  class='<?php echo $error["repassword"]["class"];?> block'>
				</div>
				<script>
					togglepassword();
					function togglepassword(){
						if (document.getElementById('usepassword').checked)
							document.getElementById('passwordcontainer').style.display='block';
						else{
							document.getElementById('passwordcontainer').style.display='none';
							document.getElementById('password').value='';
							document.getElementById('repassword').value='';
						}
					}
				</script>
				
			</div>
			<div class='paTB5'>
				<?php if ($error["pin"]["error"]){ ?> <div class="block paTRBL3 errortext"><?php echo $error["pin"]["error"];?></div>	<?php } ?>
				<input type="number" name="pin" value="<?php echo $pin; ?>" placeholder="Pin(optional, required to edit)"  class='<?php echo $error["pin"]["class"];?> block' style='width:100%;'>
			</div>	
			<div class='paTB5'>			
				<?php if ($error["username"]["error"]){ ?><div class="block paTRBL3 errortext"><?php echo $error["username"]["error"];?></div> <?php } ?>
				<input type="text" name="username" value="<?php echo $username; ?>" placeholder="Display Name"  class='<?php echo $error["username"]["class"];?> block'>
			</div>
			<div class='paTB5'>
				<?php if ($error["capvalue"]["error"]){ ?><div class="block paTRBL3 errortext" style="width:245px"><?php echo $error["capvalue"]["error"];?></div>	<?php } ?>		
				<center>
				<table id="capbox" class='<?php echo $error["capvalue"]["class"];?>'>
					<tr>
						<td><img id='capimage' src='/cap/cap.php'></td>
						<td><img id="caprefresh" title='Refresh' src='/images/refresh.png' width="50" height="50" onclick="document.getElementById('capimage').src='/cap/cap.php?+Math.random()'; document.getElementById('capvalue').value='';"></td>
					</tr>
					<tr>
						<td colspan=2><input id='capvalue' class='block maTRBL0' style='width:100%;' type='text' name='capvalue' placeholder='Type the above text here!' autocomplete='off'>
						</td>
					</tr>
				</table>
				</center>
			</div>
			
			<input type="submit" name="create" value="Create">
		</form>
	</div>