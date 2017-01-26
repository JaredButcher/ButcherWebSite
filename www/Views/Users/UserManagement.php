<style>
th, td {
    padding: 15px;
    text-align: left;
}
th{
	background-color: #bbbbbb;
}
table, td, th {
	border: 1px solid black;
}
</style>
<h1>User Management</h1>
<form id="DelUsers" method="Post" action="\Users\UserManagement\">
	<table>
	<tr>
		<th>Id</th>
		<th>Username</th>
		<th>Power</th>
		<th>Name</th>
		<th>Email</th>
		<th>Edit</th>
		<th>Deleate</th>
	 </tr>
	<?php
	foreach($GLOBALS['VD']['Users'] as $User){
		echo "<tr>";
		echo "<td>{$User["id"]}</td>";
		echo "<td>{$User["username"]}</td>";
		echo "<td>{$User["power"]}</td>";
		echo "<td>{$User["name"]}</td>";
		echo "<td>{$User["email"]}</td>";
		if($User["id"] != 1){
			echo "<td><a href=\"/Users/AccountAdmin/{$User["id"]}/\">Edit</a></td>";
			echo "<td><input type=\"checkbox\" name=\"{$User["id"]}\"></td>";
		} else {
			echo "<td>Cannot Edit Admin</td>";
			echo "<td>Cannot Delete Admin</td>";
		}
		echo "</tr>";
	}
	?>
	</table>
	<input type="button" name="DelUsers" value="Delete Users" onclick="DeleteUsers(); return false;" />
</form>
<script>
function DeleteUsers(){
	if(confirm("Are you sure you want to delete these users?")){
		document.getElementById('DelUsers').submit();
	}
}
</script>