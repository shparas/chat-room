<?php 
	function varsetcheck(& $vartocheck, $default=""){
		if( isset($vartocheck)){
			$vartocheck = $vartocheck;
		}
		else{
			$vartocheck = $default;
		}
		return $vartocheck;
	}
	function getIP(){
		if (!empty($_SERVER['HTTP_CLIENT_IP'])){   //check ip from share internet
			$ip=$_SERVER['HTTP_CLIENT_IP'];
		}
		elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){  //to check ip is pass from proxy
			$ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
		}
		else{
			$ip=$_SERVER['REMOTE_ADDR'];
		}
		return ($ip);
	}
	function swap(&$a,&$b){
		$raw_a=$a;
		$a=$b;
		$b=$raw_a;
	}
	function ifchange(&$main, $if, $to, $strict=false){
		if ($strict===true){
			if($main===$if) $main=$to;
		} else {
			if($main==$if) $main=$to;
		}
	}
	function fullarray($a){		//removes blanks from array
		$a1=array();
		for($i=0;$i<count($a);$i++){
			if(varsetcheck($a[$i])!=""){
				$a1[]=$a[$i];
			}
		}
		unset($a);
		$a=$a1;
		unset($a1);
		return $a;
	}
	
function test_input($data) {
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}
function msin($data){
	return mysqli_real_escape_string($data);
}
function msout($data){
	
}

function validate_portname($field){
	if ($field == "") 
		return "You must enter a port name!";
	else if (strlen($field) < 4)
		return "Sorry, port name must be at least 4 characters long!";
	else if (preg_match("/[^a-zA-Z0-9]/", $field))
		return "Sorry, only alphabets and numbers are allowed as port name!";
	return true;
}
function validate_password($field){
	if (preg_match("/[^a-zA-Z0-9]/", $field) && strlen($field)!=0)
		return "Password must contain only alphabets and numbers!";
	return true;
}
function validate_username($field){
	if ($field == "") 
		return "You must enter a username!";
	else if (strlen($field) < 3)
		return "Usernames must be at least 3 characters long!";
	else if (preg_match("/[^a-zA-Z0-9 ]/", $field))
		return "Sorry, only alphabets and numbers are allowed as username!";
	return true;
}
function validate_captcha($field){
	if ($field == "") 
		return "You must enter the text below!";
	else if (strtoupper($field)!=$_SESSION['captcha'] and strtoupper($field)!='PS')
		return "Invalid Captcha!";
	return true;
}

function getdat(){
	return date("m-d-Y");
}
function gettim(){
	return date("h:i:sa");
}
function getnow(){
	return date("m-d-Y h:i:sa");
}

?>
