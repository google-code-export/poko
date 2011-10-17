<?php

$this->buf .= '<h1>Page: Test Page</h1>

<p>We are accessing a page defined in the cms as "Test Page"</p>

<hr/>

<h3>Auto form output</h3>
<p>You can get a simple auto-generated form using <code>form.getPreview()</code></p>

data: ';
$this->buf .= _hxtemplo_string($ctx->form1->getData());
$this->buf .= '
';
$this->buf .= $ctx->form1->getPreview();
$this->buf .= '

<hr/>

<h3>Manual form output</h3>
<p>To output forms manually use <code>form.getOpenTag()</code> and <code>form.getCloseTag()</code> surrounding your form.
Then loop through <code>form.getElements()</code> and output <code>element.getLabel()</code> and <code>element</code>.
Don\'t foret to use <code>raw</code> if you\'re using MTT. You can also get a specific element using <code>form.getElement("name")</code>.</p>

data: ';
$this->buf .= _hxtemplo_string($ctx->form2->getData());
$this->buf .= '
';
$this->buf .= $ctx->form2->getErrors();
$this->buf .= '
';
$this->buf .= $ctx->form2->getOpenTag();
$this->buf .= '
<table>	
	';
$repeater_element = _hxtemplo_repeater($ctx->form2->getElements());  while($repeater_element->hasNext()) {$ctx->element = $repeater_element->next(); 
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
$this->buf .= $ctx->form2->getCloseTag();
$this->buf .= '

<hr/>

<h3>Form with Validation</h3>
<p>There is a simple string validator added to the name. Requiring 3-10 characters and only \'abcdefgh\' chars.</p>
data: ';
$this->buf .= _hxtemplo_string($ctx->form2->getData());
$this->buf .= '
';
$this->buf .= $ctx->form2->getPreview();
$this->buf .= '
';

?>