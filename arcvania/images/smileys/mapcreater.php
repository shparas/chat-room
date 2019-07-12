<html>
<head>
<style>
table{
	border: solid green 2px;
}
img{
	max-width: 50px;
	max-height: 50px;
}
input[type=submit]{
	background: green;
	color: white;
	border: solid green 2px;
	font-size:16px;
	width: 100px;
	height: 30px;
}
input[type=submit]:hover{
	background: white;
	color: green;
}
input[type=submit]:active{
	background: rgb(0,100,0);
	color: white;
	font-size: 18px;
}

</style>
</head>
<body>
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

	if(isset($_REQUEST['SUBMIT'])){
		$str="";
		unset($_REQUEST['SUBMIT']);
		foreach ($_REQUEST as $key => $value){
			if ( $value != "" ) $str .= $key."===>".$value."*~*~*";
		}
		$str=implode("*~*~*",fullarray(explode("*~*~*",$str)));
		file_put_contents('map.txt',$str);
		echo "<font size=20px; color='green'><u>SAVED!</u></font><br>";
	}
	$dir="pngs";
	$files=scandir($dir);
	if (count($files>2)){
		$codemap=explode("*~*~*",str_replace("/\r?\n/g","",file_get_contents("map.txt")));
		for($i=0; $i<count($codemap); $i++){
			$codemap[$i]=explode("===>",$codemap[$i]);
			$imagemap[$codemap[$i][0]]=varsetcheck($codemap[$i][1]);
		}
		unset($codemap);
		//imagemap['title']=code  of all already listed in map.txt
		
		$imagename=array();
		$imagetitle=array();
		
		echo "<form method='POST'><table>";
			
		for ( $i=2; $i<count($files); $i++) {
			$imagename="$dir\\".varsetcheck($files[$i]);
			$imagetitle=str_replace(".png","",varsetcheck($files[$i]));
			
			
			echo "<tr> <td> $imagetitle </td><td> <input type='text' name='$imagetitle' value='".varsetcheck($imagemap["$imagetitle"])."' placeholder='Insert The Code here'> </td><td><img src='pngs\\$imagetitle.png'></td> </tr>";
			
		}
		echo "</table><input type='submit' name='SUBMIT' value='SUBMIT'>";
		echo "</form>";
	}
	else{
		echo "<font size=20px; color='green'><u>pngs folder is empty!</u></font><br>";
	}
	?>
	
<?php 
/*
	$dir="images\smileys\pngs";
	$files=scandir($dir);
	if (count($files>2)){
		$codemap=explode("*~*~*",str_replace("/\r?\n/g","",file_get_contents("images\smileys\smileymap.txt")));
		for($i=0; $i<count($codemap); $i++){
			$codemap[$i]=explode("===>",$codemap[$i]);
			$imagemap[$codemap[$i][0]]=$codemap[$i][1];
		}
		unset($codemap);
		
		$imagename=array();
		$imagetitle=array();
		for ( $i=2; $i<count($files); $i++) {
			$imagename[]="$dir\\".$files[$i];
			$imagetitle[]=str_replace(".png","",$files[$i]);
			
			
			echo "<img src='"."$dir\\".$files[$i]."' title='".str_replace(".png","",$files[$i])." ".$imagemap[str_replace(".png","",$files[$i])]."' width=20 height=20> "; 
			
			
		}	
	}
*/
?>