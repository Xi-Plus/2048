<html>
<?php
	$url='http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
?>
<head>
	<meta charset="utf-8">
	<title>2048</title>
	<meta name=viewport content="width=device-width, initial-scale=1">
	<link href="css.css" rel="stylesheet" type="text/css">
	<script>
	<?php
		@$width=$_GET["width"];
		if($width=="")$width=4;
		echo "var width=".$width.";";
		@$height=$_GET["height"];
		if($height=="")$height=4;
		echo "var height=".$height.";";
		?>
		var cellvalue=[
	<?php
	for($i=0;$i<=$height*2+1;$i++){
		echo "[";
		for($j=0;$j<=$width*2;$j++){
			if($i%2!=0&&$j%2!=0)echo "0,";
			else echo "null,";
		}
		echo "],";
	}
	?>
		];
	<?php
		@$cell=explode(";",urldecode($_GET["cell"]));
		foreach($cell as $temp){
			$temp=explode(",",$temp);
			if($temp[0]==""||$temp[1]=="")continue;
			echo "cellvalue[".($temp[0]*2-1)."][".($temp[1]*2-1)."]='none';";
		}
		@$wall=explode(";",urldecode($_GET["wall"]));
		foreach($wall as $temp){
			$temp=explode(",",$temp);
			if($temp[0]==""||$temp[1]=="")continue;
			echo "cellvalue[".($temp[0])."][".($temp[1])."]='none';";
		}
	?>
	function checktype(i,j){
		if(i%2==1&&j%2==1)return 1;
		else if(i%2==1&&j%2==0)return 2;
		else if(i%2==0&&j%2==1)return 3;
		else return 4;
	}
	function start(){
		for(var i=0;i<=height*2;i++){
			for(var j=0;j<=width*2;j++){
				if(checktype(i,j)==1){
					if(cellvalue[i][j]=="none"){
						document.all["cell_"+i+"_"+j].classList.add("cellnone");
					}else {
						document.all["cell_"+i+"_"+j].classList.add("cell");
					}
				}else if(checktype(i,j)==2){
					document.all["cell_"+i+"_"+j].classList.add("wallright");
				}else if(checktype(i,j)==3){
					document.all["cell_"+i+"_"+j].classList.add("wallbottom");
				}else if(checktype(i,j)==4){
					document.all["cell_"+i+"_"+j].classList.add("wallother");
				}
				if(checktype(i,j)!=1){
					if(cellvalue[i][j]=="none"&&checktype(i,j)!=4){
						document.all["cell_"+i+"_"+j].classList.add("wallnone");
					}else {
						document.all["cell_"+i+"_"+j].classList.add("wall");
					}
				}
			}
		}
	}
	function show(){
		for(var i=1;i<=height*2;i+=2){
			for(var j=1;j<=width*2;j+=2){
				if(cellvalue[i][j]!="none"){
					if(cellvalue[i][j]==0){
						document.all["cell_"+i+"_"+j].innerHTML="";
						document.all["cell_"+i+"_"+j].className = '';
						document.all["cell_"+i+"_"+j].classList.add("cell");
					}else {
						document.all["cell_"+i+"_"+j].innerHTML=cellvalue[i][j];
						document.all["cell_"+i+"_"+j].className = '';
						document.all["cell_"+i+"_"+j].classList.add("tdcell");
						document.all["cell_"+i+"_"+j].classList.add("tile-"+cellvalue[i][j]);
					}
				}
			}
		}
	}
	function createnew(){
		var space=0;
		for(var i=1;i<=height*2;i+=2){
			for(var j=1;j<=width*2;j+=2){
				if(cellvalue[i][j]!="none"&&cellvalue[i][j]==0){
					space++;
				}
			}
		}
		var add=Math.floor(Math.random()*space);
		space=0;
		for(var i=1;i<=height*2;i+=2){
			for(var j=1;j<=width*2;j+=2){
				if(cellvalue[i][j]!="none"&&cellvalue[i][j]==0){
					if(space==add){
						cellvalue[i][j]=2;
						return ;
					}
					space++;
				}
			}
		}
	}
	function checklose(){
		for(var i=1;i<=height*2;i+=2){
			for(var j=1;j<=width*2-2;j+=2){
				if(cellvalue[i][j]!="none"&&cellvalue[i][j+1]!="none"){
					if(!((cellvalue[i][j]*cellvalue[i][j+2]==0)^(cellvalue[i][j]!=cellvalue[i][j+2]))){
						return false;
					}
				}
			}
		}
		for(var i=1;i<=height*2-2;i+=2){
			for(var j=1;j<=width*2;j+=2){
				if(cellvalue[i][j]!="none"&&cellvalue[i+1][j]!="none"){
					if(!((cellvalue[i][j]*cellvalue[i+2][j]==0)^(cellvalue[i][j]!=cellvalue[i+2][j]))){
						return false;
					}
				}
			}
		}
		return true;
	}
	var isautorun=false;
	function autorun(n){
		if(n=="edit"){
			if(isautorun){
				isautorun=false;
				autorun("stop");
			}else {
				isautorun=true;
				autorun("start");
			}
		}else if(n=="start"){
			isautorun=true;
			autorunbut.value="Stop";
			autorun();
		}else if(n=="stop"){
			isautorun=false;
			autorunbut.value="Auto Run";
		}else if(isautorun){
			var code=Math.floor(Math.random()*4)+1;
			move(code);
			if(n!="again")setTimeout(function(){autorun()},sleep.value);
		}
	}
	var scorevalue=0;
	var movecountvalue=0;
	function move(n){
		var last=0;
		var ismove=false;
		var scoretemp=0;
		if(n==1){
			for(var i=1;i<=height*2;i+=2){
				var lastmerge=-1;
				for(var j=3;j<=width*2-1;j+=2){
					if(cellvalue[i][j]=="none")continue;
					var temp=j;
					while(cellvalue[i][temp]!=0&&temp>=3&&cellvalue[i][temp-2]==0&&cellvalue[i][temp-1]!="none"){
						cellvalue[i][temp-2]=cellvalue[i][temp];
						cellvalue[i][temp]=0;
						temp-=2;
						ismove=true;
					}
					if(cellvalue[i][temp]!=0&&temp>=3&&temp>lastmerge&&cellvalue[i][temp]==cellvalue[i][temp-2]&&cellvalue[i][temp-1]!="none"){
						cellvalue[i][temp-2]*=2;
						scoretemp+=cellvalue[i][temp-2];
						cellvalue[i][temp]=0;
						ismove=true;
						lastmerge=temp;
					}
				}
			}
		}else if(n==3){
			for(var i=1;i<=height*2;i+=2){
				var lastmerge=width*2;
				for(var j=width*2-3;j>=1;j-=2){
					if(cellvalue[i][j]=="none")continue;
					var temp=j;
					while(cellvalue[i][temp]!=0&&temp<=width*2-2&&cellvalue[i][temp+2]==0&&cellvalue[i][temp+1]!="none"){
						cellvalue[i][temp+2]=cellvalue[i][temp];
						cellvalue[i][temp]=0;
						temp+=2;
						ismove=true;
					}
					if(cellvalue[i][temp]!=0&&temp<=width*2-2&&temp<lastmerge&&cellvalue[i][temp]==cellvalue[i][temp+2]&&cellvalue[i][temp+1]!="none"){
						cellvalue[i][temp+2]*=2;
						scoretemp+=cellvalue[i][temp+2];
						cellvalue[i][temp]=0;
						ismove=true;
						lastmerge=temp;
					}
				}
			}
		}else if(n==2){
			for(var i=1;i<=width*2;i+=2){
				var lastmerge=-1;
				for(var j=3;j<=height*2;j+=2){
					if(cellvalue[j][i]=="none")continue;
					var temp=j;
					while(cellvalue[temp][i]!=0&&temp>=3&&cellvalue[temp-2][i]==0&&cellvalue[temp-1][i]!="none"){
						cellvalue[temp-2][i]=cellvalue[temp][i];
						cellvalue[temp][i]=0;
						temp-=2;
						ismove=true;
					}
					if(cellvalue[temp][i]!=0&&temp>=3&&temp>lastmerge&&cellvalue[temp][i]==cellvalue[temp-2][i]&&cellvalue[temp-1][i]!="none"){
						cellvalue[temp-2][i]*=2;
						scoretemp+=cellvalue[temp-2][i];
						cellvalue[temp][i]=0;
						ismove=true;
						lastmerge=temp;
					}
				}
			}
		}else if(n==4){
			for(var i=1;i<=width*2;i+=2){
				var lastmerge=height*2+2;
				for(var j=height*2-3;j>=1;j-=2){
					if(cellvalue[j][i]=="none")continue;
					var temp=j;
					while(cellvalue[temp][i]!=0&&temp<=height*2-2&&cellvalue[temp+2][i]==0&&cellvalue[temp+1][i]!="none"){
						cellvalue[temp+2][i]=cellvalue[temp][i];
						cellvalue[temp][i]=0;
						temp+=2;
						ismove=true;
					}
					if(cellvalue[temp][i]!=0&&temp<=height*2-2&&temp<lastmerge&&cellvalue[temp][i]==cellvalue[temp+2][i]&&cellvalue[temp+1][i]!="none"){
						cellvalue[temp+2][i]*=2;
						scoretemp+=cellvalue[temp+2][i];
						cellvalue[temp][i]=0;
						ismove=true;
						lastmerge=temp;
					}
				}
			}
		}
		if(ismove){
			movecountvalue++;
			createnew();
		}
		show();
		divmovecount.innerHTML=movecountvalue;
		scorevalue+=scoretemp;
		divscore.innerHTML=scorevalue;
		if(checklose()){
			sharescorefeed["data-href"]=scoreurl+"&move="+movecountvalue+"&score="+scorevalue;
			sharescoremessage["data-href"]=scoreurl+"&move="+movecountvalue+"&score="+scorevalue;
			autorun("stop");
			alert("Game Over\nMove: "+movecountvalue+"\nScore: "+scorevalue);
		}
		if(!ismove){
			autorun("again");
		}
	}
	function keydown(){
		switch(event.keyCode){
			case 37: move(1);return false;
			case 38: move(2);return false;
			case 39: move(3);return false;
			case 40: move(4);return false;
			case 65: move(1);return false;
			case 87: move(2);return false;
			case 68: move(3);return false;
			case 83: move(4);return false;
		}
	}
	document.onkeydown=keydown;
	</script>
</head>
<body>
<div id="fb-root"></div>
<script>(function(d, s, id) {
	var js, fjs = d.getElementsByTagName(s)[0];
	if (d.getElementById(id)) return;
	js = d.createElement(s); js.id = id;
	js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&appId=703921369635733&version=v2.3";
	fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
<center>
<h1>2048</h1>
<a href="create.php" target="_blank">Create</a>
<a href="create.php?width=<?php echo $_GET["width"]; ?>&height=<?php echo $_GET["height"]; ?>&cell=<?php echo $_GET["cell"]; ?>&wall=<?php echo $_GET["wall"]; ?>" target="_blank">Edit This</a>
<br>
Share This Game: 
<a href="" onClick="prompt('Share Link','<?php echo urldecode($url); ?>');return false;">Link</a>
<div class="fb-share-button" data-href="<?php echo urldecode($url); ?>" data-layout="button"></div>
<div class="fb-send" data-href="<?php echo urldecode($url); ?>" data-colorscheme="light"></div>
<br>
<input type="button" value="New Game" onClick="location.reload();">
<input type="number" id="sleep" value="50" style="width: 70px;">ms
<input type="button" id="autorunbut" value="Auto Run" onClick="autorun('edit');">
<br><br>
<table width="0" border="0" cellspacing="5" cellpadding="0">
	<tr>
		<td>
			<div class="message">
				MOVE
				<div id="divmovecount">0</div>
			</div>
		</td>
		<td>
			<div class="message">
				SCORE
				<div id="divscore">0</div>
			</div>
		</td>
	</tr>
</table>
<br>
<table id="gamecontainer" width="0" border="0" cellspacing="0" cellpadding="0">
	<?php
	for($i=0;$i<=$height*2;$i++){
	?>
	<tr>
		<?php
		for($j=0;$j<=$width*2;$j++){
		?>
		<td class="tdcell" id="<?php echo "cell_".$i."_".$j; ?>"></td>
		<?php
		}
		?>
	</tr>
	<?php
	}
	?>
</table>
<hr>
<?php
include("../function/developer.php");
?>
</center>
<script>
	var startX = 0, startY = 0;
	var istouchstop=true;
	function touchSatrtFunc(evt) {
		try
		{
			evt.preventDefault();
			var touch = evt.touches[0];
			var x = Number(touch.pageX);
			var y = Number(touch.pageY);
			startX = x;
			startY = y;
		}
		catch (e) {
		}
	}
	function touchMoveFunc(evt) {
		try{
			evt.preventDefault();
			var touch = evt.touches[0];
			var x = Number(touch.pageX);
			var y = Number(touch.pageY);
			var dx=x - startX;
			var dy=y - startY;
			if(Math.abs(dx)>Math.abs(dy)&&Math.abs(dx)>50&&istouchstop){
				istouchstop=false;
				if(dx>0)move(3);
				else move(1);
			}else if(Math.abs(dy)>50&&istouchstop){
				istouchstop=false;
				if(dy>0)move(4);
				else move(2);
			}
		}
		catch (e) {
		}
	}
	function touchEndFunc(evt) {
		try {
			evt.preventDefault(); 
			istouchstop=true;
		}
		catch (e) {
		}
	}
	function bindEvent() {
		gamecontainer.addEventListener('touchstart', touchSatrtFunc, false);
		gamecontainer.addEventListener('touchmove', touchMoveFunc, false);
		gamecontainer.addEventListener('touchend', touchEndFunc, false);
	}
	function isTouchDevice() {
		try {
			document.createEvent("TouchEvent");
			bindEvent();
		}
		catch (e) {
		}
	}
	isTouchDevice();
	start();
	createnew();
	createnew();
	show();
	move(0);
</script>
</body>
</html>
