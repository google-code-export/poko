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
$this->buf .= $ctx->head->getJs();
$this->buf .= '
	';
$this->buf .= $ctx->head->getCss();
$this->buf .= '
	';
$this->buf .= '<!--[if IE 6]>';
$this->buf .= ' ';
$this->buf .= _hxtemplo_string($ctx->head->getCssIe6());
$this->buf .= ' ';
$this->buf .= '<![endif]-->';
$this->buf .= '
	';
$this->buf .= '<!--[if IE 7]>';
$this->buf .= ' ';
$this->buf .= _hxtemplo_string($ctx->head->getCssIe7());
$this->buf .= ' ';
$this->buf .= '<![endif]-->';
$this->buf .= '
	<link rel="shortcut icon" href="';
$this->buf .= _hxtemplo_string($ctx->head->favicon);
$this->buf .= '"/>
</head>
<body>
	<div id="wrapper">
    	<div id="pageWrapper">
			
			<!-- START PAGE CONTENT -->
			';
$this->bufferCreate();$t_src_examples__site__templates__DefaultTemplate__mtt_0 = $ctx->request_content_file;$this->includeTemplate($t_src_examples__site__templates__DefaultTemplate__mtt_0, 'src_examples__site__templates__DefaultTemplate__mtt', $ctx);
$this->buf .= '
			<!-- END PAGE CONTENT -->
				

      </div>
	</div>
</body>
</html>';

?>