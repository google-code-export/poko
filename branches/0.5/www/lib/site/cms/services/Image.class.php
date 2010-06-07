<?php

class site_cms_services_Image extends poko_Request {
	public function __construct() {
		if( !php_Boot::$skip_constructor ) {
		parent::__construct();
	}}
	public $data;
	public function main() {
		$src = $this->application->params->get("src");
		if($this->application->params->get("preset")) {
			$image = new poko_utils_ImageProcessor($this->application->uploadFolder . "/" . $src);
			$image->cacheFolder = $this->application->uploadFolder . "/cache";
			$image->format = poko_utils_ImageOutputFormat::$JPG;
			switch($this->application->params->get("preset")) {
			case "tiny":{
				$image->queueFitSize(40, 40);
			}break;
			case "thumb":{
				$image->queueFitSize(100, 100);
			}break;
			case "aspect":{
				$w = Std::parseInt($this->application->params->get("w"));
				$h = Std::parseInt($this->application->params->get("h"));
				$image->queueCropToAspect($w, $h);
			}break;
			case "custom":{
				$w2 = Std::parseInt($this->application->params->get("w"));
				$h2 = Std::parseInt($this->application->params->get("h"));
				$image->queueFitSize($w2, $h2);
			}break;
			case "gallery":{
				$image->queueFitSize(10, 10);
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
			$dateModified = php_FileSystem::stat($this->application->uploadFolder . "/" . $src)->mtime;
			$dateModifiedString2 = DateTools::format($dateModified, "%a, %d %b %Y %H:%M:%S") . " GMT";
			header("Last-Modified" . ": " . $dateModifiedString2);
			header("Expires" . ": " . DateTools::format(new Date($dateModified->getFullYear() + 1, $dateModified->getMonth(), $dateModified->getDay(), 0, 0, 0), "%a, %d %b %Y %H:%M:%S") . " GMT");
			header("Cache-Control" . ": " . "public, max-age=31536000");
			header("ETag" . ": " . "\"" . haxe_Md5::encode($src) . "\"");
			header("Pragma" . ": " . "");
			header("content-type" . ": " . "image");
			header("Content-Length" . ": " . filesize($this->application->uploadFolder . "/" . $src));
			readfile($this->application->uploadFolder . "/" . $src);
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
	function __toString() { return 'site.cms.services.Image'; }
}
