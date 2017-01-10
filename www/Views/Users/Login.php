<div>
	<h2>Login</h2>
	<?php echo ('<h3>'. $GLOBALS['VD']['Sucess'] .'</h3>'); ?>
	<form method="Post" action="?Con=Users&Action=Login">
		<table>
		<tr>
			<td><label for="Username">Username:</label></td>
			<td><input type="text" name="Username" value="<?php echo $GLOBALS['VD']['Username'];?>"></td>
			<td class="E"></td>
		</tr>
		<tr>
			<td><label for="Password">Password:</label></td>
			<td><input type="password" name="Password"></td>
			<td class="E"></td>
		</tr>
		<tr>
			<td style="padding-right: 20px;"><label for="Remember">Remember me:</label></td>
			<td><input type="checkbox" name="Remember"></td>
			<td><small>Potentaly Inscure</small></td>
		</tr>
		</table>
		<input type="submit" value="Login">
	</form>
</div>
<a href="/Users/Register/"><h4>Don't have an account?</h4></a>