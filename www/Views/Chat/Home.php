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
<h1>Chat Rooms</h1>
<h3>Search: <input type="text" id="Search" name="Search" onkeyup="GetRooms(this.value)"></h3>
<div id="RoomsTable">
</div>
<div>
	<input type="button" class="Count" name="Count10" value="10" onclick="CurCount=this.value; GetRooms();">
	<input type="button" class="Count" name="Count25" value="25" onclick="CurCount=this.value; GetRooms();">
	<input type="button" class="Count" name="Count50" value="50" onclick="CurCount=this.value; GetRooms();">
</div>
<a href="/Chat/MakeRoom/" style="background-color: #eeeeee;"><div><h2>Make Room</h2></div></a>
<script>
var CurCount = 10;
var CountIndex = 0;
function GetRooms(SearchStr) {
	if(!SearchStr){
		SearchStr = document.getElementById('Search').value;
	}
	CountIndex = Math.max(CountIndex, 0);
	$.ajax({
		url: '/Chat/GetRooms/',
		type: 'POST',
		data: $.param({
			Search: SearchStr,
			Count: CurCount,
			CountIndex: CountIndex
		}),
		contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
		context: document.body,
		success: function(response) {
			$("#RoomsTable").html(response);
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
GetRooms();
</script>