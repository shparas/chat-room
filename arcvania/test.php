<html>
<head>
<script src='jq/jq.js'></script>
<script src='jq/jqform.js'></script>
<style>
	#imgsend{
		width:300px;
		height: 400px;
		margin: 5px;
		padding: 5px;
		background: rgba(200,255,200,0.7);
		border: green solid 1px;
		border-radius: 1px;
		font-size:18px;
	}
	#sendingimg{
		margin: 1px auto 1px auto;
		display:block;
		width:auto;
		height: auto;
		max-height: 100%;
		max-width: 100%;
		max-height: 200px;
		border: solid green 1px; clear: both; float: none;
	}
	#sendingimgcont{
		text-align:center;
		float: left;
		clear: both;
		width: 100%;
		height: auto;
		max-height: 400px;
		max-width: 298px;
		border: solid green 1px;
		border-radius: green;
		overflow: hidden;
		padding:0px;	
	}
	#actionbuttons td{
		text-align: center;
	}
	#actionbuttons button{
		background: green;
		padding: 5px;
		color: white;
		font-size: 18px;
		border: green solid 1px;
	}
	
	#progress { position:relative; width:98%; border: 1px solid #ddd; padding: 1px; border-radius: 1px; display:none;}
	#bar { background-color: green; width:0%; height:20px; border-radius: 1px; }
	#percent { position:absolute; display:inline-block; top:3px; left:48%; }
	
</style>
</head>
<body>

<div id='imgsend'>
	<button style="width:100%; height: 27px; background: green; color:white; text-align:center; vertical-align:center; font-size: 19px !important;" id='file1' onclick="document.getElementById('file').click(); return false;">
		Select Image
	</button>
	<form action="imageup.php" method="post" enctype="multipart/form-data">
				<input type="hidden" name="submit" value="yes">
				<input type="hidden" name="sendtype" value="imgmessage">
				<input type='file' id='file' name='file' onchange='imageinputchange(); showimage(this);' style="display:none";>
			<div id='filename'></div>
			<div id='sendingimgcont' style="display:none;"><img id='sendingimg'></div>
		<input type='text' id='caption' name='caption' style='display:none; width:300px; border:green 1px green;border-left:none;border-right:none; padding: 5px;' placeholder='Enter caption if you like'>
		<table style="width:100%" id='actionbuttons'>
			<tr>
				<td>
					<button id='send' onclick='jqajax();' style="display:none; width: 100%">Send</button>
				</td>
				<td>
					<button id='cancels' onclick='sendcancel(); return false;' style="display:none; width: 100%;">cancel</button>
				</td>
			</tr>
		</table>
		<div id="progress">
			<div id="bar"></div >
			<div id="percent">0%</div >
		</div>
		<div id='errorsending'></div>
	</form>
</div>
<div id=jo></div>

<script>
	function imageinputchange(){
		var file=document.getElementById('file').value;
		file=file.split('\\');
		file=file[file.length-1];
		file=file.split('/');
		file=file[file.length-1];
		
		if(!file==""){
			if(fileValidate(file)!==true){
				document.getElementById('errorsending').innerHTML=fileValidate(file);
				sendcancel();
				return false;
			}
	
			document.getElementById('file1').innerHTML="Select To Change";
			document.getElementById('filename').innerHTML="<center>"+file+"<center>";
			document.getElementById('sendingimgcont').style.display="block";
			document.getElementById('caption').style.display='block';
			document.getElementById('send').style.display='inline';
			document.getElementById('cancels').style.display='inline';
			document.getElementById('progress').style.display='block';
		}
		else sendcancel();
	}
	function sendcancel(){
		document.getElementById('sendingimg').src="";
		document.getElementById('file1').innerHTML='Select Image';
		document.getElementById('file').value='';
		document.getElementById('filename').innerHTML="";
		document.getElementById('sendingimgcont').style.display='none';
		document.getElementById('caption').style.display='none';
		document.getElementById('send').style.display='none';
		document.getElementById('cancels').style.display='none';
		document.getElementById('progress').style.display='none';
		return false;
	}
	function showimage(input) {
		if (input.files && input.files[0]) {
			var reader = new FileReader();

			reader.onload = function (e) {
				$('#sendingimg').attr('src', e.target.result);
			}

			reader.readAsDataURL(input.files[0]);
		}
	}
	function fileValidate(filename){	//, allowedExtension){
		var allowedExtension = "jpg, jpeg, gif, png";
		allowedExtension = allowedExtension.split(", ");
		var fileExtension = filename.split('.').pop().toLowerCase();
		var isValidFile = false;
		for(var index in allowedExtension) {
			if(fileExtension === allowedExtension[index]) {
				isValidFile = true; 
				break;
			}
		}
		if(!isValidFile) {
			isValidFile='Sorry only files with extensions *.' + allowedExtension.join(', *.')+" are allowed!";
		}
		return isValidFile;
	}
	

	function jqajax() {
		var bar = $('#bar');
		var percent = $('#percent');
		var status = $('#status');
		   
		$('form').ajaxForm({
			beforeSend: function() {
				status.empty();
				var percentVal = '0%';
				bar.width(percentVal)
				percent.html(percentVal);
			},
			uploadProgress: function(event, position, total, percentComplete) {
				var percentVal = percentComplete + '%';
				bar.width(percentVal);
				percent.html(percentVal);
			},
			success: function() {
				var percentVal = '100%';
				bar.width(percentVal)
				percent.html(percentVal);
			},
			complete: function(xhr) {
				sendcancel();
				//sendmenu('picmenu',0);
				var returned=xhr.responseText;
				//if (parseInt(returned)
				//picsend(returned);
			},
			error: function(xhr){
				//status.html(xhr.responseText);
			}
		}); 
	}

	
</script>
</body>
</html>



