<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>2048</title>
</head>
<body>
<center>
<?php
$text=$_POST["tcell"];
$cell="";
$wall="";
function checktype($i,$j){
	if($i%2==1&&$j%2==1)return 1;
	else if($i%2==1&&$j%2==0)return 2;
	else if($i%2==0&&$j%2==1)return 3;
	else return 4;
}
foreach($text as $i => $temp){
	foreach($temp as $j => $temp2){
		if($text[$i][$j]=="none"){
			if(checktype($i,$j)==1){
				$cell.=($i/2+0.5).",".($j/2+0.5).";";
			}else {
				$wall.=$i.",".$j.";";
			}
		}
	}
}
echo $url="./?width=".$_POST["pwidth"]."&height=".$_POST["pheight"]."&cell=".$cell."&wall=".$wall;
header("Location: ".$url);
?>
</center>
</body>
</html>