<?php

$this->buf .= '
<h3>';
$this->buf .= _hxtemplo_string($ctx->gallery);
$this->buf .= '</h3>
	
<div style="margin-bottom: 20px;">Upload files below. You can choose multiple files and once they are uploaded they will be automatically be displayed in the list below.</div>

<div id="fileQueue"></div>
<input type="file" name="uploadify" id="uploadify"/>
<p><a href="#" onClick="';
$this->buf .= $ctx->jsBinding->getCall('cancelUploads', new _hx_array(array()));
$this->buf .= '">Cancel All Uploads</a></p>

<hr/>

<div id="imageContent">
</div>';

?>