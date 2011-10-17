<?php

class site_cms_services_ImageBase extends poko_controllers_Controller {
	public function __construct() {
		if( !php_Boot::$skip_constructor ) {
		parent::__construct();
		;
	}}
	public $data;
	public function main() {
		$src = $this->app->params->get("src");
		if($src === "" || $src === null) {
			$blankGif = "R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==";
			$blankHash = haxe_Md5::encode($blankGif);
			$this->setHeaders(Date::fromTime(0), "43", $blankHash);
			php_Lib::hprint(base64_decode($blankGif));
			return;
			unset($blankHash,$blankGif);
		}
		if($this->app->params->exists("preset")) {
			$image = new poko_utils_ImageProcessor(site_cms_PokoCms::$uploadFolder . $src);
			$image->cacheFolder = site_cms_PokoCms::$uploadFolder . "cache";
			$image->format = poko_utils_ImageOutputFormat::$JPG;
			$this->resizeImage($this->app->params->get("preset"), $image);
			$imageStr = $image->getOutput(null);
			$filename = ($image->cacheFolder . "/") . $image->getCacheName();
			$length = site_cms_services_ImageBase_0($this, $filename, $image, $imageStr, $src);
			$this->setHeaders($image->dateModified, $length, $image->hash);
			php_Lib::hprint($imageStr);
			unset($length,$imageStr,$image,$filename);
		}
		else {
			$dateModified = php_FileSystem::stat(site_cms_PokoCms::$uploadFolder . $src)->mtime;
			$length = filesize(site_cms_PokoCms::$uploadFolder . $src);
			$this->setHeaders($dateModified, $length, haxe_Md5::encode($src));
			readfile(site_cms_PokoCms::$uploadFolder . $src);
			php_Sys::hexit(1);
			unset($length,$dateModified);
		}
		unset($src);
	}
	public function resizeImage($preset, $image) {
		;
		;
	}
	public function setHeaders($dateModified, $length, $hash) {
		header(("Last-Modified" . ": ") . (DateTools::format($dateModified, "%a, %d %b %Y %H:%M:%S") . " GMT"));
		header(("Expires" . ": ") . (DateTools::format(new Date($dateModified->getFullYear() + 1, $dateModified->getMonth(), $dateModified->getDay(), 0, 0, 0), "%a, %d %b %Y %H:%M:%S") . " GMT"));
		header(("Cache-Control" . ": ") . "public, max-age=31536000");
		header(("ETag" . ": ") . (("\"" . $hash) . "\""));
		header(("Pragma" . ": ") . "");
		header(("Content-Type" . ": ") . "image");
		header(("Content-Length" . ": ") . $length);
		;
	}
	public function __call($m, $a) {
		if(isset($this->$m) && is_callable($this->$m))
			return call_user_func_array($this->$m, $a);
		else if(isset($this->»dynamics[$m]) && is_callable($this->»dynamics[$m]))
			return call_user_func_array($this->»dynamics[$m], $a);
		else if('toString' == $m)
			return $this->__toString();
		else
			throw new HException('Unable to call «'.$m.'»');
	}
	function __toString() { return 'site.cms.services.ImageBase'; }
}
;
function site_cms_services_ImageBase_0(&$»this, &$filename, &$image, &$imageStr, &$src) {
if(file_exists($filename)) {
	return filesize($filename);
	;
}
else {
	return Std::string(strlen($imageStr));
	;
}
}