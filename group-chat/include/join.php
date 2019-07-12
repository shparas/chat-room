<?php 
//has a inside block for sign in purpose.
//lets begin the code
	$pname = trim(varsetcheck($_POST["pname"]));
	$pword = varsetcheck($_POST["pword"]);
	$uname = trim(varsetcheck($_POST["uname"]));
	$join = varsetcheck($_POST["join"]);
	
	if($join=="Lets Join"){
		if(validate_portname($pname)===true && validate_password($pword)===true && validate_username($uname)===true){
			$sql = "SELECT portname, password, pin, type, status FROM _ports WHERE portname='$pname' AND password='$pword'";
			$records = selectrecord($sql);
			if ($records[0][0]==1){			//get to index as member if record is there
				$_SESSION[$records[1]["portname"]]['password']=$records[1]["password"];
				$_SESSION[$records[1]["portname"]]['pin']=$records[1]["pin"];
				$_SESSION[$records[1]["portname"]]['type']=$records[1]["type"];
				$_SESSION[$records[1]["portname"]]['status']=$records[1]["status"];
				$_SESSION[$records[1]["portname"]]['username']=$uname;
				
				exit(header("Location: msg.php?pname=$pname"));
			
			}
			else{
				$error["pname"]["class"]="formerror";
				$error["pword"]["class"]="formerror";
				$error["1"]["1"]="Wrong portname and password combination!";
			}
		}
		else{
			if (validate_username($uname)!==true)
				$error["1"]["2"]=validate_username($uname);
			$records = selectrecord("SELECT portname FROM _ports WHERE portname='$pname' AND password='$pword'");
			if(varsetcheck($records[0][0])!=1) {
				$error["pname"]["class"]="formerror";
				$error["pword"]["class"]="formerror";
				$error["1"]["1"]="Wrong portname and password combination!";
			}
		}
	}
?>