<!DOCTYPE html>
<html>
<?php
	$url='http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
?>
<head>
	<meta charset="utf-8">
	<title>2048 create</title>
	<meta name=viewport content="width=device-width, initial-scale=1">
	<link href="createcss.css" rel="stylesheet" type="text/css">
	<link rel="shortcut icon" href="http://pc2.tfcis.org/xiplus/2048/icon.png">
</head>
<body>
<center>
<h1>2048 create</h1>
<?php
	@$width=$_GET["width"];
	if($width=="")$width=4;
	@$height=$_GET["height"];
	if($height=="")$height=4;
?>
<form action="" method="get">
Step 1<br>
width=<input name="width" type="number" value="<?php echo $width; ?>"><br>
height=<input name="height" type="number" value="<?php echo $height; ?>"><br>
<input type="submit" value="Submit">
</form>
<br><br>
<script>
function checktype(i,j){
	if(i%2==1&&j%2==1)return 1;
	else if(i%2==1&&j%2==0)return 2;
	else if(i%2==0&&j%2==1)return 3;
	else return 4;
}
function edit(i,j){
	if(document.all["cell["+i+"]["+j+"]"].value==""){
		document.all["cell["+i+"]["+j+"]"].value="none";
		document.all["tcell["+i+"]["+j+"]"].value="none";
		if(checktype(i,j)==1){
			document.all["cell["+i+"]["+j+"]"].className = '';
			document.all["cell["+i+"]["+j+"]"].classList.add("cellnone");
		}else if(checktype(i,j)==2){
			document.all["cell["+i+"]["+j+"]"].classList.remove("wall");
			document.all["cell["+i+"]["+j+"]"].classList.add("wallnone");
		}else if(checktype(i,j)==3){
			document.all["cell["+i+"]["+j+"]"].classList.remove("wall");
			document.all["cell["+i+"]["+j+"]"].classList.add("wallnone");
		}
	}
	else {
		document.all["cell["+i+"]["+j+"]"].value="";
		document.all["tcell["+i+"]["+j+"]"].value="";
		if(checktype(i,j)==1){
			document.all["cell["+i+"]["+j+"]"].className = '';
			document.all["cell["+i+"]["+j+"]"].classList.add("cell");
		}else if(checktype(i,j)==2){
			document.all["cell["+i+"]["+j+"]"].classList.remove("wallnone");
			document.all["cell["+i+"]["+j+"]"].classList.add("wall");
		}else if(checktype(i,j)==3){
			document.all["cell["+i+"]["+j+"]"].classList.remove("wallnone");
			document.all["cell["+i+"]["+j+"]"].classList.add("wall");
		}
	}
}
</script>
<form action="produce.php" method="post">
Step 2<br>
width=<input name="pwidth" type="number" value="<?php echo $width; ?>" size="5" disabled>
height=<input name="pheight" type="number" value="<?php echo $height; ?>" size="5" disabled>
<table width="0" border="0" cellspacing="0" cellpadding="0">
	<?php
	function checktype($i,$j){
		if($i%2==1&&$j%2==1)return 1;
		else if($i%2==1&&$j%2==0)return 2;
		else if($i%2==0&&$j%2==1)return 3;
		else return 4;
	}
	for($i=1;$i<$height*2;$i++){
	?>
	<tr>
		<?php
		for($j=1;$j<$width*2;$j++){
		?>
		<td>
		<input class="<?php 
		if(checktype($i,$j)==1)echo "cell";
		else if(checktype($i,$j)==2)echo "wall wallright";
		else if(checktype($i,$j)==3)echo "wall wallbottom";
		else echo "wall blockother";
		?>" id="cell[<?php echo $i; ?>][<?php echo $j; ?>]" name="cell[<?php echo $i; ?>][<?php echo $j; ?>]" type="button" <?php echo (checktype($i,$j)==4?"disabled":""); ?> onClick="edit(<?php echo $i; ?>,<?php echo $j; ?>);">
		<input name="tcell[<?php echo $i; ?>][<?php echo $j; ?>]" type="hidden" id="tcell[<?php echo $i; ?>][<?php echo $j; ?>]" value="">
		</td>
		<?php
		}
		?>
	</tr>
	<?php
	}
	?>
</table><br>
<input type="submit" value="Submit">
</form>
<hr>
<?php
include("../function/developer.php");
?>
</center>
<script>
<?php 
	$cell=explode(";",urldecode($_GET["cell"]));
	foreach($cell as $temp){
		$temp=explode(",",$temp);
		if($temp[0]==""||$temp[1]=="")continue;
		echo "edit(".($temp[0]*2-1).",".($temp[1]*2-1).");";
	}
	$wall=explode(";",urldecode($_GET["wall"]));
	foreach($wall as $temp){
		$temp=explode(",",$temp);
		if($temp[0]==""||$temp[1]=="")continue;
		echo "edit(".($temp[0]).",".($temp[1]).");";
	}
?>
</script>
</body>
</html>
