<script src='https://www.google.com/recaptcha/api.js'></script>
<style>
	.E {
		color: red;
	}
</style>
<div>
	<h2>Register</h2>
	<p><?php echo $GLOBALS['VD']['Sucess']; ?></p>
	<form method="Post" id="Changes" action="?Con=Users&Action=Register">
		<table>
		<tr>
			<td><label for="name">Username:</label></td>
			<td><input type="text" name="Username" value="<?php echo $GLOBALS['VD']['Name'];?>"></td>
			<td class="E" ><?php echo $GLOBALS['VD']['EUsername'];?><td>
		</tr>
		<tr>
			<td><label for="name">Name:</label></td>
			<td><input type="text" name="Name" value="<?php echo $GLOBALS['VD']['Name'];?>"></td>
			<td class="E" ><?php echo $GLOBALS['VD']['EName'];?><td>
		</tr>
		<tr>
			<td><label for="name">Email:</label></td>
			<td><input type="email" name="Email" value="<?php echo $GLOBALS['VD']['Email'];?>"></td>
			<td class="E" ><?php echo $GLOBALS['VD']['EEmail'];?><td>
		</tr>
		<tr>
			<td><label for="name">Password:</label></td>
			<td><input type="password" name="Password" id="pass1" onkeyup="NormPass(); return false;"></td>
			<td class="E" ><?php echo $GLOBALS['VD']['EPassword'];?><td>
		</tr>
		<tr>
			<td><label for="name">Confirm:</label></td>
			<td><input type="password" name="confirm" id="pass2" onkeyup="NormPass(); return false;"></td>
			<td class="E" id="ConfirmE"></td>
		</tr>
		</table>
		<div class="g-recaptcha" data-sitekey="6LeRkgwUAAAAAO8Yz2ko1GToLoIigdHhXQ3mKHi_"></div>
		<p><?php echo $GLOBALS['VD']['Cap']; ?></p>
		<input type="button" value="Register" id="SubBut" onclick="Submit(); return false;">
	</form>
</div>
<script>
	function CheckPassword(){
		if(!(document.getElementById('pass1').value == document.getElementById('pass2').value)){
			document.getElementById('pass2').style.backgroundColor = "#ffaaaa";
			document.getElementById('submit').name = "";
			document.getElementById('ConfirmE').innerHTML = "Passwords do not match";
			return false;
		} else{
			return true;
		}
	}
	function NormPass(){
		document.getElementById('pass2').style.backgroundColor = "#ffffff";
		document.getElementById('submit').name = "submit";
		document.getElementById('ConfirmE').innerHTML = "";
	}
	function Submit(){
		if(CheckPassword()) {
			document.getElementById('Changes').submit();
		}
	}
</script>