<?php 
	if (!isset($_REQUEST['mapid'])) exit();
	$code=trim(htmlentities(urldecode($_REQUEST['mapid'])));
	
	$codemap=explode("*~*~*",str_replace("/\r?\n/g","",file_get_contents("map.txt")));
	for($i=0; $i<count($codemap); $i++){
		$codemap[$i]=explode("===>",$codemap[$i]);
		if($codemap[$i][1]==$code)
			$file="pngs\\".$codemap[$i][0].".png";
	}
	unset($codemap);	
	
	if (!isset($file)) $file="haha";
	if (file_exists($file)){
		header('Content-type: image/png');
		readfile($file);
	}
?>
