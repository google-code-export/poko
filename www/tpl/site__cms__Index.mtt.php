<?php

$this->buf .= '<div id="loginBox">
	<h2>Login</h2>
	<form action="" method="POST">
		<p><b>';
$this->buf .= _hxtemplo_string($ctx->message);
$this->buf .= '</b></p>
		<table>
			<tr>
				<td><label for="user">Username</label></td>
				<td><input type="text" name="username" id="username" value="';
$this->buf .= _hxtemplo_string($ctx->inputUsername);
$this->buf .= '"/></td>
			</tr>
			<tr>
				<td><label for="password">Password</label></td>
				<td><input type="password" name="password" id="password"/></td>
			</tr>
		</table>
		<input id="loginBoxSubmit" type="submit" name="submitted" value="Login"/>
	</form>
</div>';

?>