<?php

class site_services_Image extends site_cms_services_ImageBase {
	public function __construct() { if( !php_Boot::$skip_constructor ) {
		parent::__construct();
		;
	}}
	public function resizeImage($preset, $image) {
		switch($this->app->params->get("preset")) {
		case "tiny":{
			$image->queueFitSize(40, 40);
			;
		}break;
		case "thumb":{
			$image->queueFitSize(100, 100);
			;
		}break;
		case "square":{
			$image->queueCropToAspect(100, 100);
			$image->queueFitSize(200, 200);
			;
		}break;
		case "aspect":{
			$w = Std::parseInt($this->app->params->get("w"));
			$h = Std::parseInt($this->app->params->get("h"));
			$image->queueCropToAspect($w, $h);
			unset($w,$h);
		}break;
		case "custom":{
			$w = Std::parseInt($this->app->params->get("w"));
			$h = Std::parseInt($this->app->params->get("h"));
			$image->queueFitSize($w, $h);
			unset($w,$h);
		}break;
		case "trans":{
			$image->format = poko_utils_ImageOutputFormat::$PNG;
			$image->saveAlpha = true;
			;
		}break;
		}
		;
	}
	function __toString() { return 'site.services.Image'; }
}
