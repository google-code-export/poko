<?php

$this->buf .= '<h1>Basic Data</h1>

<p>Lets get the data out of a table, show images as images and trim description text to 200chars :</p>

<h3>Products</h3>

<pre class="code" lang="mtt" style="width:600px;display:none">
// Retrieving data from your database often requires only one line of code
products = app.db.request("SELECT * FROM `example_projects` WHERE `visible`=1");
// This can be displayed in your mtt templates using loops
&#58;&#58;foreach product products&#58;&#58;
	// Displaying and accessing member variables is as easy as:
	&#58;&#58;product.id&#58;&#58;
	&#58;&#58;product.name&#58;&#58;
// And finally close the loop
&#58;&#58;end&#58;&#58;
</pre>

<pre class="code" lang="php" style="width:600px;display:none">
// The same can be displayed in PHP as easily
foreach ($products as $product) {
	product-&gt;id
	product-&gt;name
}
</pre>

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
			<td><img src="?request=services.Image&preset=thumb&src=';
$this->buf .= _hxtemplo_string($ctx->product->heroimage);
$this->buf .= '"/></td>
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