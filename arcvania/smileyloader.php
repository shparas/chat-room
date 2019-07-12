<?php 
	$codemap=explode("*~*~*",trim(str_replace("/\r?\n/g","",file_get_contents("images\smileys\map.txt"))));
	if ( !(count($codemap)== 1 and $codemap[0]=="")){
		for($ite=0; $ite<count($codemap); $ite++){	
			$codemap[$ite]=explode("===>",$codemap[$ite]);
			$title123=$codemap[$ite][0];
			$file123=$codemap[$ite][0].".png";
			$code123=varsetcheck($codemap[$ite][1]);
			$smileys=varsetcheck($smileys)."`~`~`".$code123;
			echo "<img class='unselectable smileysmall smileysmallshow' id='smiley".rand(1,100000)."' src='images\smileys\map.php?mapid=$code123' title='$code123' width='25px' height='25px' onclick='smileyclick(this.id);'> "; 	
		}
		$smileys=implode("`~`~`",fullarray(explode("`~`~`",$smileys)));
		echo "<script>smiley='$smileys';</script>";
	} 
	else {
		echo "Sorry, nothing available right now.";
	}
	unset($codemap);	
?>

