<?php

$this->buf .= '	<div style="margin-bottom:50px;">
		<h3>Email</h3>
		
		';
$this->buf .= $ctx->form->getPreview();
$this->buf .= '
	</div>';

?>