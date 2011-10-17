<?php

$this->buf .= '<h3>List Users</h3>
<table>
	<tr><td>Username</td><td>Name</td><td>Email</td><td>Action</td></tr>
	';
$repeater_u = _hxtemplo_repeater($ctx->users);  while($repeater_u->hasNext()) {$ctx->u = $repeater_u->next(); 
$this->buf .= '
		<tr>
		<td>';
$this->buf .= _hxtemplo_string($ctx->u->username);
$this->buf .= '</td>
		<td>';
$this->buf .= _hxtemplo_string($ctx->u->name);
$this->buf .= '</td>
		<td>';
$this->buf .= _hxtemplo_string($ctx->u->email);
$this->buf .= '</td>
		<td><a href="?request=cms.modules.base.User&action=edit&id=';
$this->buf .= _hxtemplo_string($ctx->u->id);
$this->buf .= '">Edit</a> | <a href="?request=cms.modules.base.Users&action=delete&id=';
$this->buf .= _hxtemplo_string($ctx->u->id);
$this->buf .= '">Delete</a> </td>
		</tr>
	';
}
$this->buf .= '
</table>';

?>