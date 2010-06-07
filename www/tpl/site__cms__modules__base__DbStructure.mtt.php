<?php

$this->buf .= '

<p>';
$this->buf .= $ctx->dbStructureSelector;
$this->buf .= '</p>

<table class="greyTable">		
';
$repeater_field = _hxtemplo_repeater($ctx->fields);  while($repeater_field->hasNext()) {$ctx->field = $repeater_field->next(); 
$this->buf .= '
	<tr>
	<td><b>';
$this->buf .= _hxtemplo_string($ctx->field->Field);
$this->buf .= '</b> ';
$this->buf .= _hxtemplo_string($ctx->field->Type);
$this->buf .= ' ';
$this->buf .= _hxtemplo_string($ctx->field->Key);
$this->buf .= '</td>
	</tr>
';
}
$this->buf .= '
</table>';

?>