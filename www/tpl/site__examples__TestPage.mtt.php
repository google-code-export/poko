<?php

$this->buf .= '<h1>Page: Test Page</h1>

<p>We are accessing a page defined in the cms as "Test Page"</p>



<h2>As Request parameters</h2>

<b>heading:</b> ';
$this->buf .= _hxtemplo_string($ctx->heading);
$this->buf .= ' <br/><br/>

<b>content:</b> ';
$this->buf .= $ctx->content;
$this->buf .= ' <br/><br/>

<b>image: </b> <img src="?request=services.Image&preset=thumb&src=';
$this->buf .= _hxtemplo_string($ctx->image);
$this->buf .= '"/> <br/><br/>





<hr/>

<h2>Direct object access</h2>
<p>We have set variables to the Request class for the elements of the page. We could bypass doing this by accessing the data directly in the mtt as follows:</p>

<b>heading:</b> ';
$this->buf .= _hxtemplo_string($ctx->page->data->heading);
$this->buf .= '  <br/><br/>

<b>content:</b> ';
$this->buf .= $ctx->page->data->content;
$this->buf .= ' <br/><br/>

<b>image: </b> <img src="?request=services.Image&preset=thumb&src=';
$this->buf .= _hxtemplo_string($ctx->page->data->image);
$this->buf .= '"/> <br/>



';

?>