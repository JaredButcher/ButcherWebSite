<table>
<th>Name</th>
<th>Discription</th>
<th>Owner</th>
<th class="ts">Created</th>
<?php
	if($GLOBALS['VD']['Rooms']){
		foreach($GLOBALS['VD']['Rooms'] as $Room){
			echo "<tr><td><a href=\"/Chat/Room/{$Room["id"]}/\">{$Room["name"]}</a></td>";
			echo "<td>{$Room["info"]}</td>";
			echo "<td>{$Room["username"]}</td>";
			echo "<td class = \"ts\">{$Room["ts"]}</td></tr>";
		}
	}
?>
</table>
<div>
<?php
	$Count = $_POST['Count'];
	$Index = $_POST['CountIndex'];
	if($Index > 0){
		echo '<input type="button" name="CountDown" value="<" onclick="CountIndex-=1; GetRooms();">';
	}
	echo '<input style="width:40px;text-align:center;" value='.($Index + 1)."/".(ceil($GLOBALS['VD']['Total'] / $Count)).'>';
	if($GLOBALS['VD']['Total'] > $Count * ($Index + 1)){
		echo '<input type="button" name="CountUp" value=">" onclick="CountIndex+=1; GetRooms();">';
	}
?>
</div>