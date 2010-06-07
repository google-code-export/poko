<?php

$this->buf .= '<h1>Pages</h1>

<p>We cam access pages content via the cms.common.PageData class. Pages have a definition and data seperately. 
You may choose to use the data without the definition (often this is only needed for the cms) by accessing a (Pagedata Object).data </p>

<p>In this way, you can design a site in the fontend which is totally independant, and custom from the cms</p>

<h3>Pages</h3>

Using PageData.getPages() we can get a list of pages. Often you might want to create a cutom request for each page and access it manually however. 

';
$this->buf .= $ctx->pageNav;
$this->buf .= '


';
if($ctx->selectedPage !== null) {
$this->buf .= '

	<h2>Page: ';
$this->buf .= _hxtemplo_string($ctx->selectedPage->name);
$this->buf .= '</h2>
	
	
	<h3>Elements</h3>
	
	';
if(_hxtemplo_length($ctx->selectedPage->definition->elements) > 0) {
$this->buf .= '
		
		';
$repeater_el = _hxtemplo_repeater($ctx->selectedPage->definition->elements);  while($repeater_el->hasNext()) {$ctx->el = $repeater_el->next(); 
$this->buf .= '
			<b>';
$this->buf .= _hxtemplo_string($ctx->el->name);
$this->buf .= '</b>
			<table class="greyTable">
				<tr><td>name  </td> <td> ';
$this->buf .= _hxtemplo_string($ctx->el->name);
$this->buf .= ' </td></tr>
				<tr><td>name: </td> <td> ';
$this->buf .= _hxtemplo_string($ctx->el->type);
$this->buf .= ' </td></tr>
				<tr><td>data: </td> <td> ';
$this->buf .= _hxtemplo_string($ctx->controller->getData($ctx->el->name));
$this->buf .= ' </td></tr>
			</table>
		';
}
$this->buf .= '
		
	';
} else {
$this->buf .= '
		There are no defined elemtents for this page
	';
}
$this->buf .= '
	
	<h3>Raw Data</h3>
	';
$this->buf .= _hxtemplo_string($ctx->selectedPage->data);
$this->buf .= '
';
}
?>