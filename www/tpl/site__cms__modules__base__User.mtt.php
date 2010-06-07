<?php

$this->buf .= '<h3>';
$this->buf .= _hxtemplo_string($ctx->heading);
$this->buf .= '</h3>
';
$this->buf .= $ctx->form1->getPreview();
?>