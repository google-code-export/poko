<?php

$this->buf .= '<h1>Basic Data</h1>

<p>Lets get the data out of a table, show images as images and trim description text to 200chars :</p>

<h3>Products</h3>

<table class="greyTable">
	<tr>
		<td><b>id</b></td>
		<td><b>name</b></td>
		<td><b>image</b></td>
		<td><b>description</b></td>
	</tr>
	';
$repeater_product = _hxtemplo_repeater($ctx->products);  while($repeater_product->hasNext()) {$ctx->product = $repeater_product->next(); 
$this->buf .= '
		<tr>
			<td>';
$this->buf .= _hxtemplo_string($ctx->product->id);
$this->buf .= '</td>
			<td>';
$this->buf .= _hxtemplo_string($ctx->product->name);
$this->buf .= '</td>
			<td>';
$this->buf .= _hxtemplo_string($ctx->product->image);
$this->buf .= '</td>
			<td>';
$this->buf .= $ctx->controller->trim($ctx->product->description, 200);
$this->buf .= '</td>
		</tr>
	';
}
$this->buf .= '
</table>


';

?>