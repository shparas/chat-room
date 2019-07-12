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
	$filename=$result[1]['originalname'];
	
	if (file_exists($file)){
		$filesize=filesize($file);
		if ($filesize>5000*1024*1024){
			include("lostpage.php");
		}
		
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