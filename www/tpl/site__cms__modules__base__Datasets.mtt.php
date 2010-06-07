<?php

$this->buf .= '	<h3>Manage Datasets</h3>
	
	<form action="" method="POST">
		<table class="greyTable">
			<tr>
				<td><b>ord</b></td>
				<td><b>Label</b></td>
				<td><b>DB table</b></td>
				<td><b>Definition</b></td>
				<td><b>Indents</b></td>
				<td></td>
			</tr>
			';
$repeater_row = _hxtemplo_repeater($ctx->data);  while($repeater_row->hasNext()) {$ctx->row = $repeater_row->next(); 
$this->buf .= '
				<tr>
					<td>';
if($ctx->row->order !== null) {
$this->buf .= '<input type="input" name="order[';
$this->buf .= _hxtemplo_string($ctx->row->id);
$this->buf .= ']" value="';
$this->buf .= _hxtemplo_string($ctx->row->order);
$this->buf .= '" class="order"/>';
}
$this->buf .= '</td>
					<td>';
if(_hxtemplo_is_true($ctx->row->label)) {
$this->buf .= ' ';
$this->buf .= _hxtemplo_string($ctx->row->label);
$this->buf .= ' ';
} else {
$this->buf .= ' - none - ';
}
$this->buf .= '</td>
					<td>';
$this->buf .= _hxtemplo_string($ctx->row->tableName);
$this->buf .= '</td>
					<td>';
if(_hxtemplo_is_true($ctx->row->definitionName)) {
$this->buf .= ' ';
$this->buf .= _hxtemplo_string($ctx->row->definitionName);
$this->buf .= ' ';
} else {
$this->buf .= ' - none - ';
}
$this->buf .= '</td>
					<td>';
if($ctx->row->indents !== null) {
$this->buf .= ' ';
$this->buf .= _hxtemplo_string($ctx->row->indents);
$this->buf .= ' ';
} else {
$this->buf .= ' - none - ';
}
$this->buf .= '</td>
					<td><a href="?request=cms.modules.base.DatasetsLink&tableName=';
$this->buf .= _hxtemplo_string($ctx->row->tableName);
$this->buf .= '">edit</a></td>
				</tr>
			';
}
$this->buf .= '
		</table>
		
		<input type="hidden" name="action" value="order"/>
		<button type="submit">update order</button>
	</form>';

?>