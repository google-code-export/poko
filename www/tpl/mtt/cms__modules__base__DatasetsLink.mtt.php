<?php

$this->buf .= '
	<h3>Manage Datasets</h3>

';
$this->buf .= $ctx->form1->getOpenTag();
$this->buf .= '

<table class="greyTable datasetItem">

	';
$repeater_element = _hxtemplo_repeater($ctx->form1->getElements());  while($repeater_element->hasNext()) {$ctx->element = $repeater_element->next(); 
$this->buf .= '
		
		<tr><td class="labelTd">';
$this->buf .= $ctx->element->getLabel();
$this->buf .= '</td><td class="contentTd">';
$this->buf .= $ctx->element;
$this->buf .= '</td></tr>
		
	';
}
$this->buf .= '
	
</table>
	
';
$this->buf .= $ctx->form1->getCloseTag();
?>