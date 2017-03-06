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
<a href="/Chat/MakeRoom/" style="background-color: #eeeeee;"><div><h2>Make Room</h2></div></a>
<script>
var CurCount = 10;
var CountIndex = 0;
function GetRooms(SearchStr) {
	if(!SearchStr){
		SearchStr = document.getElementById('Search').value;
	}
	$.ajax({
		url: '/Chat/GetRooms/',
		type: 'POST',
		data: $.param({
			Search: SearchStr,
			Count: CurCount,
			CountIndex: 0
		}),
		contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
		context: document.body,
		success: function(response) {
			$("#RoomsTable").html(response);
			document.getElementsByName("Count" + CurCount)[0].id = "CurCount";
			document.getElementById("CountIndex").html = CountIndex;
		},
		error: function () {
			alert("error");
		}
	});
}
GetRooms();
</script>