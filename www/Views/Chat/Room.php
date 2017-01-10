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
<?php echo "<h2>".$GLOBALS['VD']['Room']['name']."</h2>";
echo "<p>".$GLOBALS['VD']['Room']['info']."</p>";
echo "<p>Owner: ".$GLOBALS['VD']['Room']['owner']."</p>";
echo "<p>Created: ".$GLOBALS['VD']['Room']['ts']."</p>";
echo "<table>";
foreach($GLOBALS['VD']['Posts'] as $Post){
	echo "<tr><td>{$Post["owner"]}</td>";
	echo "<td>{$Post["content"]}</td>";
	echo "<td class = \"ts\">{$Post["ts"]}</td></tr>";
}
?>
</table>
<form method="Post" id="Changes" action="/Chat/Room/<?php echo $GLOBALS['VD']['Room']['id']; ?>/">
	<table>
	<tr>
		<td style="width: 100px;"><label for="Content"><input type="Submit" value="Post"></label></td>
		<td><input type="text" name="Content" style="width: 100%"></td>
	</tr>
	</table>
</form>