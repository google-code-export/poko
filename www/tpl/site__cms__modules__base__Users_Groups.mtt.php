<?php

$this->buf .= '<h3>List Groups</h3>
<table>
	<tr>
		<td>Group</td>
		<td>Description</td>
		<td>Permissions</td>
		<td>Action</td>
	</tr>
	';
$repeater_g = _hxtemplo_repeater($ctx->groups);  while($repeater_g->hasNext()) {$ctx->g = $repeater_g->next(); 
$this->buf .= '
		<tr>
			<td>';
$this->buf .= _hxtemplo_string($ctx->g->name);
$this->buf .= '</td>
			<td>';
$this->buf .= _hxtemplo_string($ctx->g->description);
$this->buf .= '</td>
			<td>';
if(_hxtemplo_is_true($ctx->g->isAdmin)) {
$this->buf .= ' Admin ';
}
$this->buf .= ' ';
if(_hxtemplo_is_true($ctx->g->isSuper)) {
$this->buf .= ' Super ';
}
$this->buf .= '</td>
			<td><a href="?request=cms.modules.base.Users_Group&action=edit&id=';
$this->buf .= _hxtemplo_string($ctx->g->id);
$this->buf .= '">Edit</a> | <a href="?request=cms.modules.base.Users_Groups&action=delete&id=';
$this->buf .= _hxtemplo_string($ctx->g->id);
$this->buf .= '">Delete</a> </td>
		</tr>
	';
}
$this->buf .= '
</table>';

?>