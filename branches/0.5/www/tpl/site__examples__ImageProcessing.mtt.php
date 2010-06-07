<?php

$this->buf .= '<h1>Image Processing</h1>

<p>You can define a set a preset ways to process you images - for each part of you site for instance. You can also just send in custom sizes. Edit the services.Image class to setup presets.</p>

<b>small: </b> <img src="?request=services.Image&preset=tiny&src=';
$this->buf .= _hxtemplo_string($ctx->imageUrl);
$this->buf .= '"/> <br/><br/>
<b>thumb: </b> <img src="?request=services.Image&preset=thumb&src=';
$this->buf .= _hxtemplo_string($ctx->imageUrl);
$this->buf .= '"/> <br/><br/>
<b>square: </b> <img src="?request=services.Image&preset=square&src=';
$this->buf .= _hxtemplo_string($ctx->imageUrl);
$this->buf .= '"/> <br/><br/>
<b>aspect: </b> <img src="?request=services.Image&preset=aspect&w=2&h=10&src=';
$this->buf .= _hxtemplo_string($ctx->imageUrl);
$this->buf .= '"/> <br/><br/>
<b>custom size: </b> <img src="?request=services.Image&preset=custom&w=200&h=200&src=';
$this->buf .= _hxtemplo_string($ctx->imageUrl);
$this->buf .= '"/> <br/><br/>
<b>original: </b> <img src="?request=services.Image&src=';
$this->buf .= _hxtemplo_string($ctx->imageUrl);
$this->buf .= '"/> <br/><br/>

';

?>