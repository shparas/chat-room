<?php
	///   ~`*`*`~
	/// errors: database can't write messages with ' ; Ajax handles only latest responseText from similar URL so it can't update all the ids
	include "easefunctions.php";
	include "databasehandler.php";
	include "session.php";
	
	function checktable(){	//creates new table and writes a line or if exitsts already does nothing WORKS
		global $tablename, $typefile, $usr1, $usr2, $log1, $log2, $msg;

		//creating table if it doesn't exist
		$sql = "CREATE TABLE $tablename (
			id INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
			usrid INT(10),
			message VARCHAR(2000),
			datesent VARCHAR(20) NOT NULL,
			rawid VARCHAR(15) NOT NULL,
			datechecked VARCHAR(20) NOT NULL,
			hidefrom VARCHAR(20) NOT NULL,
			UNIQUE (id)
		)";
		singlequery($sql);
		
		//file to store typing information
		if(!file_exists($typefile) && $msg!=''){
			$content="=*=";
			file_put_contents($typefile, $content);
		}
		
		//user log
		if(!file_exists($log1)  && $msg!='') file_put_contents($log1, "*");
		if(!file_exists($log2) && $msg!='') file_put_contents($log2, "*");
	}
	function update(){					//return $lastid.$typing.$newmessages.$lastseenid.$seendate.$seentime
		global $tablename, $typefile, $usr1, $usr2, $log1, $crawid, $rrawid;	//log1 is file on user1/onlineuser due to  user2
		
		//for last id in table named by tablename
		$lastid=getmaxid();
		
		//for typing stats from user2 
		$content=file_get_contents($typefile);
		$content=explode("*", $content);
		$content[0]=explode("=", $content[0]);//content[0]=[user, lasttypetime]
		$content[1]=explode("=", $content[1]);
		$time=time();
		if (varsetcheck($content[0][0])==$usr2) {
			$typetime=$content[0][1]; 
		}
		else if (varsetcheck($content[1][0])==$usr2) {
			$typetime=$content[1][1];
		}
		$typing=($time-varsetcheck($typetime)<2? "TYPING": "");
		
		//for number of new unseen messages	from user2	
		$file1=explode("*", file_get_contents($log1));		
		$newmessages=$file1[1];
		
		//for id of last seen message
		$sql="SELECT MAX(id) FROM $tablename WHERE datechecked<>'' AND usrid=$usr1";
		$record=selectrecord($sql);
		$lastseenid=$record[1]['MAX(id)'];
		
		//for seen date and time
		if($lastseenid>0){
			$sql="SELECT datechecked FROM $tablename WHERE id=$lastseenid";
			$record=selectrecord($sql);
		}
		
		if( varsetcheck($record[1]['datechecked'])>2345 ){
			$dateseen=date("d M, Y h:i",varsetcheck($record[1]['datechecked']));
		} else $dateseen="NO";
		
		if ($rrawid!="") rawidremover($rrawid);
		if ($crawid!="") $conv=idconv($crawid);
		
		return $lastid."~`*`*`~".$typing."~`*`*`~".$newmessages."~`*`*`~".$lastseenid."~`*`*`~".$dateseen."~`*`*`~".varsetcheck($conv);
		
	}
	function msgwrite($msg, $rawid){			//writes the message along with user, date and time  and returns date*time*rawid*id
		global $tablename, $usr1, $usr2, $log1, $log2;
		$date=time();
		$msg=str_replace("'","`~!~`", $msg);
		$sql="INSERT INTO $tablename (usrid, message, datesent, rawid) VALUES ($usr1, '$msg', '$date', '$rawid')";
		if($result=insertrecord($sql)[2]){			//if writes to table successfullt
			$lastid=substr($result,strlen("Record added successfully and its id is:"))+0;
			idle();
			
			//writing user logs as well
			$timeval=$date;
			$file1=explode("*", file_get_contents($log1));
			$file2=explode("*", file_get_contents($log2));
			$file1[0]=$timeval; $file1[1]=$file1[1];
			$file2[0]=$timeval; $file2[1]=$file2[1]+1; 
			file_put_contents($log1, implode("*", $file1));
			file_put_contents($log2, implode("*", $file2));
		}
		return date("d M, Y",$date)."*".date("h:i",$date)."*".varsetcheck($rawid)."*".varsetcheck($lastid);
	}
	function seenwrite($id){			//writes the date check stat and updates the log
		global $tablename, $usr1, $usr2, $log1;
		if (getmaxid()<($id+0)) return "";
		$date=time();
		$sql = "UPDATE $tablename SET datechecked='$date' WHERE id<=$id AND datechecked='' AND usrid=$usr2";
		$result=updaterecord($sql);
		
		//updating userlog
		$file1=explode("*", file_get_contents($log1));
		$file1[1]=$file1[1]-$result[2];
		if ($file1[1]<0) $file1[1]=0;
		file_put_contents($log1, implode("*", $file1));
	}
	function delmsg($id){				//write from whom to hide ie single user id or ALL for conversatino delete	DONE
		global $tablename, $usr1, $usr2, $log1;
		if (getmaxid()<($id+0)) return "";		
		if($id>=1){
			$sql="SELECT hidefrom FROM $tablename WHERE id=$id";
			$result=selectrecord($sql);
			if ($result[1]['hidefrom']==$usr2 OR $result[1]['hidefrom']=="ALL") $hidefrom='ALL'; else $hidefrom=$usr1;
			$sql = "UPDATE $tablename SET hidefrom='$hidefrom' WHERE id='$id'";
			updaterecord($sql);
		}
		if (strtoupper($id)=="ALL"){
			$maxid=getmaxid();
			if($maxid>=1){
				$sql="SELECT hidefrom FROM $tablename WHERE id<=$maxid";
				$result=selectrecord($sql);
				for ($i=1; $i<=$maxid; $i++){
					if ($result[$i]['hidefrom']==$usr2 OR $result[$i]['hidefrom']=="ALL") $hidefrom='ALL'; else $hidefrom=$usr1;
					$sql = "UPDATE $tablename SET hidefrom='$hidefrom' WHERE id='$i'";
					updaterecord($sql);
				}
			}
			unlink($log1);
		}
	}
	function msgfetch($from, $to, $of, $change){
		global $tablename, $usr1, $usr2;
		if (strtolower($of)=="home") $string="usrid=$usr1 AND";
		else if (strtolower($of)=="all") $string="";
		else if (strtolower($of)=="away") $string="usrid=$usr2 AND";
		
		$sql="SELECT id, usrid, message, datesent, hidefrom FROM $tablename WHERE $string id>=$from AND id<=$to";
		$result=selectrecord($sql);
		echo "$change"."~`*`*`~";

		for ($a=1; $a<count($result); $a++){	//as a=0, there's somethign else
			if(!($result[$a]['hidefrom']=="ALL" OR $result[$a]['hidefrom']==$usr1)){
				
				if($result[$a]['usrid']==$usr1) $class='message talk-bubble tri-right border round right-in';
				else $class='message talk-bubble talk-bubble-away border round tri-right left-in';
				
				echo '<div id="'."m".$result[$a]['id'].'" class="'.$class.'" title="'.date("d M, Y",$result[$a]['datesent'])." ".date("h:i",$result[$a]['datesent']).'"><div class="talktext">';
				$mes=nl2br(str_replace("`~!~`","'" , $result[$a]['message']));
				$mes=imgreplace($mes);
				$mes=smileyreplace($mes);
				echo $mes;
				echo '</div></div>';
			}	
		}	
	}
	function typing(){		//updates the last typing time of the usr
		global $typefile, $usr1 , $usr2;
		if(!file_exists($typefile)) return false;
		$content=file_get_contents($typefile);
		$content=explode("*", $content);
		$content[0]=explode("=", $content[0]);//content[0]=[user, lasttypetime]
		$content[1]=explode("=", $content[1]);
		$time=time();
		
		if (varsetcheck($content[0][0])==$usr2) {
			$content[1][0]=$usr1; $content[1][1]=$time; 
		}
		else if (varsetcheck($content[1][0])==$usr2) {
			$content[0][0]=$usr1; $content[0][1]=$time;
		}
		else {
			$content[0][0]=$usr1; $content[0][1]=$time; 
		}
		$content[0]=implode("=",$content[0]);
		$content[1]=implode("=",$content[1]);
		$content=implode("*",$content);
		file_put_contents($typefile, $content);
	}
	function idle(){		//empities the typing status instantly				
		global $typefile, $usr1, $usr2;
		if(!file_exists($typefile)) return false; 
		$content=file_get_contents($typefile);
		$content=explode("*", $content);
		$content[0]=explode("=", $content[0]);//content[0]=[user, lasttypetime]
		$content[1]=explode("=", $content[1]);
		
		if (varsetcheck($content[0][0])==$usr1){ $content[0][0]=""; $content[0][1]="";}
		if (varsetcheck($content[1][0])==$usr1){ $content[1][0]=""; $content[1][1]="";}
		$content[0]=implode("=",$content[0]);
		$content[1]=implode("=",$content[1]);
		$content=implode("*",$content);
		file_put_contents($typefile, $content);
	}
	function getmaxid(){	//gives the max id of messages in table				DONE
		global $tablename;
		$sql="SELECT MAX(id) FROM $tablename";
		$result=selectrecord($sql);
		return $result[1]['MAX(id)'];
	}
	function universalupdate($listfrom=0, $listto=0){	//gives total no of unseen messages throughout the user profile or makes list for message page 
		global $usr1, $usr2;
		$dir=getusrname($usr1)."\\"."message";
		$files=scandir($dir);
		$tm=0;
		if (count($files>2)){
			$records=array();
			if ($listfrom==0 and $listto==0){				//if list no. isn't mentioned, returns the total no. of new messages
				for ( $i=2; $i<count($files); $i++) {
					$contents=explode("*",file_get_contents("$dir/$files[$i]"));
					$tm=$tm+varsetcheck($contents[1]);
				}
				//return $tm;
			}else{											//makes list to print in message window
				for ( $i=2; $i<count($files); $i++) {
					$contents=explode("*",file_get_contents("$dir/$files[$i]"));
					$tm=$tm+varsetcheck($contents[1]);
					$contents[2]=$files[$i];	//returns userid
					$records[]=$contents;		//time in std sec, no of new messages, userid
				}
				
				//sorting based on date
				function sorter($a,$b){return $b[0]-$a[0];}
				usort($records, 'sorter');
				
				echo "$tm"."~`*`*`~"."";
				for ($rec=0; $rec<count($records); $rec=$rec+1){
							//send the premade list here
					$usrname=getusrname($records[$rec][2]);
					$messagenumber=($records[$rec][1]>8?"8+":$records[$rec][1]);
					$messagenumber=($messagenumber==0?"":"<div class='newmessagenumber'>$messagenumber</div>");
					if($usr2==$records[$rec][2]) $class=' activeusr'; else $class='';
					echo "<button class='paTRBL3 maB3 usrnamebox$class' id='$usrname' onclick='newuser(this.id)'>".getfullname($records[$rec][2])."$messagenumber</button>";	
				}
			}
		}	
	}
	function rawidremover($rawids='ALL'){		//removes all rawid or usr1; send as ALL or r123*r12312**...
		global $tablename, $usr1;
		if(strtoupper($rawids)=="ALL"){
			$sql = "UPDATE $tablename SET rawid='' WHERE usrid=$usr1";
		}else{
			$id=fullarray(explode("*", $rawids));
			$str="rawid='" . $id[0] . "'";
			for($i=1; $i<count($id);$i++){
				$str=$str . " OR rawid='" . $id[$i] . "'";
			}
			$sql = "UPDATE $tablename SET rawid='' WHERE ($str) AND usrid=$usr1";
		}
		updaterecord($sql);
	}
	function idconv($rawids){	//takes r123*r123... and gives r123=123*r123=323... of tablename and usr1
		global $tablename, $usr1;
		
		$rawid=fullarray(explode("*", $rawids));
		$realid=array();
		
		$str="rawid='" . $rawid[0] . "'";
		for($i=1; $i<count($rawid);$i++){
			$str=$str . " OR rawid='" . $rawid[$i] . "'";
		}
		$sql = "SELECT id, rawid FROM $tablename WHERE ($str) AND usrid=$usr1";
		
		$result=selectrecord($sql);
		$output=array();
		for ($i=1;$i<count($result);$i++){
			$output[]=$result[$i]["rawid"]."=m".$result[$i]["id"];
		}
		$output=implode("*", $output);
		return $output;
	}	
	function idconv2($rawids){	//takes r123*r123... and gives r123=123*r123=323... of tablename and usr1
		global $tablename, $usr1;
		
		$rawid=fullarray(explode("*", $rawids));
		$realid=array();
		$output=array();
		$output1="";
		
		for($i=0; $i<count($rawid);$i++){
			$sql = "SELECT id, rawid FROM $tablename WHERE rawid='".$rawid[$i]."' AND usrid=$usr1";
			$result=selectrecord($sql);
			if (varsetcheck($result[1]['rawid'])==$rawid[$i]){
				$output[]=$result[1]['rawid']."=m".$result[1]['id'];
			}else $output1.="*".$rawid[$i];
		}
		$output=implode("*", $output);
		return $output;
	}
	function smileyreplace($string){
		$codemap=explode("*~*~*",trim(str_replace("/\r?\n/g","",file_get_contents("images\smileys\map.txt"))));
		if ( !(count($codemap)== 1 and $codemap[0]=="")){
			for($ite=0; $ite<count($codemap); $ite++){	
				$codemap[$ite]=explode("===>",$codemap[$ite]);
				$title123=$codemap[$ite][0];
				$file123=$codemap[$ite][0].".png";
				$code123=varsetcheck($codemap[$ite][1]);
				$smileys=varsetcheck($smileys)."`~`~`".$code123;	
			}
			$smileys=implode("`~`~`",fullarray(explode("`~`~`",$smileys)));
		} 
		$smilies=explode("`~`~`", varsetcheck($smileys));
		for ($ite=0; $ite<count($smilies); $ite++){
			$string = str_replace($smilies[$ite], '<img class="unselectable smileysmall" src="images\\smileys\\map.php?mapid='.$smilies[$ite].'" width="20px" height="20px">', $string);
		}
		unset($codemap);
		return $string;
	}
	function imgreplace($string){
		if(substr($string, 0, 4)=="_:(("){
			$string=trim(str_replace("_:((","",$string));
			$string=trim(str_replace(")):_","",$string));
			$src="images/uploads/$string.jpg";
			$string="<img src='$src' class='msgimage'>";
		}
		return $string;
	}
	
	$usr1=getid(varsetcheck($_REQUEST['usr1']));
	$usr2=getid(varsetcheck($_REQUEST['usr2']));

		if ($usr1==0 or $usr1!=varsetcheck($_SESSION['id'])) exit();
		
	$update=varsetcheck($_POST['update']);

	$universalupdate=varsetcheck($_POST['universalupdate']);
	$listfrom=varsetcheck($_POST['listfrom']);
	$listto=varsetcheck($_POST['listto']);
				
				//for universal updates that needn't pass below:
				if ($universalupdate==1){
					echo universalupdate(1,2);	//calls only if universal update is asked directily and gives the list
					exit();
				}				
				if ($usr2==0) exit();
		
	$from=varsetcheck($_REQUEST['from']);
	$to=varsetcheck($_REQUEST['to']);
		if ($from>$to) swap($from,$to);
	$of=varsetcheck($_REQUEST['of']);
		if ($of=="") $of="BOTH";
	$change=varsetcheck($_REQUEST['change']);
		
	$msg=htmlentities(urldecode(trim(varsetcheck($_REQUEST['msg']))));
	
	$rrawid=varsetcheck($_REQUEST['rrawid']);		//remove raw id
	$crawid=varsetcheck($_REQUEST['crawid']);		//convert raw id
	
	$msgid=varsetcheck($_REQUEST['msgid']);
	$seenstat=varsetcheck($_REQUEST['seenstat']);
	$id=varsetcheck($_REQUEST['id']);
	$rawid=varsetcheck($_REQUEST['rawid']);
	$delmsg=varsetcheck($_REQUEST['delmsg']);
	$typing=varsetcheck($_REQUEST['typing']);
	

	//preparing table name and file names to store the main message and its part
	if($usr2!="" and $usr1!=""){
		$tablename=array($usr1, $usr2);
		sort($tablename);
		$tablename=implode("X",$tablename);
		$typefile="Message/$tablename";					//a file about combined users' typing status as USR=TIMEINS*USR=TIMEINS
		$log1=getusrname($usr1)."/message/".$usr2;		//a file about usr2 in usr1/message/
		$log2=getusrname($usr2)."/message/".$usr1;		//a file about usr1 in usr2/message/
		checktable();	
	}



	//if ($universalupdate==1) echo universalupdate(1,2);	//calls only if universal update is asked directily and gives the list
	if ($rrawid!="") rawidremover($rrawid);
	if ($update==1) echo update();
	if ($from!="" AND $to!="") msgfetch($from, $to, $of, $change);
	if ($msg!="") echo msgwrite($msg, $rawid);
	if ($seenstat!="" AND $id!="") seenwrite($id);
	if ($delmsg!="" AND $id!="") delmsg($id);
	if ($typing==1) typing();
?>

