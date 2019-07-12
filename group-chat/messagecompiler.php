<?php
	///   ~`*`*`~
	/// errors: database can't write messages with ' ; Ajax handles only latest responseText from similar URL so it can't update all the ids
	include "easefunctions.php";
	include "databasehandler.php";
	include "session.php";
	
	function checktable(){	//creates new table and writes a line or if exitsts already does nothing WORKS
		global $portname, $log, $username;

		//creating table if it doesn't exist
		$sql = "CREATE TABLE $portname (
			id INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
			rawid VARCHAR(15) NOT NULL,
			username VARCHAR(100),
			message VARCHAR(2000),
			datesent VARCHAR(20) NOT NULL,
			UNIQUE (id)
		)";
		singlequery($sql);
		
		//file to store last check in and typing information
		if(!file_exists($log)){
			$content=time()."_";
			file_put_contents($log, $content);
		} else {
			$content=explode("_",file_get_contents($log));
			$content[0]=time();
			file_put_contents($log, implode("_",$content));
		}
	}

	function update(){		// fives maxid_rawidconverted_messagelists
		global $username, $logs, $shownumber, $crawid, $rrawid;
		
		//for last id in table named by portname
		$lastid=getmaxid();
						
		//if ($rrawid!="") rawidremover($rrawid);
		if ($crawid!="") $conv=idconv($crawid);
		
		echo $lastid."_".varsetcheck($conv)."_";
		
		
		//for recent users and sort them based on their active time
		$files=scandir($logs);
		if (count($files)>2){
			$users=array();
			for ($i=2; $i<count($files); $i++){
				$contents=explode("_",file_get_contents("$logs\\$files[$i]"));
				$users[$i-2]['name']=$files[$i];
				$users[$i-2]['time']=$contents[0];
				$users[$i-2]['typing']=$contents[1];
			}
			//sorting based on time
			function sorter($a,$b){return $b['time']-$a['time'];}
			usort($users, 'sorter');
			
			//giving the result
			$totalnumber=count($users);
			if ($shownumber<$totalnumber) $totalnumber=$shownumber;
			for ($i=0; $i<$totalnumber; $i++){
				$time=time()-$users[$i]['time'];
				if($time<10) $active='Active now.';
				else if($time>10 and $time<60) $active='Active '.$time.'s ago.';
				else if($time>60 and $time<3600) $active='Active '.floor($time/60).'m ago.';
				else if($time>3600 and $time<3600*24) $active='Active '.floor($time/3600).'h ago.';
				else if($time>3600*24) $active='Active '.floor($time/(24*3600)).'d ago.';
				
				if (time()-$users[$i]['typing']<3) $active="<i>Typing...</i>";
				else $typing='';
				$user=$users[$i]['name'];
				if (strtoupper($user)==strtoupper($username)) 
					echo "<div class='overflowcontrol' style='border:white solid 1px;border-left:none;border-right:none;'>".$user."<div style='text-align:right;font-size:11px'>".$active."</div></div>";
				else
					echo "<div class='overflowcontrol'>".$user."<div style='text-align:right;font-size:11px'>".$active."</div></div>";
			}
		}		
	}

	function msgwrite($msg, $rawid){			//writes the message along with user, date and time  and returns date*time*rawid*id
		global $portname, $username;
		$date=time();
		$msg=str_replace("'","`~!~`", $msg);
		$sql="INSERT INTO $portname (rawid, username, message, datesent) VALUES ('$rawid', '$username', '$msg', '$date')";
		$result=insertrecord($sql);
		if($result[0]==1){			//if writes to table successfullt
			$lastid=substr($result[2],strlen("Record added successfully and its id is:"))+0;
			idle();
		} 
		return date("d M, Y",$date)."*".date("h:i",$date)."*".varsetcheck($rawid)."*".varsetcheck($lastid);
	}

	function msgfetch($from, $to, $of, $change){
		global $portname, $username;
		
		if (strtolower($of)=="home") $string="username='$username' AND";
		else if (strtolower($of)=="all") $string="";
		else if (strtolower($of)=="away") $string="username<>'$username' AND";
		
		$sql="SELECT id, username, message, datesent FROM $portname WHERE $string id>=$from AND id<=$to";
		$result=selectrecord($sql);
		echo "$change"."~`*`*`~";
		
		for ($a=1; $a<count($result); $a++){	//as a=0, there's somethign else
			if($a==1 and $result[1]['id']>1){
				$tempid=$result[1]['id']-1;
				$tempsql="SELECT id, username FROM $portname WHERE id=$tempid";
				$tempresult=selectrecord($tempsql);
				$prevuser=$tempresult[1]['username'];
				unset($tempid,$tempsql,$tempresult);
			}
			if($result[$a]['username']==$username) 
				$class='bubbleright right';
			else 
				$class='bubbleleft';
			
			if ((varsetcheck($prevuser)!=$result[$a]['username']) AND ($result[$a]['username']!=$username)){
				$tit="<div class='hiddenbox'><div class='username'>".$result[$a]['username']."</div></div>";
				$class='bubbleleft maT20';
				}
			else $tit='';
			
			echo '<div id="'."m".$result[$a]['id'].'" class="'.$class.'" title="'.date("d M, Y",$result[$a]['datesent'])." ".date("h:i",$result[$a]['datesent']).'">'.$tit.'<div class="talktext">';
			
			$mes=nl2br(str_replace("`~!~`","'" , $result[$a]['message']));
			$mes=imgreplace($mes);
			$mes=smileyreplace($mes);
			echo $mes;
			echo '</div></div>';
			$prevuser=$result[$a]['username'];
			}	
		}	

	function typing(){		//updates the last typing time of the usr
		global $log;
		file_put_contents ($log, time() . "_" . time());
	}
	function idle(){		//empities the typing status instantly				
		global $log, $username;
		if(!file_exists($log)) 
			return false; 
		$content=explode('_',file_get_contents($log));
		$content[1]='';
		file_put_contents($log, implode("_",$content));
	}
	function getmaxid(){	//gives the max id of messages in table				DONE
		global $portname;
		$sql="SELECT MAX(id) FROM $portname";
		$result=selectrecord($sql);
		return $result[1]['MAX(id)'];
	}

	function rawidremover($rawids='ALL'){		//removes all rawid or usr1; send as ALL or r123*r12312**...
		global $portname, $username;
		if(strtoupper($rawids)=="ALL"){
			$sql = "UPDATE $portname SET rawid='' WHERE username='$username'";
		}else{
			$id=fullarray(explode("*", $rawids));
			$str="rawid='" . $id[0] . "'";
			for($i=1; $i<count($id);$i++){
				$str=$str . " OR rawid='" . $id[$i] . "'";
			}
			$sql = "UPDATE $portname SET rawid='' WHERE ($str) AND username='$username'";
		}
		updaterecord($sql);
	}
	
	function idconv($rawids){	//takes r123*r123... and gives r123=123*r123=323... of portname and usr1
		global $portname, $username;
		
		$rawid=fullarray(explode("*", $rawids));
		$realid=array();
		
		if(count($rawid)==0) return false;
		$str="rawid='" . $rawid[0] . "'";
		for($i=1; $i<count($rawid);$i++){
			$str=$str . " OR rawid='" . $rawid[$i] . "'";
		}
		$sql = "SELECT id, rawid, datesent FROM $portname WHERE ($str) AND username='$username'";
		
		$result=selectrecord($sql);
		$output=array();
		for ($i=1;$i<count($result);$i++){
			$output[]=$result[$i]["rawid"]."=m".$result[$i]["id"]."+".date("d M, Y h:i",$result[$i]['datesent']);
		}
		$output=implode("*", $output);
		return $output;
	}	
	function idconv2($rawids){	//takes r123*r123... and gives r123=123*r123=323... of portname and usr1
		global $portname, $usr1;
		
		$rawid=fullarray(explode("*", $rawids));
		$realid=array();
		$output=array();
		$output1="";
		
		for($i=0; $i<count($rawid);$i++){
			$sql = "SELECT id, rawid FROM $portname WHERE rawid='".$rawid[$i]."' AND usrid=$usr1";
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
	
	
	
	//basic things
	$portname=trim(varsetcheck($_REQUEST['portname']));
	$password=trim(varsetcheck($_SESSION[$portname]['password']));
	$pin=trim(varsetcheck($_SESSION[$portname]['pin']));
	$type=trim(varsetcheck($_SESSION[$portname]['type']));
	$status=trim(varsetcheck($_SESSION[$portname]['status']));
	$username=trim(varsetcheck($_SESSION[$portname]['username']));
	if($portname=='') exit();
	if($username=='') exit();
	
		$portinfo=explode("_",file_get_contents("$portname\info"));
		if (strtoupper($portname)!=strtoupper($portinfo[0])	AND $password!=$portinfo[1]) exit("INVALID MOVE");

	//preparing file names to store the main message and its part
	$logs="$portname\users";					//a folder where logs of users last update and typing stat defined as LASTRESPONSETIME_LASTTYPETIME
	$log="$portname\users\\$username";		//a file storing user's last update and typing stat defined as LASTRESPONSETIME_LASTTYPETIME
	checktable();
	
	

	$rrawid=varsetcheck($_REQUEST['rrawid']);		//remove raw id
	$crawid=varsetcheck($_REQUEST['crawid']);		//convert raw id
	

	$listfrom=varsetcheck($_POST['listfrom']);
	$listto=varsetcheck($_POST['listto']);
				
	
	$from=varsetcheck($_REQUEST['from']);
	$to=varsetcheck($_REQUEST['to']);
		if ($from>$to) swap($from,$to);
	$of=varsetcheck($_REQUEST['of']);
		if ($of=="") $of="BOTH";
	$change=varsetcheck($_REQUEST['change']);
	if ($from!="" AND $to!="") msgfetch($from, $to, $of, $change);
	
	
	$rawid=varsetcheck($_REQUEST['rawid']);
	$msg=htmlentities(urldecode(trim(varsetcheck($_REQUEST['msg']))));	
	if ($msg!="") echo msgwrite($msg, $rawid);
	
	
	$typing=varsetcheck($_REQUEST['typing']);
	if ($typing==1) typing();
	

	$update=varsetcheck($_POST['update']);
	$shownumber=varsetcheck($_POST['numberofusers']);
		if ($shownumber>100) $shownumber=100;
		if ($shownumber==0) $shownumber=10;
	if ($update==1) echo update();


	if ($rrawid!="") rawidremover($rrawid);
	
	
	
	
?>

