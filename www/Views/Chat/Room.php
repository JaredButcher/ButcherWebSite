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
	#CurCount{
		background-color: gray;
	}
</style>

<?php 
	echo "<form method=\"Post\" action=\"/Chat/Room/{$GLOBALS['VD']['Room']['id']}\">";
	echo "<h2>".$GLOBALS['VD']['Room']['name']."</h2>";
	echo "<p>".$GLOBALS['VD']['Room']['info']."</p>";
	echo "<p>Owner: ".$GLOBALS['VD']['Room']['username']."</p>";
	echo "<p>Created: ".$GLOBALS['VD']['Room']['ts']."</p>";
	if(isset($_SESSION['Id']) && ($GLOBALS['Secrets']['RoomDelPower'] <= $_SESSION["Power"] || $GLOBALS['VD']['Room']['owner'] == $_SESSION['Id'])){
		echo "<button type=\"submit\" name=\"DeleteRoom\" value=\"{$GLOBALS['VD']['Room']['id']}\" onclick=\"ConfirmRoom();return false;\"/>Delete Room</button>";
	}
?>
</form>
<div id="Posts">
</div>
<div>
	<input type="button" class="Count" name="Count10" value="10" onclick="CurCount=this.value; GetPosts();">
	<input type="button" class="Count" name="Count25" value="25" onclick="CurCount=this.value; GetPosts();">
	<input type="button" class="Count" name="Count50" value="50" onclick="CurCount=this.value; GetPosts();">
</div>
<form method="Post" id="Changes" action="/Chat/Room/<?php echo $GLOBALS['VD']['Room']['id']; ?>/">
	<table>
	<tr>
		<td style="width: 100px;"><label for="Content"><input type="Submit" value="Post"></label></td>
		<td><input class="tinymce" type="text" name="Content" style="width: 100%"></td>
	</tr>
	</table>
</form>
<script>
	var CurCount = 10;
	var CountIndex = 0;
	function ConfirmRoom(){
		if(confirm("Are you sure you want to delete this room?")){
			document.getElementById('DeleteRoom').submit();
		}
	}
	function GetPosts(){
		CountIndex = Math.max(CountIndex, 0);
		$.ajax({
			url: '/Chat/GetPosts/',
			type: 'POST',
			data: $.param({
				Room: <?php echo $GLOBALS['VD']['Room']['id']; ?>,
				Count: CurCount,
				CountIndex: CountIndex
			}),
			contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
			context: document.body,
			success: function(response) {
				$("#Posts").html(response);
				var Counts = document.getElementsByClassName("Count");
				for(var Name in Counts){
					Counts[Name].id = "";
				}
				document.getElementsByName("Count" + CurCount)[0].id = "CurCount";
			},
			error: function () {
				alert("error");
			}
		});
	}
	GetPosts();
</script>
<script type="text/javascript" src="/Content/Plugins/tinymce/tinymce.min.js"></script>
<script type="text/javascript" src="/Content/JS/TinyMceInti.js"></script>