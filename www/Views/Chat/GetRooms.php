<table>
<th>Name</th>
<th>Discription</th>
<th>Owner</th>
<th class="ts">Created</th>
<?php
	foreach($GLOBALS['VD']['Rooms'] as $Room){
		echo "<tr><td><a href=\"/Chat/Room/{$Room["id"]}/\">{$Room["name"]}</a></td>";
		echo "<td>{$Room["info"]}</td>";
		echo "<td>{$Room["username"]}</td>";
		echo "<td class = \"ts\">{$Room["ts"]}</td></tr>";
	}
?>
</table>
<div>
	<input type="button" name="CountDown" value="<" onclick="CountIndex+=1; GetRooms();">
	<p id="CountIndex"></p>
	<input type="button" name="CountUp" value=">" onclick="CountIndex-=1; GetRooms();">
	
	<input type="button" name="Count10" value="10" onclick="CurCount=this.value; GetRooms();">
	<input type="button" name="Count25" value="25" onclick="CurCount=this.value; GetRooms();">
	<input type="button" name="Count50" value="50" onclick="CurCount=this.value; GetRooms();">
</div>