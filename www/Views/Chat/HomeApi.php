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