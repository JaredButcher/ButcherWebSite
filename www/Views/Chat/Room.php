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

<?php echo "<form id=\"{$Post["id"]}\" method=\"Post\" action=\"/Chat/Room/{$GLOBALS['VD']['Room']['id']}\">";
echo "<h2>".$GLOBALS['VD']['Room']['name']."</h2>";
echo "<p>".$GLOBALS['VD']['Room']['info']."</p>";
echo "<p>Owner: ".$GLOBALS['VD']['Room']['username']."</p>";
echo "<p>Created: ".$GLOBALS['VD']['Room']['ts']."</p>";
if(isset($_SESSION['Id']) && ($GLOBALS['Secrets']['RoomDelPower'] <= $_SESSION["Power"] || $GLOBALS['VD']['Room']['owner'] == $_SESSION['Id'])){
echo "<button type=\"submit\" name=\"DeleteRoom\" value=\"{$GLOBALS['VD']['Room']['id']}\" onclick=\"ConfirmRoom();return false;\"/>Delete Room</button>";
}
echo "<table>";
foreach($GLOBALS['VD']['Posts'] as $Post){
	echo "<tr><td>{$Post["username"]}</td>";
	echo "<td>{$Post["content"]}</td>";
	echo "<td class = \"ts\">{$Post["ts"]}</td>";
	if( isset($_SESSION['Id']) && ($GLOBALS['Secrets']['PostDelPower'] <= $_SESSION["Power"] || $GLOBALS['VD']['Room']['owner'] == $_SESSION['Id'] || $Post['owner'] == $_SESSION['Id'])){
		echo "<td class = \"ts\"><button type=\"submit\" name=\"DeletePost\" value=\"{$Post['id']}\" onclick=\"submit();\"/>Delete</button></td></tr>";
	}
}
?>
</form>
</table>
<form method="Post" id="Changes" action="/Chat/Room/<?php echo $GLOBALS['VD']['Room']['id']; ?>/">
	<table>
	<tr>
		<td style="width: 100px;"><label for="Content"><input type="Submit" value="Post"></label></td>
		<td><input class="tinymce" type="text" name="Content" style="width: 100%"></td>
	</tr>
	</table>
</form>
<script>
	function ConfirmRoom(){
		if(confirm("Are you sure you want to delete this room?")){
			document.getElementById('DeleteRoom').submit();
		}
	}
</script>
<script type="text/javascript" src="/Content/Plugins/tinymce/tinymce.min.js"></script>
<script type="text/javascript" src="/Content/JS/TinyMceInti.js"></script>