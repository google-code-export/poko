<?php

$this->buf .= '<h1>Page: Test Page</h1>

<p>We are accessing a page defined in the cms as "Test Page"</p>

<h3>Auto preview</h3>

data: ';
$this->buf .= _hxtemplo_string($ctx->form1->getData());
$this->buf .= '
';
$this->buf .= _hxtemplo_string($ctx->form1->getErrors());
$this->buf .= '
';
$this->buf .= $ctx->form1->getOpenTag();
$this->buf .= '
<table>	
	';
$repeater_element = _hxtemplo_repeater($ctx->form1->getElements());  while($repeater_element->hasNext()) {$ctx->element = $repeater_element->next(); 
$this->buf .= '
		<tr><td>';
$this->buf .= $ctx->element->getLabel();
$this->buf .= '</td><td>';
$this->buf .= $ctx->element;
$this->buf .= '</td></tr>
	';
}
$this->buf .= '
</table>
';
$this->buf .= $ctx->form1->getCloseTag();
$this->buf .= '


<h3>Auto preview</h3>

data: ';
$this->buf .= _hxtemplo_string($ctx->form1->getData());
$this->buf .= '
';
$this->buf .= $ctx->form1->getPreview();
$this->buf .= '



<h3>Form with valudation</h3>
There is a simple string validator added to the name. Requiring 3-10 characters and only \'abcdefgh\' chars.<br/>
data: ';
$this->buf .= _hxtemplo_string($ctx->form2->getData());
$this->buf .= '
';
$this->buf .= $ctx->form2->getPreview();
$this->buf .= '
';

?>