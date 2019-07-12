<?php
	$ite=2;
	do{
		$file="pngs\\(".$ite.").png";
		if(file_exists($file)){
			rename($file,"$ite.png");
			$ite+=1;
		}
		else {
			echo "Done!";
			$ite="STOP";
		}
	} while ($ite!="STOP");
?>