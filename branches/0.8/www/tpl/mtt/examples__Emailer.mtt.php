<?php

$this->buf .= '<h1>Emailer</h1>

';
$this->buf .= $ctx->form->getPreview();
$this->buf .= '

<p>';
$this->buf .= _hxtemplo_string($ctx->message);
$this->buf .= '</p>';

?>