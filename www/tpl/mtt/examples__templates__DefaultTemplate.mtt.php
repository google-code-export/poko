<?php

$this->buf .= '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<meta name="Title" content="';
$this->buf .= _hxtemplo_string($ctx->head->title);
$this->buf .= '"/>
    <meta name="Generator" content="haxe(haxe.org) - fwork"/>
    <meta name="Description" content="';
$this->buf .= _hxtemplo_string($ctx->head->description);
$this->buf .= '"/>
    <meta name="Meta" content="';
$this->buf .= _hxtemplo_string($ctx->head->meta);
$this->buf .= '"/>
    <meta name="Keywords" content="';
$this->buf .= _hxtemplo_string($ctx->head->keywords);
$this->buf .= '"/>
	<meta name="Publisher" content="';
$this->buf .= _hxtemplo_string($ctx->head->publisher);
$this->buf .= '"/>
	<meta name="Date" content="';
$this->buf .= _hxtemplo_string($ctx->head->date);
$this->buf .= '"/>
	<title>';
$this->buf .= _hxtemplo_string($ctx->head->title);
$this->buf .= '</title>
	';
$this->buf .= $ctx->scripts->getScripts();
$this->buf .= '
	<link rel="shortcut icon" href="';
$this->buf .= _hxtemplo_string($ctx->head->favicon);
$this->buf .= '"/>
</head>
<body>
	<div id="container">
		<div id="navigationMenu">
			<h1> EXAMPLES</h1>
			';
$this->buf .= $ctx->navigation;
$this->buf .= '
		</div>
		
		<div id="mainContent">
			<!-- START PAGE CONTENT -->
			';
$this->buf .= $ctx->controller->view->render();
$this->buf .= '
			<!-- END PAGE CONTENT -->
		</div>
	</div>
</body>
</html>';

?>