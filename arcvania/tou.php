<!DOCTYPE html>
<html>
<head>
	<title>Terms and Conditions</title>
	<?php include "favicons.html";?>
	<meta name="viewport" content="user-scalable=no, initial-scale=1.0, width=device-width, maximum-scale=1.0, minimal-ui">
	<meta charset="UTF-8">
	<meta name="description" content="Terms of use">
	<link rel='stylesheet' href='/style.css'>
	<style>
		.divboxlink{
			background:rgba(0,0,0,0.6);
			padding:10px;
			padding-top:5px;
			padding-bottom:5px;
			color:rgb(100,255,100);
			border-radius:4px;
		}
		.divboxlink:hover{
			background:rgba(0,0,0,0.8);
			color:rgb(50,255,50);
		}
		.divboxlink:active{
			background:rgba(0,0,0,1);
			color:rgb(0,255,0);
		}
		.divboxlinkactive{
			background:rgba(0,0,0,1);
			color:rgb(0,255,0);
		}
		.box{
			display:block;
		}
		li+li{
			margin-top:3px;
		}
		.left{
			float:left;
			clear:left;
		}
		.bggoodlow{
			background:rgba(0,0,0,0.7);
		}
		.title1{
			text-align:center;
			font-size:2em;
			padding:0;
			margin-top:0;
			margin-bottom:0;
		}
		.title2{
			text-align:center;
			font-size:1.5em;
			margin-top:0;
			margin-bottom:0;
		}
		.title3{
			text-align:center;
			font-size:1.3em;
			margin-top:0;
			margin-bottom:0;
		}
		.title4{
			text-align:center;
			font-size:1.1em;
			margin-top:0;
			margin-bottom:0;
		}
		.bg10{background:rgba(0,0,0,1)}
		.bg9{background:rgba(0,0,0,0.9)}
		.bg8{background:rgba(0,0,0,0.8)}
		.bg7{background:rgba(0,0,0,0.7)}
		.bg6{background:rgba(0,0,0,0.6)}
		.bg5{background:rgba(0,0,0,0.5)}
		.bg4{background:rgba(0,0,0,0.4)}
		.bg3{background:rgba(0,0,0,0.3)}
		.bg2{background:rgba(0,0,0,0.2)}
		.bg1{background:rgba(0,0,0,0.1)}
		.bg0{background:rgba(0,0,0,0)}
	</style>
	
	
	
</head>
<body>
<h1 class='maTRL10 maB5 paTRBL10 bggoodhead brgood center'>
	ArcVania: Terms of Use
</h1>

<div class='maTRBL10'>
<table style='border-collapse:collapse;'><tr>
	<td style='vertical-align:top; width:auto; min-width:200px;'>
		<ul style="padding:0; margin:0; list-style-type:none;">
			<li><a href='#tab1'><div id='tab1' class='tab divboxlink  divboxlinkactive' onclick='tabchange(this.id);'>Hello To click1</div></a></li>
			<li><a href='#tab2'><div id='tab2' class='tab divboxlink' onclick='tabchange(this.id);'>Hello To click2</div></a></li>
		</ul>	
	</td>
	<td style='vertical-align:top;'>
		<div id='tabbody1' class='tabbody brgood bggood bggoodlow' style='display:block;vertical-align:top;overflow:hidden'>
			<p class='title3 paTRBL10 bg3'>Hello1</p>
			<p class='paTRBL10 maT0 paT0' style='margin:0'>Man this is insanely good haha. Where was it long time ago. Man this is insanely good haha. Where was it long time ago. Man this is insanely good haha. Where was it long time ago. Man this is insanely good haha. Where was it long time ago. Man this is insanely good haha. Where was it long time ago. Man this is insanely good haha. Where was it long time ago. Man this is insanely good haha. Where was it long time ago. Man this is insanely good haha. Where was it long time ago. Man this is insanely good haha. Where was it long time ago. Man this is insanely good haha. Where was it long time ago. Man this is insanely good haha. Where was it long time ago. Man this is insanely good haha. Where was it long time ago. Man this is insanely good haha. Where was it long time ago. Man this is insanely good haha. Where was it long time ago. Man this is insanely good haha. Where was it long time ago. Man this is insanely good haha. Where was it long time ago. Man this is insanely good haha. Where was it long time ago. Man this is insanely good haha. Where was it long time ago. Man this is insanely good haha. Where was it long time ago. Man this is insanely good haha. Where was it long time ago. Man this is insanely good haha. Where was it long time ago.</p>
		</div>
		<div id='tabbody2' class='tabbody brgood bggood bggoodlow' style='display:block;display:none;vertical-align:top;overflow:hidden'>
			<p class='title3 paTRBL10 bg3'>Hello2</p>
			<p class='paTRBL10 maT0 paT0' style='margin:0'>Man this is insanely good haha. Where was it long time ago. Man this is insanely good haha. Where was it long time ago. Man this is insanely good haha. Where was it long time ago. Man this is insanely good haha. Where was it long time ago. Man this is insanely good haha. Where was it long time ago. Man this is insanely good haha. Where was it long time ago. Man this is insanely good haha. Where was it long time ago. Man this is insanely good haha. Where was it long time ago. Man this is insanely good haha. Where was it long time ago. Man this is insanely good haha. Where was it long time ago. Man this is insanely good haha. Where was it long time ago. Man this is insanely good haha. Where was it long time ago. Man this is insanely good haha. Where was it long time ago. Man this is insanely good haha. Where was it long time ago. Man this is insanely good haha. Where was it long time ago. Man this is insanely good haha. Where was it long time ago. Man this is insanely good haha. Where was it long time ago. Man this is insanely good haha. Where was it long time ago. Man this is insanely good haha. Where was it long time ago. Man this is insanely good haha. Where was it long time ago. Man this is insanely good haha. Where was it long time ago.</p>
		</div>
	</td>
</tr>
</table>
</div>
<script>
var hash=window.location.hash.substr(1).trim();
if(hash.length>0) tabchange(hash);

function tabchange(id){
	//change the displays of all to normal
	var tabbody=document.getElementsByClassName('tabbody');
	for (var i=0; i<tabbody.length;i++){
		tabbody[i].style.display='none';
	}
	var tab=document.getElementsByClassName('tab');
	for (var i=0; i<tab.length;i++){
		tab[i].className=tab[i].className.replace(" divboxlinkactive","");
	}
	//change the display of active one
	document.getElementById(id).className+=" divboxlinkactive";
	id=id.replace('tab','tabbody');
	document.getElementById(id).style.display='block';
}
</script>


<hr class=break>
<?php include 'footer.php'; ?>
</body>
</html>

