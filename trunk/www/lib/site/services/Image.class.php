<?php

class site_services_Image extends poko_controllers_Controller {
	public function __construct() {
		if( !php_Boot::$skip_constructor ) {
		parent::__construct();
	}}
	public $data;
	public function main() {
		$src = $this->app->params->get("src");
		$uploadFolder = "./res/uploads";
		if($this->app->params->get("preset") !== null) {
			$image = new poko_utils_ImageProcessor($uploadFolder . "/" . $src);
			$image->cacheFolder = $uploadFolder . "/cache";
			$image->format = poko_utils_ImageOutputFormat::$JPG;
			switch($this->app->params->get("preset")) {
			case "tiny":{
				$image->queueFitSize(40, 40);
			}break;
			case "thumb":{
				$image->queueFitSize(100, 100);
			}break;
			case "square":{
				$image->queueCropToAspect(100, 100);
				$image->queueFitSize(200, 200);
			}break;
			case "aspect":{
				$w = Std::parseInt($this->app->params->get("w"));
				$h = Std::parseInt($this->app->params->get("h"));
				$image->queueCropToAspect($w, $h);
			}break;
			case "custom":{
				$w2 = Std::parseInt($this->app->params->get("w"));
				$h2 = Std::parseInt($this->app->params->get("h"));
				$image->queueFitSize($w2, $h2);
			}break;
			}
			$dateModifiedString = DateTools::format($image->dateModified, "%a, %d %b %Y %H:%M:%S") . " GMT";
			header("Last-Modified" . ": " . $dateModifiedString);
			header("Expires" . ": " . DateTools::format(new Date($image->dateModified->getFullYear() + 1, $image->dateModified->getMonth(), $image->dateModified->getDay(), 0, 0, 0), "%a, %d %b %Y %H:%M:%S") . " GMT");
			header("Cache-Control" . ": " . "public, max-age=31536000");
			header("ETag" . ": " . "\"" . $image->hash . "\"");
			header("Pragma" . ": " . "");
			header("content-type" . ": " . "image");
			$this->setOutput($image->getOutput(null));
		}
		else {
			$dateModified = php_FileSystem::stat($uploadFolder . "/" . $src)->mtime;
			$dateModifiedString2 = DateTools::format($dateModified, "%a, %d %b %Y %H:%M:%S") . " GMT";
			header("Last-Modified" . ": " . $dateModifiedString2);
			header("Expires" . ": " . DateTools::format(new Date($dateModified->getFullYear() + 1, $dateModified->getMonth(), $dateModified->getDay(), 0, 0, 0), "%a, %d %b %Y %H:%M:%S") . " GMT");
			header("Cache-Control" . ": " . "public, max-age=31536000");
			header("ETag" . ": " . "\"" . haxe_Md5::encode($src) . "\"");
			header("Pragma" . ": " . "");
			header("content-type" . ": " . "image");
			header("Content-Disposition" . ": " . "inline; filename=" . _hx_substr($src, 32, null));
			header("Content-Transfer-Encoding" . ": " . "binary");
			header("Content-Length" . ": " . filesize($uploadFolder . "/" . $src));
			readfile($uploadFolder . "/" . $src);
			php_Sys::hexit(1);
		}
	}
	public function __call($m, $a) {
		if(isset($this->$m) && is_callable($this->$m))
			return call_user_func_array($this->$m, $a);
		else if(isset($this->»dynamics[$m]) && is_callable($this->»dynamics[$m]))
			return call_user_func_array($this->»dynamics[$m], $a);
		else
			throw new HException('Unable to call «'.$m.'»');
	}
	function __toString() { return 'site.services.Image'; }
}
