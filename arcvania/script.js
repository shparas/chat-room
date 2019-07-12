var out=document.getElementById("java");
window.onscroll=scrolled;
window.onresize=scrolled;

function scrolled(){
	var header=document.getElementById("header");
	var headercover=document.getElementById("headercover");
	var scrolledBy=window.pageYOffset;
	var headerelement=document.getElementsByClassName("headerelement");
	if(!scrolledBy){
		scrolledBy=window.scrollY;
	}
	header.style.width=headercover.offsetWidth+"px";
	$application=document.getElementById("pagecover").offsetHeight;
	//filler($application);
	if ($application==undefined){$application=0;}
	if (scrolledBy>=$application){
		header.style.position="fixed";
		header.style.top="0";
		header.style.left=0-window.pageXOffset+"px";
		if(scrolledBy>=($application+5)){
			for(var i=0; i<headerelement.length; i++){
				headerelement[i].style.opacity="0.8";
			}
		}
		else{
			for(var i=0; i<headerelement.length; i++){
				headerelement[i].style.opacity="1.0";
			}
		}
	}
	else{
		header.style.position="static";
		header.style.width="100%";
		for(var i=0; i<headerelement.length; i++){
			headerelement[i].style.opacity="1.0";
		}
	}
}


function ajax(){
	var obj;
	function loadDoc(url, func){
		if (window.XMLHttpRequest){
			obj=new XMLHttpRequest();
		}
		else{
			obj=new ActiveXObject("Microsoft.XMLHTTP");
		}
		obj.open("GET",url,true);
		obj.send();
		obj.onreadystatechange=func;
	}
	function myFunction(){
		loadDoc("messagefetcher.php?usr1="+"abc"+"&usr2="+"def"+"&from="+"1"+"&to="+"10",function(){
									if (obj.readyState==4 && obj.status==200){
										document.getElementById("").innerHTML=xmlhttp.responseText;
									}
			});
	}		
}
function filesent(obj){
	var file = obj.value;
	var fileName = file.split("\\");
	document.getElementById("uploadfilebtn").innerHTML = fileName[fileName.length-1];
	//document.myForm.submit();
	document.getElementById('sub').click();
	event.preventDefault();
}
function notlessthan(number, than){
	if (number<than) number=than;
	return number;
}