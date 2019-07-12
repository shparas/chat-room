<?php
	include ('easeFunctions.php');
	include ('databasehandler.php');
	
	$id=varsetcheck($_REQUEST['id'])+0;
	if(strlen($id)<5)		
		include 'lostpage.php';
	$id.='.file';
	
	$sql="SELECT id, name, originalname, type, extension, description, upfrom, upfrom2, allow, block FROM _files WHERE name='$id'";
	$result=selectrecord($sql);
	if ($result[0][0]!=1) 
		include ('lostpage.php');
	
	$file="files/uploads/".$result[1]['name'];
	$description=$result[1]['description'];
	$name=$result[1]['originalname'];
	
	if (file_exists($file)){
		$filesize=filesize($file);
		if ($filesize>5000*1024*1024){
			include("lostpage.php");
		}
		
		header('Content-Type: application/octet-stream');
		header('Content-Description: File Transfer');
		header('Content-Disposition: attachment; filename="'.$name.'"');
		header('Cache-Control: private');
		header('Pragma: private');
			
		//check if file has already started downloading for resume support
		if(isset($_SERVER['HTTP_RANGE'])){
			list($a, $range) = explode("=",$_SERVER['HTTP_RANGE'],2);
			list($range) = explode(",",$range,2);
			list($range, $range_end) = explode("-", $range);
			$range=intval($range);
			if(!$range_end){
				$range_end=$filesize-1;
			} else { 
				$range_end=intval($range_end);
			}
			$new_length=$range_end-$range+1;
			header("HTTP/1.1 206 Partial Content");
			header("Content-Length: $new_length");
			header("Content-Range: bytes $range-$range_end/$filesize");
		}else{
			$new_length=$filesize;
			header("Content-Length: ".$filesize);
		}
	 
		// download the file now
		$speed = 5*(1024*1024); //mb*(1024*1024)
		if ($file = fopen($file, 'r')){
			if(isset($_SERVER['HTTP_RANGE']))
				fseek($file, $range);
		 
			while(!feof($file)){
				echo (fread($file, $speed));
				flush();      	//to prevent memory issuse as there is limited output buffer size
				//sleep(1);		//to pause every seconds to help maintain speeds
			}
			fclose($file);
		}
	} 
	else {
		include("lostpage.php");
	}
?>