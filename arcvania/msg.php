<?php include "easeFunctions.php"; include "databasehandler.php"; session_start(); if(varsetcheck($_SESSION["username"])=="") include "lostpage.php";?><!DOCTYPE html>
<html>
<head>
	<?php include "favicons.html";?>
	<meta name="viewport" content="user-scalable=no, initial-scale=1.0, width=device-width, maximum-scale=1.0, minimal-ui">
	<meta charset="UTF-8">
	<meta name="keyword" content="ArcVania, Share Ideas, Connect to People">
	<meta name="description" content="Connect to people of same interests and share ideas publicly.">
	<link rel='stylesheet' href='/style.css'>
	
	
	<link rel=stylesheet href="/bubble/bubble.css">
	<script src='/jq/jq.js'></script>
	<script src='/jq/jqform.js'></script>
<script>
	var obj;
	function loadDoc(url, string, readyfunc){
		if (window.XMLHttpRequest){
			obj=new XMLHttpRequest();
		}else{
			obj=new ActiveXObject("Microsoft.XMLHTTP");
		}
		obj.onreadystatechange=readyfunc;
		obj.open("POST",url,true);
		obj.setRequestHeader("content-type","application/x-www-form-urlencoded");
		obj.send(string);
	}
	function send(strings, func){		//use this to send string and funciton
		var rand=Math.random();
		var string="jpt="+rand+"&usr1="+usr1+"&usr2="+usr2+"&"+strings;
		var url="messagecompiler.php";
		loadDoc(url, string, func);
	}
	function unicode(e){				//use this to get the ascii characters of input
		var code=e.keyCode? e.keyCode : e.charCode
		return code;
	}	
	function htmlentities(str){			//translates to html entities
		var encodedStr = str.replace(/[\u00A0-\u9999<>\&]/gim, function(i) {
		return '&#'+i.charCodeAt(0)+';';});
		return encodedStr;
	}
	function notlessthan(nmr, than){	//compares the no and return the max one
		if (nmr<than) nmr=than;
		return nmr;
	}
</script>
<style>
	#additem{
		max-width:270px;
		border: none !important;
		outline-none  !important;;
		outline-color: transparent  !important;;
		outline-style: none  !important;;
		background: transparent !important;
		padding:5px;
		color:white;
		display:inline-block; 
		min-width:88px;
		background:white; 
		color:black; 
		font-size:18px;
	}
	#additem::-webkit-input-placeholder{color:grey;}#additem:-moz-placeholder{color:grey;}#additem::-moz-placeholder{color:grey;}#additem:-ms-input-placeholder{color:grey;}
	.items{
		border-radius: 3px;
		padding:5px;
		color:white;
		display: inline-block;
		background: green;
		margin-bottom: 3px;
		margin-right: 3px;
		font-size: 18px;
		vertical-align: top;
	}
	.value{
		max-width: 270px;
		display: inline-block;
		
		overflow-wrap: break-word;
		word-wrap: break-word;
		-ms-word-break: break-all;
		word-break: break-all;
		word-break: break-word;
		-ms-hyphens: auto;
		-moz-hyphens: auto;
		-webkit-hyphens: auto;
		hyphens: auto;
	}
	.closecrossspace{
		vertical-align: top;
		width:25px;
		display: inline-block
	}
	.closecross{
		float: right;
		background: green;
		width:0px;
		height:0px;
		text-align: center;
		color: white;
		font-weight: bold;
		cursor: pointer;
	}
	.insideclosecross{
		margin: 1px;
		position: relative;
		left: -18px;
		width: 16px;
		height:16px;
		background: rgb(230,0,0);
		opacity: 0.7;
		font-size: 16px;
		color:white;
	}
	.insideclosecross:hover{
		background: rgb(255,0,0);
		opacity:1;
	}
	.button{
		width: auto;
		height:	30px;
		padding: 3px 5px 3px 5px;
		margin: 3px;
		border: 1px solid rgb(70,210,70);
		background: rgb(30,150,30);
		border-radius: 3px;
		color: white;
	}
	.button:hover, .button:active{
		border: 1px solid rgb(140,280,140);
		background: rgb(40,180,40);
	}
	#imgsendcover{
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
		max-height: 170px;
		border: solid green 1px; clear: both; float: none;
	}
	#sendingimgcont{
		text-align:center;
		float: left;
		clear: both;
		width: 100%;
		height: auto;
		max-height: 170px;
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
<?php include("headerbar.php");?>
<div class='paTRBL10'>
	
	<div class='maRB5 bggood brgood'  style="float:left;width:100%;max-width:300px;min-width:200px;">
		<button id='newmsg' onclick="newuser('new')">+New</button>
		<div id='messagelist' class='paTRL5 paB2'>
		</div>
	</div>


	<div class='maL5 maB5 bggood brgood'  style="float:right;width:100%;max-width:300px;min-width:200px;">
		<div id='adbarright' class='paTRBL10'>Loading the ads here....</div>
	</div>


	<center>
	<div id='mainsbox' style="text-align:left;overflow:hidden;max-width:500px;min-width:200px;">
		<div class=''>
			<div id='usr2focus' class='paTRBL5' style="display:inline-block;background:rgb(0,150,0);">
				CLICK ON USER
			</div>
			<div id='msgsetting' class='paTRBL5 right' style="display:inline-block;background:rgb(0,150,0); height: 20px;">
				<img src='/images/setting.png' width=20 height=20>
			</div>
			
			
			<div id='msgwindow' onscroll='boxscroll()' class='bggood paTRBL10' style='height:400px;'>	
					<div id="typing"></div>
			</div>
				
				
			


			
				
		<div id='hiddenmenu'>
			<div id='smileymenu' class="inlineblock" style='width:0; height:0; margin:0px; padding: 0px; border: 0px; position:relative; top:-215px; z-index:1; display:none'>
				<div class='textcontainer bordersmall' style='
								background: rgb(240,250,240);
								background: rgba(240,250,240,0.9);
								width: 350px;
								height: 200px;
								overflow-y: auto;
								overflow-x: hidden;
				'>
					<?php include "smileyloader.php"; ?>
				</div>
			</div>
			<div id='picmenu' class="inlineblock" style='width:0; height:0; margin:0px; padding: 0px; border: 0px; position:relative; top:-365px; z-index:1; display:none'>
				<div class='textcontainer bordersmall' style='background:rgb(240,250,240);width:290px;height:350px;overflow:auto;'>
					<center>
					<button style="width:100%; height: 27px; background: green; color:white; text-align:center; vertical-align:center; font-size: 19px !important;" id='file1' onclick="document.getElementById('file').click(); return false;">
						Select Image
					</button>
					<form action="imageup.php" method="post" enctype="multipart/form-data">
								<input type="hidden" name="submit" value="yes">
								<input type="hidden" name="sendtype" value="imgmessage">
								<input type='file' id='file' name='file' onchange='imageinputchange(); showimage(this);' style="display:none";>
							<div id='filename'></div>
							<div id='sendingimgcont' style="display:none;"><img id='sendingimg'></div>
						<input type='text' id='caption' name='caption' style='display:none; width:290px; border:green 1px green;border-left:none;border-right:none; padding: 5px;' placeholder='Enter caption if you like'>
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
					</center>
				</div>
			</div>
			<div id='stickermenu' class="inlineblock" style='width:0; height:0; margin:0px; padding: 0px; border: 0px; position:relative; top:-215px; z-index:1; display:none'>
				<div class='textcontainer bordersmall' style='
								background: rgb(240,250,240);
								background: rgba(240,250,240,0.8);
								width: 350px;
								height: 200px;
								overflow: auto;
				'>
					NOTHING RIGHT NOW!
				</div>
			</div>
		</div>
		


		
			<div class='textcontainer' style="background:rgb(245,255,245);background:rgba(245,255,245,0.7); height: 20px">
				<img src='images/smileyface.png' width='20px' height='20px' onclick="sendmenu('smileymenu',2);"> <img src='images/sendpic.png' width='30px' height='20px' onclick="sendmenu('picmenu',2);"> <img src='images/sendsticker.png' width='20px' height='20px' onclick="sendmenu('stickermenu',2);">
			</div>

			<div id='inputbar'>
				<div class='maL1' style='float:right;overflow:hidden;height:40px;'>
					<button id='clicktosend' style="width:60px;height:100%;background:rgb(0,140,0);" onclick='writemsg()'>SEND</button>
				</div>
				<div style='height:40px;overflow:hidden'>
					<textarea id="msg" placeholder="Type your message" onkeydown="pressdown(event)" onkeyup="pressup(event)" onclick="see()" onfocus="see()" style="width:100%;height:100%;resize:none;"></textarea>
				</div>
			</div>

		</div>
	</div>
				
	</center>	

<div id='processcheck'>				
</div>
</div>

	

	
	
	
<script>
var usr1="<?php echo varsetcheck($_SESSION['username']); if (varsetcheck($_SESSION['username'])=="") exit("REGISTER FIRST");?>";
var usr2="<?php echo trim(getusrname(varsetcheck($_REQUEST['usr2'])));?>".trim();

var lastid=0, newid=0, lastseen="", startid=0, typing="", craw="";
universalupdate();
var auto;
var uniupdate=setInterval(function(){universalupdate()}, 4000);
if (usr2=='') usr2="new";
newuser(usr2);

function update(){	//works now
	var string="update=1&crawid="+craw;
	var fun=function(){ 
			if (obj.readyState==4 && obj.status==200){
				var response=obj.responseText;		//returns newid,typing stat, newmessages, lastseenid,seendate, seentime
				var update=response.split("~`*`*`~");
				var change;
				
				//for craw;
				var writeid=update[5]; //for craw
				var templine;
				if (writeid.length>0){
					writeid=writeid.split("*");
					for (var ite=0; ite<writeid.length; ite++){
						templine=writeid[ite].split("=");
						
						if(templine[0]=templine[0])
							templine[0]=templine[0].trim();
						if(templine[1]=templine[1])
							templine[1]=templine[1].trim();
						
						document.getElementById('mess').innerHTML+="<br>"+templine[0]+" "+templine[1];
						if(document.getElementById(templine[0])){
							document.getElementById(templine[0]).className="message talk-bubble tri-right border round right-in";
						}
						if(document.getElementById(templine[0])){
							document.getElementById(templine[0]).id=templine[1];
							//craw=craw.replace(templine[0],"");
							//craw=craw.replace("**","*");
							}
						
					}
				}
				//document.getElementById('mess').innerHTML=update[5];
				
				
				//before adding to find scroll stuffs
				var temptype=document.getElementById('typing').innerHTML;
				
				var p = document.getElementById('msgwindow');
				var scrolled=p.scrollTop;					//returns amount scrolled
				var totalheight=p.scrollHeight;;			//returns the total height of box
				var shownheight = parseInt(p.style.height);	//returns the shown height
				var scrollable = totalheight-shownheight;	//returns the max value of scrolltop
				var scroll;
				if ((scrollable-scrolled)<15) {
					scroll=3;
				}
				
				newid=parseInt(update[0]);  //get newid in int
				if (newid==0){				//if empty prints nothing 
					document.getElementById('msgwindow').innerHTML="NOTHING TO DISPLAY<div id='typing'></div>";
				}
				if (lastid==0 && lastid<newid){		//if its for first time the message box is loaded. It gets max of last 15 messages
					var abc=notlessthan(newid-15,0);	//if less than 15 are present then request from 0th position
					document.getElementById('msgwindow').innerHTML='<div id="typing">'+typing+'</div>';		//adding the typing div in box and its stat inside
					readmsg(abc,newid,"all","start");
				}else if (lastid>0 && lastid<newid) {		//if its not for first time or message id is not last
					readmsg(lastid+1, newid,"away", "last");
				}
				
				if (update[1]=="TYPING"){		//updates the typing stat
					typing="TYPING";
					if (document.getElementById('typing').innerHTML="TYPING"){};
				} else {
					typing="";
					if (document.getElementById('typing').innerHTML=""){};
				}
				
				var lastseenupdate=update[3];	
				if (lastseen!=lastseenupdate){	//if seen id is changed, removes all previous seen  if any, then adds after new received one
					var par=document.getElementById("msgwindow");
					var addat=document.getElementById("m"+lastseenupdate)
					var seen=document.getElementById("seen");
			 
					if (seen) par.removeChild(seen);
					
					var ele=document.createElement("div");
						ele.id="seen";
						ele.innerHTML="SEEN &#10004";
						ele.title=update[4];
					if (addat) {par.insertBefore(ele, addat.nextSibling);
						lastseen=lastseenupdate;
					}
					scroll=scroll+1;
				}
				
				//after adding
				if (temptype!=update[1]) scroll=scroll+1;
				if (scroll>3){				
					scrollToBottom("msgwindow");
					scroll=0;
				}
			}
	}
	send (string, fun);
}
function universalupdate(){		//how own ajax 
	var uniupd;
	var string="jpt="+Math.random()+"&usr1="+usr1+"&usr2="+usr2+"&universalupdate=1";
	var url="messagecompiler.php";
	if (window.XMLHttpRequest){
		uniupd=new XMLHttpRequest();
	}else{
		uniupd=new ActiveXObject("Microsoft.XMLHTTP");
	}
	uniupd.onreadystatechange=function(){
			if (uniupd.readyState==4 && uniupd.status==200){
				var response=uniupd.responseText.split("~`*`*`~");
				if(response[1].length>10 && response[1]!=document.getElementById('messagelist').innerHTML){
					document.getElementById('messagelist').innerHTML=response[1];
				}
				var newmsg=document.getElementById('totalnewmessage');
				if (newmsg) newmsg.innerHTML=response[0];
			}
		}
	uniupd.open("POST",url,true);
	uniupd.setRequestHeader("content-type","application/x-www-form-urlencoded");
	uniupd.send(string);
}

function readmsg(fromz, to, ofz, change){
	var string="from="+fromz+"&to="+to+"&of="+ofz+"&change="+change;
	var fun=function(){ 
			if (obj.readyState==4 && obj.status==200){
				var response=obj.responseText.split("~`*`*`~");
				//document.getElementById('here').innerHTML=obj.responseText;
				var change=response[0];
				var message=response[1];
				
				//before adding
				var p = document.getElementById('msgwindow');
				var scrolled=p.scrollTop;					//returns amount scrolled
				var totalheight=p.scrollHeight;;			//returns the total height of box
				var shownheight = parseInt(p.style.height);	//returns the shown height
				var scrollable = totalheight-shownheight;	//returns the max value of scrolltop
				if ((scrollable-scrolled)<15) {
					var scroll=1;
				}
				
				//adding
				if (change=="start"){
					startid=notlessthan(newid-15,0);
					lastid=newid;
					var fetchmore="<div id=\"fetchmore\" onclick=\"loadmore(this.id);\">Load More</div>";
					
					document.getElementById("msgwindow").innerHTML=fetchmore+message+document.getElementById("msgwindow").innerHTML;
					if (startid==0){
						document.getElementById('fetchmore').innerHTML='';
						document.getElementById('fetchmore').style.display='none';
					}
					scrollToBottom("msgwindow");
				} else if (change=="last"){
					lastid=newid;
					var par=document.getElementById("msgwindow");
					var child=document.getElementById("typing");
					par.removeChild(child);
					document.getElementById("msgwindow").innerHTML+=message+"<div id='typing'>"+typing+"</div>";
				} else if (change=="first"){
					var iniheight=document.getElementById("msgwindow").scrollHeight;			//returns the total height of box
					
					
					var par=document.getElementById("msgwindow");
					var child=document.getElementById("fetchmore");
					if(child) if(par.removeChild(child)){};
					
					var fetchmore="<div id=\"fetchmore\" onclick=\"loadmore(this.id);\">Load More</div>";
					startid=notlessthan(startid-6,0);
					document.getElementById("msgwindow").innerHTML=fetchmore+message+document.getElementById("msgwindow").innerHTML;
					if (startid==0){
						document.getElementById('fetchmore').innerHTML='';
						document.getElementById('fetchmore').style.display='none';
					}
				
					var finheight=document.getElementById("msgwindow").scrollHeight;			//returns the final total height of box
					if((finheight-iniheight)>2)
						document.getElementById("msgwindow").scrollTop=finheight-iniheight;
				}						
				
				//after adding
				if (scroll==1){
					scrollToBottom("msgwindow");
					scroll=0;
				}
			}		
	}
	send(string,fun);
}
function writemsg(){	//works now		//writes the info then when written assigns the id and time to written msg
	
	var message=(document.getElementById("msg").value.trim()).trim();
	if (message=="") return false;
	document.getElementById('msg').value="";
	
	var rawid="r"+(Math.random()*999999999999+1).toFixed(0);
	craw+="*"+rawid;
	var string="msg="+encodeURIComponent(message)+"&rawid="+rawid;
	
	//for writing on windows
	message = message.replace(/\r?\n/g, '<br>');
	message = smileyreplace(message);
	
	var div=document.createElement("div");
	div.id=rawid;
	div.title="";
	div.className="notsent message talk-bubble tri-right border round";
	
	var divin=document.createElement("div");
	divin.className='talktext';
				
	var text=document.createTextNode('');
	divin.appendChild(text);
	divin.innerHTML=message;
	div.appendChild(divin);

	var main=document.getElementById("msgwindow");
	var type=document.getElementById("typing");
	main.insertBefore(div, type);
	
	//scroll to bottom
	scrollToBottom("msgwindow");
	
	//for returned key
	
	
	
	//for and after writing on table
	var fun=function(){ 
			if (obj.readyState==4 && obj.status==200){
				//var response=obj.responseText.trim();
				//var arr=response.split("*");
				
				//var wins=document.getElementById(arr[2]);
				//wins.title=arr[0]+" "+arr[1];
				//wins.id="m"+arr[3];
				//wins.className="message talk-bubble tri-right border round right-in";	
			}
		}
	send (string, fun);
}
function picsend(returned){
	var rawid="r"+(Math.random()*999999999999+1).toFixed(0);
	craw+="*"+rawid;
	var string="msg="+encodeURIComponent("_:(("+returned+")):_")+"&rawid="+rawid;
	
	//for writing on windows
	var div=document.createElement("div");
	div.id=rawid;
	div.title="";
	div.className="notsent message talk-bubble tri-right border round";
	
	var divin=document.createElement("div");
	divin.className='talktext';
				
	var text=document.createTextNode('');
	divin.appendChild(text);
	divin.innerHTML="<img class='msgimage' src='images//uploads//"+returned+".jpg'>";
	div.appendChild(divin);

	var main=document.getElementById("msgwindow");
	var type=document.getElementById("typing");
	main.insertBefore(div, type);
	
	//scroll to bottom
	scrollToBottom("msgwindow");
	
	
	//for and after writing on table
	var fun=function(){};
	send (string, fun);
}

function type(){	//works now 
	var string="typing=1";
	var a=function(){}
	send(string, a);
}
function see(){		//works now
	//if (msgid<1) msgid=lastid;
	var string="seenstat=1&id="+lastid;
	var a=function(){
		if (obj.readyState==4 && obj.status==200){
			//document.getElementById('man').innerHTML=obj.responseText;
		}
	};
	send(string, a);
}
function del(msgid){	//works now
	var string="delmsg=true&id="+msgid;
	var a=function(){}
	send (string,a);
}

var shift="";
function pressdown(e){	//works now
	if (e.shiftKey) shift='on'; //if shifts is being pressed, turns shift on
	
	var key=unicode(e);			 
	if (shift!='on' && key==13){
		writemsg();
		document.getElementById("msg").value="";
	}
	else if((key>31)) {
		type();
	}
	
}	
function pressup(e){
	if (!e.shiftKey) shift='off';		
	
	var key=unicode(e); 
	if (shift!='on' && key==13){	//preventing bug of having line blank after sending
		document.getElementById("msg").value="";
	}
}

function scrollToBottom(element){
	window.setTimeout(function(){
		var objDiv = document.getElementById(element);
		objDiv.scrollTop = objDiv.scrollHeight;
		}, 50);
	window.setTimeout(function(){
		var objDiv = document.getElementById(element);
		objDiv.scrollTop = objDiv.scrollHeight;
		}, 100);
	window.setTimeout(function(){
		var objDiv = document.getElementById(element);
		objDiv.scrollTop = objDiv.scrollHeight;
		}, 500);


}
function maxScrollTop(id){	
	var p = document.getElementById(id);
	
	var scrolled=p.scrollTop				//returns amount scrolled
	var totalheight=p.scrollHeight;;	//returns the total height of box
	var shownheight = parseInt(p.style.height);		//returns the shown height
	var scrollable = totalheight-shownheight;		//returns the maxvalue of scrollTOP
	
	return scrollable;
}
function boxscroll(){		//fetches more message if top of box is reached
	var element1=document.getElementById("msgwindow");
	element1.onscroll=function(){
		if(element1.scrollTop<=20){
			var abc1=notlessthan(startid-6,0);
			var abc2=notlessthan(startid-1,0);
			readmsg(abc1,abc2,"all","first");
		}
	}
}


function newuser(usrid){
	if (usrid!="new"){
		lastid=0; newid=0; lastseen=""; startid=0; typing="";
		
		document.getElementById('msgwindow').innerHTML='<div id="typing"></div>';
		usr2=usrid;
		document.getElementById('usr2focus').innerHTML=usr2;
		document.getElementById('msg').placeholder='Type your message';
		document.getElementById('msg').removeAttribute("readonly");
		
		update();
		auto=setInterval(function(){update()}, 1000);
		
	} else if (usrid=="new"){
		usr2='';
		document.getElementById('usr2focus').innerHTML=usrid;
		document.getElementById('msgwindow').innerHTML='';
		document.getElementById('msg').innerHTML='';
		document.getElementById('msg').placeholder='Click on any user to send messages';
		document.getElementById('msg').setAttribute("readonly","yes");
		
		clearInterval(auto);
		clearInterval(auto);
		clearInterval(auto);
		clearInterval(auto);
		
		document.getElementById('msgwindow').innerHTML="<div id='put' class='paTRBL5' onclick=\"document.getElementById('additem').focus();\" style=\"font-size:18px;border:green solid 1px;background:white; min-height:75px;\"> <input type='text' id='additem' placeholder=\"Add Here...\" onkeydown=\"entered(event, this.id); this.style.width = ((this.value.length + 2) * 10) + 'px';\" class='items'></div><button class='button' onclick='sendit()'>Lets Start!</button>";
		
	}
}

function loadmore(id){
	var abc1=notlessthan(startid-6,0);	
	var abc2=notlessthan(startid-1,0); 
	readmsg(abc1,abc2,'all','first'); 
	document.getElementById(id).innerHTML="<img src='images/loading.gif'>";
}
function toggle(variable, opt1, opt2){
	if (variable==opt2) return opt1;
	else if (variable==opt1) return opt2;
}
function loadmenu(menu, ev){}

//menu
var smileymenu=0;
var picmenu=0;
var stickermenu=0;

function sendmenu(menu,state){
	if(menu=='smileymenu'){
		smileymenu=toggle(smileymenu,0,1);
		picmenu=0;
		stickermenu=0;
		if (state==1) smileymenu=1;
		if (state==0) smileymenu=0;
	}
	if(menu=='picmenu'){
		smileymenu=0;
		picmenu=toggle(picmenu,0,1);
		stickermenu=0;
		if (state==1) picmenu=1;
		if (state==0) picmenu=0;
	}
	if(menu=='stickermenu'){
		smileymenu=0;
		picmenu=0;
		stickermenu=toggle(stickermenu,0,1);;
		if (state==1) stickermenu=1;
		if (state==0) stickermenu=0;				
	}
	if (smileymenu==1) document.getElementById('smileymenu').style.display='block'; else document.getElementById('smileymenu').style.display='none';
	if (picmenu==1) document.getElementById('picmenu').style.display='block'; else document.getElementById('picmenu').style.display='none';
	if (stickermenu==1) document.getElementById('stickermenu').style.display='block'; else document.getElementById('stickermenu').style.display='none';
}
//menu items
function smileyclick(id){
	code=(document.getElementById(id).title).trim();
	document.getElementById('msg').value+=code;
	document.getElementById('msg').focus();
	sendmenu("smileymenu",0);
}
function smileyreplace(mess){
	var smilies=smiley.split("`~`~`");
	for (var ite=0; ite<smilies.length; ite++){
		mess = mess.replace(smilies[ite], '<img class="unselectable smileysmall" src="images\\smileys\\map.php?mapid='+smilies[ite]+'" width="20px" height="20px">');
	}
	mess = mess.replace(">:O", '<img class="unselectable smileysmall" src="images\smileys\map.php?mapid=:frus:" width="20px" height="20px"');
	mess = mess.replace("<3", '<img class="unselectable smileysmall" src="images\smileys\map.php?mapid=:plove:" width="20px" height="20px"');
	mess = mess.replace(">:(", '<img class="unselectable smileysmall" src="images\smileys\map.php?mapid=:frusad:" width="20px" height="20px"');
	return mess;
}


//for image send
function imageinputchange(){
	document.getElementById('errorsending').innerHTML='';
	var file=document.getElementById('file').value;
	file=file.split('\\');
	file=file[file.length-1];
	file=file.split('/');
	file=file[file.length-1];
	
	if(!file==""){
		if(fileValidate(file)!==true){
			sendcancel();
			document.getElementById('errorsending').innerHTML=fileValidate(file);
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
	document.getElementById('errorsending').innerHTML='';
	document.getElementById('sendingimg').src="";
	document.getElementById('file1').innerHTML='Select Image';
	document.getElementById('file').value='';
	document.getElementById('filename').innerHTML="";
	document.getElementById('sendingimgcont').style.display='none';
	document.getElementById('caption').style.display='none';
	document.getElementById('send').style.display='none';
	document.getElementById('cancels').style.display='none';
	document.getElementById('progress').style.display='none';
	document.getElementById('percent').innerHTML='0%';
	document.getElementById('bar').style.width='0%';
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
			sendmenu('picmenu',0);
			var returned=xhr.responseText;
			//returned=parseInt(returned);
			picsend(returned);
		},
		error: function(xhr){
			//status.html(xhr.responseText);
		}
	}); 
}

//for interests input
function entered(e,id){
	var key=unicode(e);			 
	var value=document.getElementById(id).value;		
	var string=readystring().split("_!~~!_");
	var rawvalue=value.trim().toLowerCase();
	if (key==13 && value!=""){
		if (string.length > 0){
			for (var ite=0; ite<string.length; ite++){
				if (string[ite]==rawvalue){
					document.getElementById(id).value='';
					return false;
				}
			}
		}
		document.getElementById(id).value='';
		var divnode=document.createElement('div');
		divnode.innerHTML="<div class='closecross unselectable' onclick='closethis(this);'><div class='insideclosecross'>&#215 </div></div><div class='value'>"+value+"</div><div class='closecrossspace'></div>";
		divnode.className="items";
		
		var before=document.getElementById('additem');
		var parent=document.getElementById('put');
		parent.insertBefore(divnode,before);
	}
}
function closethis(id){		//just send as this and it will close the parent
	var parent=id.parentNode
	var grandparent=parent.parentNode
	grandparent.removeChild(parent);
}
function readystring(){
	var elements=document.getElementsByClassName('value');
	var string="";
	if (elements.length==0) return '';
	string=elements[0].innerHTML;
	for (var ite=1; ite<elements.length; ite++){
		string+="_!~~!_"+elements[ite].innerHTML.trim().toLowerCase();
	}
	return string;
}
function sendit(){
	string="jpt="+Math.random()+"&int="+encodeURIComponent(readystring());
	if (window.XMLHttpRequest) var st=new XMLHttpRequest(); else var st=new ActiveXObject("Microsoft.XMLHTTP");
	st.onreadystatechange=function(){ 
			if (st.readyState==4 && st.status==200){
				var response=st.responseText.trim().split('_!~~!_');
				if (parseInt(response[0])==1){
					newuser(response[1]);
				} else document.getElementById('mess1').innerHTML=response[1];
			}
		}
	
	st.open("POST","UserCompare.php",true);
	st.setRequestHeader("content-type","application/x-www-form-urlencoded");
	st.send(string);
}	
</script>
	
<hr class='break'>
<?php include 'footer.php'; ?>	
</body>
</html>