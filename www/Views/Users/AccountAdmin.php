<style>
	.E {
		color: red;
	}
</style>
<div>
	<h2>Modieing entries on <?php echo $GLOBALS['VD']['Username'];?>'s account</h2>
	<form method="Post" action="?Con=Users&Action=AccountAdmin" id="Changes">
		<table>
		<tr>
			<td><label for="name">Change Username: </label></td>
			<td><input type="text" name="Username" value="<?php echo $GLOBALS['VD']["Username"];?>"></td>
			<td class="E" ><?php echo $GLOBALS['VD']['EUsername'];?><td>
		</tr>
		<tr>
			<td><label for="name">Change Name: </label></td>
			<td><input type="text" name="Name" value="<?php echo $GLOBALS['VD']["Name"];?>"></td>
		</tr>
		<tr>
			<td><label for="name">Change Email: </label></td>
			<td><input type="email" name="Email" value="<?php echo $GLOBALS['VD']["Email"];?>"></td>
			<td class="E" ><?php echo $GLOBALS['VD']['EEmail'];?><td>
		</tr>
		<tr>
			<td><label for="name">Change Power: </label></td>
			<td><input type="email" name="Power" value="<?php echo $GLOBALS['VD']["Power"];?>"></td>
		</tr>
		<tr>
			<td><label for="name">Change Password:</label></td>
			<td><input type="password" name="NewPassword" id="pass1" onkeyup="NormPass(); return false;"></td>
			<td class="E" ><?php echo $GLOBALS['VD']['ENewPassword'];?><td>
		</tr>
		<tr>
			<td><label for="name">Confirm:</label></td>
			<td><input type="password" name="confirm" id="pass2" onkeyup="NormPass(); return false;"></td>
			<td  class="E" id="ConfirmE"></td>
		</tr>
		</table>
		<input type="button" value="Save Changes" id="SubBut" onclick="Submit(); return false;"> 
	</form>
</div>
<script>
	function CheckPassword(){
		if(!(document.getElementById('pass1').value == document.getElementById('pass2').value)){
			document.getElementById('ConfirmE').innerHTML = "Passwords do not match";
			return false;
		} else{
			return true;
		}
	}
	function NormPass(){
		document.getElementById('ConfirmE').innerHTML = "";
	}
	function Submit(){
		if(CheckPassword()) {
			document.getElementById('Changes').submit();
		}
	}
</script>