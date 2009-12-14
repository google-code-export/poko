<?php

$this->buf .= '


<b>HEADER</b>

';
$this->buf .= $ctx->controller->view->render();
$this->buf .= '

<b>FOOTER</b>';

?>