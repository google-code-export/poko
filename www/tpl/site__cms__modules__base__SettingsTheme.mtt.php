<?php

$this->buf .= '<h3>Theme</h3>
<p>Choose a theme for the CMS. Make things look pretty.</p>
';
$repeater_theme = _hxtemplo_repeater($ctx->themes);  while($repeater_theme->hasNext()) {$ctx->theme = $repeater_theme->next(); 
$this->buf .= '
	';
if($ctx->currentTheme === $ctx->theme) {$ctx->cThemeCss = 'cmsSettinsThemeWrapperCurrent';}
$this->buf .= '
	<div class="cmsSettinsThemeWrapper ';
$this->buf .= _hxtemplo_string($ctx->cThemeCss);
$this->buf .= '"><a href="?request=cms.modules.base.SettingsTheme&set=';
$this->buf .= _hxtemplo_string($ctx->theme);
$this->buf .= '"><img width="240" height="180" src="res/cms/theme/';
$this->buf .= _hxtemplo_string($ctx->theme);
$this->buf .= '/screenshot-thumb.png" title="';
$this->buf .= _hxtemplo_string($ctx->theme);
$this->buf .= '"/></a></div>
	';
$ctx->cThemeCss = '';
$this->buf .= '
';
}
$this->buf .= '
<div style="clear: both;"></div>';

?>