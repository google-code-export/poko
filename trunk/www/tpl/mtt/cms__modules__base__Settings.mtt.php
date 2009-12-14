<?php
if($ctx->section === null) {
$this->buf .= '
	<h3>You can change stuff here</h3>
	<p>Set themes and such ...</p>
';
} else {
$this->buf .= '
	<h3>';
$this->buf .= _hxtemplo_string($ctx->sectionTitle);
$this->buf .= '</h3>
	<p>Change your settings here.</p>
	';
$this->buf .= $ctx->form->getPreview();
$this->buf .= '
';
}
?>