<?php

$this->buf .= '
<h3>Manage : "';
$this->buf .= _hxtemplo_string($ctx->definition->name);
$this->buf .= '" &gt; "';
$this->buf .= _hxtemplo_string($ctx->meta->name);
$this->buf .= '"</h3>

';
$this->buf .= $ctx->form->getOpenTag();
$this->buf .= '
	
	';
$this->buf .= $ctx->form->getErrors();
$this->buf .= '	
		
		';
if($ctx->fieldsets !== null) {
$this->buf .= '
			';
$repeater_fieldset = _hxtemplo_repeater($ctx->fieldsets);  while($repeater_fieldset->hasNext()) {$ctx->fieldset = $repeater_fieldset->next(); 
$this->buf .= '
				';
if($ctx->fieldset->name !== '__submit' && _hxtemplo_length($ctx->fieldset->elements) > 0) {
$this->buf .= ' 
					
					';
$this->buf .= $ctx->fieldset->getOpenTag();
$this->buf .= '
						<table>
						';
$repeater_element = _hxtemplo_repeater($ctx->fieldset->elements);  while($repeater_element->hasNext()) {$ctx->element = $repeater_element->next(); 
$this->buf .= '
							<tr>
								<td>';
$this->buf .= $ctx->element->getLabel();
$this->buf .= '</td>
								<td>';
$this->buf .= $ctx->element;
$this->buf .= '</td>
							</tr>
						';
}
$this->buf .= '
						</table>
					';
$this->buf .= $ctx->fieldset->getCloseTag();
$this->buf .= '
					
				';
}
$this->buf .= '
			';
}
$this->buf .= '
		';
} else {
$this->buf .= '
			';
$repeater_element = _hxtemplo_repeater($ctx->elements);  while($repeater_element->hasNext()) {$ctx->element = $repeater_element->next(); 
$this->buf .= '
				<tr><td>';
$this->buf .= $ctx->element->getLabel();
$this->buf .= '</td><td>';
$this->buf .= $ctx->element;
$this->buf .= '</td></tr>
			';
}
$this->buf .= '
		';
}
$this->buf .= '
		
	';
$this->buf .= $ctx->form->getElement('submit');
$this->buf .= '

';
$this->buf .= $ctx->form->getCloseTag();
$this->buf .= '

<script>
	$(document).ready(function(){
		$(".resizable").resizable();
	});
</script>';

?>