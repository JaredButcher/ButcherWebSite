<style>
	td{
		padding: 10px 10px 10px 10px;
	}
	tr {
		border-top: 1px solid black;
	}
	table{
		margin: 0px;
		width: 100%;
	}
	.ts{
		float: right;
	}
</style>
<h1>Chat Rooms</h1>
<table>
<th>Name</th>
<th>Discription</th>
<th>Owner</th>
<th class="ts">Created</th>
<?php
	foreach($GLOBALS['VD']['Rooms'] as $Room){
		echo "<tr><td><a href=\"/Chat/Room/{$Room["id"]}/\">{$Room["name"]}</a></td>";
		echo "<td>{$Room["info"]}</td>";
		echo "<td>{$Room["owner"]}</td>";
		echo "<td class = \"ts\">{$Room["ts"]}</td></tr>";
	}
?>
</table>
<a href="/Chat/MakeRoom/" style="background-color: #eeeeee;"><div><h2>Make Room</h2></div></a>