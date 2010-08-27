<?php

$this->buf .= 'a, a:visited
{
	color: ';
$this->buf .= _hxtemplo_string($ctx->colorLinkOnLight);
$this->buf .= ';
}

#heading 
{
	height: ';
$this->buf .= _hxtemplo_string(_hxtemplo_add($ctx->sizeLogoHeight,70));
$this->buf .= 'px;	
	background-image: url(\'';
$this->buf .= _hxtemplo_string($ctx->imageHeaderBg);
$this->buf .= '\');
	background-position: bottom;
}

#heading #headingNavigation li a
{
	width: ';
$this->buf .= _hxtemplo_string($ctx->sizeButtonWidth);
$this->buf .= 'px;
	height: ';
$this->buf .= _hxtemplo_string($ctx->sizeButtonHeight);
$this->buf .= 'px;
	background: url(\'';
$this->buf .= _hxtemplo_string($ctx->imageHeaderButtonBgUp);
$this->buf .= '\') no-repeat;
	text-align: center;
	color: #fff;
	margin-right: 5px;
	font-size: 12px;
	font-weight: bold;
	padding-top: 5px;
}

#heading #headingNavigation li a:hover
{
	background: url(\'';
$this->buf .= _hxtemplo_string($ctx->imageHeaderButtonBgOver);
$this->buf .= '\') no-repeat;
}

#headingLogo
{
	background: url(\'';
$this->buf .= _hxtemplo_string($ctx->imageHeadingLogo);
$this->buf .= '\') 2px 20px no-repeat;
	width: ';
$this->buf .= _hxtemplo_string($ctx->sizeLogoWidth);
$this->buf .= 'px;
	height: ';
$this->buf .= _hxtemplo_string(_hxtemplo_add($ctx->sizeLogoHeight,35));
$this->buf .= 'px;
}

#headingUserBox
{
	background: url(\'';
$this->buf .= _hxtemplo_string($ctx->imageHeadingLoginBg);
$this->buf .= '\') no-repeat;
}
#headingUserBox a
{
	color: ';
$this->buf .= _hxtemplo_string($ctx->colorLinkOnDark);
$this->buf .= ';
}

#content h3
{
	background: url(\'';
$this->buf .= _hxtemplo_string($ctx->imageHeadingLong);
$this->buf .= '\') no-repeat;	
}

#leftNavigation h3
{
	background: url(\'';
$this->buf .= _hxtemplo_string($ctx->imageHeadingShort);
$this->buf .= '\') no-repeat;
}

#leftNavigation ul a:hover
{
	color: ';
$this->buf .= _hxtemplo_string($ctx->colorLinkOnLight);
$this->buf .= ';
}

#leftNavigationFooter a
{
	background: ';
$this->buf .= _hxtemplo_string($ctx->colorNavigationLinkBgUp);
$this->buf .= ';
	color: ';
$this->buf .= _hxtemplo_string($ctx->colorNavigationLinkColor);
$this->buf .= ';
}
#leftNavigationFooter a:hover
{
	background: ';
$this->buf .= _hxtemplo_string($ctx->colorNavigationLinkBgOver);
$this->buf .= ';
}';

?>