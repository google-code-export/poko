

<h2>Login</h2>
<form action="" method="POST">
	<p><b><?=$message?></b></p>
	<table>
		<tr>
			<td><label for="user">Username</label></td>
			<td><input type="text" name="username" id="username" value="<?=$username?>" /></td>
		</tr>
		<tr>
			<td><label for="password">Password</label></td>
			<td><input type="password" name="password" id="password" /></td>
		</tr>
	</table>
	<input type="submit" name="submitted" value="Login" />
</form>
