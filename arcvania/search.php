<?php 
	include "easeFunctions.php";
	include "databasehandler.php";
	include "session.php";
	include "ipstore.php";
	
	$searchid=trim(strtolower(varsetcheck($_GET["searchid"])));
	$comdata=array();
	$pardata=array();

	//for Email
	if(strpos($searchid,"@")!==FALSE AND validate_srchfield($searchid)){
		$sql = "SELECT * FROM users WHERE Lcase(email)='$searchid'";
		$result=selectrecord($sql);
		if($result[0][0]==1){
			for ($i=1; $i<count($result); $i++){
				$comdata[]=$result[$i];
			}
		}
	}
	elseif (strlen($searchid)>0 AND validate_srchfield($searchid)){				//lets not bother for blank inputs
		//for usernames;
		if(strpos($searchid," ")===FALSE){		//only if it doesn't have space
			//complete match
			$sql = "SELECT * FROM users WHERE Lcase(username)='$searchid'";
			$result=selectrecord($sql);
			if($result[0][0]==1){
				for ($i=1; $i<count($result); $i++){
					$comdata[]=$result[$i];
				}
			}
			
			if(strlen($searchid)>=3){			//for partial username match from extreme positions
				//for username from first;
				$pattern=$searchid."_%";
				$sql = "SELECT * FROM users WHERE Lcase(username) LIKE '$pattern'";
				$result=selectrecord($sql);
				if($result[0][0]==1){
					for ($i=1; $i<count($result); $i++){
						$pardata[]=$result[$i];
					}
				}
				//for username from last;
				$pattern="%_".$searchid;
				$sql = "SELECT * FROM users WHERE Lcase(username) LIKE '$pattern'";
				$result=selectrecord($sql);
				if($result[0][0]==1){
					for ($i=1; $i<count($result); $i++){
						$pardata[]=$result[$i];
					}
				}

			}
		}
	
		//for names;
		$sql="SELECT fname, lname FROM users";
		$result=selectrecord($sql);
		if($result[0][0]==1){
			$name=array();
			for ($i=1; $i<count($result); $i++){	//getting names in correct form
				$name[$i]=$result[$i]["fname"]." ".$result[$i]["lname"];
			}
			$check=array();
			for($i=1;$i<=count($name);$i++){
				$checkraw=searcher($searchid,$name[$i]," ");	//sending for matching strings
				if($checkraw!=0) 					//if matched to some context
					$check[$i]=$checkraw;
			}
			unset($name,$result, $checkraw);		//removing unneeded variables for memory
			arsort($check);							//sorting array based on result of matching
			foreach($check as $id=>$match){
				$sql = "SELECT * FROM users WHERE id='$id'";
				$result=selectrecord($sql);
				if ($match==100) $comdata[]=$result[1];
				else $pardata[]=$result[1];
			}
		}
	}
	
	
	$comdata = array_map("unserialize", array_unique(array_map("serialize", $comdata)));	//remove duplicates array inside
	$pardata = array_map("unserialize", array_unique(array_map("serialize", $pardata)));	//remove duplicates array inside
	$comdata=fullarray($comdata);
	$pardata=fullarray($pardata);
	?>

<!DOCTYPE html>
<html>
<head>
	<meta name="viewport" content="user-scalable=no, initial-scale=1.0, width=device-width, maximum-scale=1.0, minimal-ui">
	<meta charset="UTF-8">
	<meta name="keyword" content="Seagull, Stranger's bay">
	<meta name="description" content="Connect with people of same mind">
	<style>
		@import url("style.css")
	</style>
</head>

<body>
<div id="applicationcover">
		<?php include("sign_in.php"); ?>
</div>

<?php include("headerbar.php");?>

<div id="page">
<?php 
	if (count($comdata)>0){
		echo "<b>".count($comdata)." matches made:</b><br>";
		for($i=0;$i<count($comdata);$i++){
			echo $comdata[$i]["fname"]." ".$comdata[$i]["lname"]."(".$comdata[$i]["username"].")";
			echo "<br>";
		}
	}	
	if (count($pardata)>0){
		if (count($comdata)>0) $possiblematches="<br>Other possible matches:<br>";
		else $possiblematches="Sorry! No exact matches found.<br>Few possible matches:<br>";
		echo "<b>".$possiblematches."</b>";
		for($i=0;$i<count($pardata);$i++){
			echo $pardata[$i]["fname"]." ".$pardata[$i]["lname"]."(".$pardata[$i]["username"].")";
			echo "<br>";
		}
	}
	if (count($pardata)<1 and count($comdata)<1) echo "<br>Sorry, no matches available!<br>";
?>
</div>
<script src="script.js"></script>
</body>
</html>
