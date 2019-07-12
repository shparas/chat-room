<?php
	include ('easeFunctions.php');
	include ('databasehandler.php');
	
	
	
	
	
function output_file($file, $name, $mime_type=''){
	$size = filesize($file);
	$name = rawurldecode($name);
  
	/* Figure out the MIME type (if not specified) */
	$known_mime_types=array(
		"pdf" => "application/pdf",		"txt" => "text/plain",
		"html" => "text/html",		"htm" => "text/html",
		"exe" => "application/octet-stream",		"zip" => "application/zip",
		"doc" => "application/msword",		"xls" => "application/vnd.ms-excel",
		"ppt" => "application/vnd.ms-powerpoint",		"gif" => "image/gif",
		"png" => "image/png",		"jpeg"=> "image/jpg",
		"jpg" =>  "image/jpg",		"php" => "text/plain"
	);
       
	if($mime_type==''){
		$file_extension = strtolower(substr(strrchr($file,"."),1));
		if(array_key_exists($file_extension, $known_mime_types)){
			$mime_type=$known_mime_types[$file_extension];
		} else {
			$mime_type="application/force-download";
		}
	}
  
	@ob_end_clean(); //turn off output buffering to decrease cpu usage
  
	// required for IE, otherwise Content-Disposition may be ignored
	if(ini_get('zlib.output_compression'))
		ini_set('zlib.output_compression', 'Off');
   
	header('Content-Type: ' . $mime_type);
	header('Content-Disposition: attachment; filename="'.$name.'"');
	header("Content-Transfer-Encoding: binary");
	header('Accept-Ranges: bytes');
  
	//make the download non-cacheable
	header("Cache-control: private");
	header('Pragma: private');
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
 
	// multipart-download and download resuming support
	if(isset($_SERVER['HTTP_RANGE'])){
		list($a, $range) = explode("=",$_SERVER['HTTP_RANGE'],2);
		list($range) = explode(",",$range,2);
		list($range, $range_end) = explode("-", $range);
		$range=intval($range);
		if(!$range_end){
			$range_end=$size-1;
		} else {
			$range_end=intval($range_end);
		}
		$new_length=$range_end-$range+1;
		header("HTTP/1.1 206 Partial Content");
		header("Content-Length: $new_length");
		header("Content-Range: bytes $range-$range_end/$size");
	}else{
		$new_length=$size;
		header("Content-Length: ".$size);
	}
 
	/* output the file itself */
	$speed = 5*(1024*1024); //mb*(1024*1024)
	$bytes_send = 0;
	if ($file = fopen($file, 'r')){
		if(isset($_SERVER['HTTP_RANGE']))
			fseek($file, $range);
     
		while(!feof($file) && (!connection_aborted()) && ($bytes_send<$new_length)){
			$buffer = fread($file, $speed);
			echo($buffer); 
			flush();
			//sleep(1);
			$bytes_send += strlen($buffer);
		}
		fclose($file);
	}
	else 
		die('Error - can not open file.');  
}   

	
	
	
	
	
	
	
	
	
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
	$filename=$result[1]['originalname'];
	
	if (file_exists($file)){
		$filesize=filesize($file);
		if ($filesize>5000*1024*1024){
			include("lostpage.php");
		}
		
		
		
		
set_time_limit(0);  
$file_path='that_one_file.txt';
output_file($file, $filename);
exit();
		
		
		
		
		
		
		
		header('Content-Description: File Transfer');
		header('Content-Type: application/octet-stream');
		header('Content-Disposition: attachment; filename="'.$filename.'"');
		header('Expires: 0');
		header('Cache-Control: must-revalidate');
		header('Pragma: public');
		header('Content-Length: ' . $filesize);
		
		flush();
		if (ob_get_level()) ob_end_clean();		//to remove filesize restrications
		readfile($file);
		exit();
	} else 
		include("lostpage.php");
/*
to add download limit, use similar to;
		while(!eofFILE)
			flush(); //to prevent memory issuse as there is limited output buffer size
			fread($file,bytespersecond);
			pause(1second);
			
*/
?>