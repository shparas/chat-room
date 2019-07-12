<?php 
//has a inside block for sign in purpose.
//lets begin the code
	$rname = trim(varsetcheck($_POST["rname"]));
	$pword = varsetcheck($_POST["pword"]);
	$uname = trim(varsetcheck($_POST["uname"]));
	$join = varsetcheck($_POST["join"]);
	
	if($join=="Lets Join"){
		if(validate_roomname($rname)===true && validate_password($pword)===true && validate_username($uname)===true){
			$sql = "SELECT roomname, password, pin, type, status FROM rooms WHERE roomname='$rname' AND password='$pword'";
			$records = selectrecord($sql);
			if ($records[0][0]==1){			//get to index as member if record is there
				$_SESSION[$records[1]["roomname"]]['password']=$records[1]["password"];
				$_SESSION[$records[1]["roomname"]]['pin']=$records[1]["pin"];
				$_SESSION[$records[1]["roomname"]]['type']=$records[1]["type"];
				$_SESSION[$records[1]["roomname"]]['status']=$records[1]["status"];
				$_SESSION[$records[1]["roomname"]]['username']=$uname;
				
				exit(header("Location: msg.php?rname=$rname"));
			
			}
			else{
				$error["rname"]["class"]="formerror";
				$error["pword"]["class"]="formerror";
				$error["1"]["1"]="Wrong roomname and password combination!";
			}
		}
		else{
			if (validate_username($uname)!==true)
				$error["1"]["2"]=validate_username($uname);
			$records = selectrecord("SELECT roomname FROM rooms WHERE roomname='$rname' AND password='$pword'");
			if(varsetcheck($records[0][0])!=1) {
				$error["rname"]["class"]="formerror";
				$error["pword"]["class"]="formerror";
				$error["1"]["1"]="Wrong roomname and password combination!";
			}
		}
	}
?>
		<div class='paTRBL10' style='overflow:hidden'>
			
			<form id="signin" method="POST" action="">
				<?php if (varsetcheck($error["1"]["1"])){ ?> <div class="errortext block paTRBL3"><?php echo $error["1"]["1"];?></div>	<?php } ?>
				<input type="text" name="rname" value="<?php echo varsetcheck($rname);?>" placeholder="Room Name" autofocus class='<?php echo varsetcheck($error["rname"]["class"]);?>  block paTRBL5 maB10'>
				<input type="password" name="pword" value="<?php echo varsetcheck ($pword);?>" placeholder="Password(if any)" class='<?php echo varsetcheck($error["pword"]["class"]);?> block paTRBL5 maB10'>
				
				<?php if (varsetcheck($error["1"]["2"])){ ?> <div class="errortext block paTRBL3"><?php echo $error["1"]["2"];?></div>	<?php } ?>				
				<input type="text" name="uname" value="<?php echo varsetcheck($uname);?>" placeholder="Display Name" class='<?php if(varsetcheck($error["1"]["2"])) echo "formerror ";?>block paTRBL5 maB10'>
		
				<input class='block' type="submit" name="join" value="Lets Join">
				
			</form>
		</div>