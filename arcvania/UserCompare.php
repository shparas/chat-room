<?php 
	include "easeFunctions.php";
	include "databasehandler.php";
	include "session.php";
	
	$usr=$_SESSION['id'];
	$interests=urldecode($_REQUEST['int']);		//group by _!~~!_
	$blockedusers='';

	$sql = "DELETE FROM findusers WHERE (".time()."-addedtime>5) OR (holdby<>'' AND STATUS<>'SEARCHING')";
	$result=singlequery($sql);
	$sql = "DELETE FROM findusers WHERE usrid=$usr";
	$result=singlequery($sql);

	$timeadded=time();
	$sql = "INSERT INTO findusers (usrid, interests, blockedusers, addedtime, status) 
			VALUES ($usr, '$interests', '$blockedusers', $timeadded, 'SEARCHING')";	
	$result=singlequery($sql);

	do{
		$sql = "SELECT * FROM findusers WHERE usrid=$usr";
		$result=selectrecord($sql);
		$holder=varsetcheck($result[1]['holdby']);
		if ($holder!=''){
			$sql="SELECT * FROM findusers WHERE usrid=$holder";
			$result=selectrecord($sql);
			if($result[0][0]==1){
				$sql = "UPDATE findusers SET status='FOUND', holdby=$usr WHERE usrid=$holder";
				$result=singlequery($sql);
				$sql = "DELETE FROM findusers WHERE usrid=$usr";
				$result=singlequery($sql);
				echo "1_!~~!_".getusrname($holder);
				exit();
			}else{
				$sql = "UPDATE findusers SET holdby=NULL WHERE usrid=$usr";
				$result=singlequery($sql);
			}
		}
		//now selecting one record at a time;
		$selectid=varsetcheck($selectid)+1;
		$sql="SELECT MIN(id), usrid, interests, blockedusers, addedtime, status, holdby FROM findusers WHERE id>=$selectid AND status='SEARCHING' AND usrid<>$usr AND ".time()."-addedtime<8";
		$result=selectrecord($sql);
		$newselectid=varsetcheck($result[1]['MIN(id)']);
		if ($selectid<$newselectid) $selectid=$newselectid;
		else {
			//use when database is completely scanned
			continue;
			//exit("SORRY END OF DATABASE IS REACHED!"); 
		}
		//here's the required other rows where status=searching, and addedtime not more than 7 sec before
		if(searcher($interests, $result[1]['interests'],"_!~~!_")!=0){
				$sql = "UPDATE findusers SET holdby=$usr WHERE usrid=".$result[1]['usrid'];
				$result=singlequery($sql);
		}
	} while (time()-$timeadded<5);
	exit ("0_!~~!_Sorry! Couldn't find any users at the moment!");
	
	