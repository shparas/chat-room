<html>
<style>
body { padding: 30px }
form { display: block; margin: 20px auto; background: #eee; border-radius: 10px; padding: 15px }

.progress { position:relative; width:400px; border: 1px solid #ddd; padding: 1px; border-radius: 1px; }
.bar { background-color: green; width:0%; height:20px; border-radius: 1px; }
.percent { position:absolute; display:inline-block; top:3px; left:48%; }
</style>
<head>
	<script src='jq/jq.js'></script>
	<script src='jq/jqform.js'></script>
</head>

<body>
<h3>	<?php session_start(); if (!isset($_SESSION['novisit'])) $_SESSION['novisit']=0; $_SESSION['novisit']=$_SESSION['novisit']+1;	echo $_SESSION['novisit']; ?>
</h3>
    <form action="imageup.php" method="post" enctype="multipart/form-data">
        <input type="file" name="file"><br>
        <input type="submit" name='submit' value="Send" onclick="jqajax();">
    </form>
    
    <div class="progress">
        <div class="bar"></div >
        <div class="percent">0%</div >
    </div>
    
    <div id="status"></div>

    
<script>
function jqajax() {
	var bar = $('.bar');
	var percent = $('.percent');
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
			status.html(xhr.responseText);
		}
	}); 
};       
</script>