<?php
//has a block of 300px for sign up purpose.
//lets begin the code
	//preventing the emptiness of variable
	$portname=trim(varsetcheck($_POST["portname"]));
	$usepassword=varsetcheck($_POST["usepassword"]);
	$password=varsetcheck($_POST["password"]);
	$repassword=varsetcheck($_POST["repassword"]);
	$pin=trim(varsetcheck($_POST["pin"]));
	$username=trim(varsetcheck($_POST["username"]));
	$capvalue=trim(varsetcheck($_POST["capvalue"]));
	$agree=varsetcheck($_POST["agree"]);
	$agree='yes';
	$create=varsetcheck($_POST["create"]);
	
	varsetcheck($error["portname"]["class"]);	varsetcheck($error["portname"]["error"]);
	varsetcheck($error["password"]["class"]);	varsetcheck($error["password"]["error"]);
	varsetcheck($error["repassword"]["class"]);	varsetcheck($error["repassword"]["error"]);
	varsetcheck($error["pin"]["class"]);varsetcheck($error["pin"]["error"]);
	varsetcheck($error["username"]["class"]);varsetcheck($error["username"]["error"]);
	varsetcheck($error["capvalue"]["class"]); 	varsetcheck($error["capvalue"]["error"]);
	varsetcheck($error["agree"]["class"]); 	varsetcheck($error["agree"]["error"]);
	
	//executing if submitted
	if ($create=="Create"){	
		if(($error["portname"]["error"]=validate_portname($portname))!==true){
			$error["portname"]["class"]="formerror";
			$skip=1;
		}else{
			$records = selectrecord("SELECT portname FROM _ports WHERE portname='$portname'");
			if($records[0][0]==1) {
				$error["portname"]["error"]="Sorry! Port not available!";
				$error["portname"]["class"]="formerror";
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
		ifchange($error["portname"]["error"],true,"",true);
		ifchange($error["password"]["error"],true,"",true);
		ifchange($error["repassword"]["error"],true,"",true);
		ifchange($error["pin"]["error"],true,"",true);
		ifchange($error["username"]["error"],true,"",true);
		ifchange($error["capvalue"]["error"],true,"",true);
		ifchange($error["agree"]["error"],true,"",true);
		
		if(varsetcheck($skip)!=1){			//if everything's great
			$timenow=time();
			$expdate=time()+3600*24*15;
			if ($pin=='') $pin='NULL';
			$sql = "INSERT INTO _ports (portname, password, pin, date, expdate, type, status) 
				VALUES ('$portname', '$password', $pin, $timenow, $expdate, 'user', 'active')";
			$status_raw=insertrecord($sql);
			mkdir("$portname");
			mkdir("$portname\users");
			
			$_SESSION[$portname]['password']=$password;
			$_SESSION[$portname]['pin']=$pin;
			$_SESSION[$portname]['type']='user';
			$_SESSION[$portname]['status']='active';
			$_SESSION[$portname]['username']=$username;
			
			$content=$portname."_".$password."_".$pin."_".$type."_".$status;
			file_put_contents("$portname\info",$content);

			exit(header("Location: msg.php?pname=$portname"));		
		}
	}
?>