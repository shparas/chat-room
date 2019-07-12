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
function validate_fname($field){
	if ($field == "") 
		return "You do have a first name, don't you? Please, enter it.";
	else if (strlen($field) < 2)
		return "Sorry, first name must be at least 2 characters long!";
	else if (preg_match("/[^a-zA-Z ]/", $field))
		return "Sorry, only alphabets are allowed in first name!";
	return true;
}
function validate_lname($field){
	if ($field == "") 
		return "You do have a last name, don't you? Please, enter it.";
	else if (strlen($field) < 2)
		return "Sorry, last name must be at least 2 characters long!";
	else if (preg_match("/[^a-zA-Z ]/", $field))
		return "Sorry, only alphabets are allowed in last name!";
	return true;
}
function validate_email($field){
	if ($field == "") 
		return "Sorry, you can't leave your email blank!";
	else if (!((strpos($field, ".") > 0) && (strpos($field, "@") > 0)) || preg_match("/[^a-zA-Z0-9.@_-]/", $field))
		return "In what universe do you think this is a valid email address!?!";
	return true;
}
function validate_usrname($field){
	if ($field == "") 
		return "You must enter a username!";
	else if (strlen($field) < 5)
		return "Usernames must be at least 5 characters long!";
	else if (preg_match("/[^a-zA-Z0-9._]/", $field) || preg_match("/.+[^a-zA-Z0-9]$/", $field) || preg_match("/^[^a-zA-Z0-9].+/", $field) )
		return "Invalid username!";
	return true;
}
function validate_pswd($field){
	if ($field == "") 
		return "You must enter a password!";
	else if (strlen($field) < 6)
		return "Passwords must be at least 6 characters long!";
	else if (!preg_match("/[a-zA-Z0-9]/", $field) || !preg_match("/[a-zA-Z0-9]/", $field))
		return "Password must contain only alphabets and numbers!";
	return true;
}
function validate_captcha($field){
	if ($field == "") 
		return "You must enter the text below!";
	else if (strtoupper($field)!=$_SESSION['captcha'])
		return "Invalid Captcha!";
	return true;
}
function validate_srchfield($field){
	if (validate_fname($field)) return true;
	if (validate_usrname($field)) return true;
	if (validate_email($field)) return true;
}

function searcher($srch_str, $srchon_str){ //pass all those characters like " ", +,... as remaining strings 
	$srch_str=strtolower(trim($srch_str)); 
	$srchon_str=strtolower(trim($srchon_str));
	
	//replacing replacable arguments besides 1st 2 into *~*
	$arguments=func_get_args();
	for ($i=2;$i<count($arguments);$i++){
		$srch_str=str_replace(strtolower($arguments[$i]),"*~*",$srch_str);
		$srchon_str=str_replace(strtolower($arguments[$i]),"*~*",$srchon_str);
	}
	
	//converting the elements into array and removing blanks
	$raw=fullarray(explode("*~*",$srch_str));
	$rawon=fullarray(explode("*~*",$srchon_str));

	$found=array();
	$blastfound=array();
	for ($i1=0; $i1<count($raw); $i1++){
		$found[$i1] = array();
		$blastfound[$i1] = array();
		for($i2=0;$i2<count($rawon);$i2++){
			if ( strpos($rawon[$i2],$raw[$i1]) !== false ){
				$found[$i1][]=$i2;
				if($rawon[$i2]==$raw[$i1])
					$blastfound[$i1][]=$i2;
			}
		}
		if(isset($found[$i1][0]))
			$word=varsetcheck($word)+1;
		if(isset($blastfound[$i1][0]))
			$blastword=varsetcheck($blastword)+1;
	}
	if(count($raw)>0){
		$blastpercent=varsetcheck($blastword)/count($raw)*100;
		$percent=varsetcheck($word)/count($raw)*100;
	}
	$final=varsetcheck($blastpercent)+varsetcheck($percent);
	return $final/2;
}

function getid($usr){
	$sql = "SELECT id FROM users WHERE username='$usr' ";
	$result=selectrecord($sql);

	if ($result[0][0]!=0) return $result[1]["id"];
	else{
		$sql = "SELECT MAX(id) FROM oldusernames WHERE username='$usr'";
		$result=selectrecord($sql);
		if ($result[0][0]!=0){
			$id=$result[1]["MAX(id)"];
			if($id>0){
				$sql = "SELECT userid FROM oldusernames WHERE id=$id";
				$result=selectrecord($sql);
				return $result[1]["userid"];
			} else return false;
		}
		return false;
	}
}
function getusrname($usr){
	if ($usr+0==0) return "";
	$sql = "SELECT username FROM users WHERE id=$usr";
	$result=selectrecord($sql);
	if ($result[0][0]==0) return false; else return $result[1]["username"];
}
function getemail($usr){
	if ($usr+0==0) return "";
	$sql = "SELECT email FROM users WHERE id=$usr";
	$result=selectrecord($sql);
	if ($result[0][0]==0) return false; else return $result[1]["email"];
}
function getfullname($usr){
	$sql = "SELECT fname, lname FROM users WHERE id=$usr";
	$result=selectrecord($sql);
	if ($result[0][0]==0) return false; else return $result[1]["fname"]." ".$result[1]["lname"];
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

function loreturn($userid){	//seconds after last online
	if($userid>0){
		$sql = "SELECT lastonline FROM lastonline WHERE id=$userid";
		$result=selectrecord($sql);
		$to=$result[1]['lastonline'];
		$tn=time();
		$b4=$tn-$to;  	//timenow-timeonline
		return $b4;		
	}
}
function loupdate($userid){
	if($userid>0){
		$date=time();
		$sql = "UPDATE lastonline SET lastonline='$date' WHERE id='$userid'";
		$result=updaterecord($sql);
	}
}

//echo msin("haha' OR 'ha");
?>
