<?php

class poko_utils_ImageProcessor {
	public function __construct($file) {
		if( !php_Boot::$skip_constructor ) {
		$this->fileName = $file;
		$this->queue = new HList();
		$this->cache = true;
		$this->cacheFolder = "./cache";
		$this->forceNoCache = false;
		$this->quality = .8;
		$this->format = poko_utils_ImageOutputFormat::$JPG;
		$this->hash = $this->getHash();
		$this->resource = eval("if(isset(\$this)) \$»this =& \$this;switch(\$»this->getFileFormat(\$file)) {
			case poko_utils_ImageOutputFormat::\$JPG:{
				\$»r = poko_utils_GD::imageCreateFromJpeg(\$file);
			}break;
			case poko_utils_ImageOutputFormat::\$GIF:{
				\$»r = poko_utils_GD::imageCreateFromGif(\$file);
			}break;
			case poko_utils_ImageOutputFormat::\$PNG:{
				\$»r = poko_utils_GD::imageCreateFromPng(\$file);
			}break;
			default:{
				\$»r = null;
			}break;
			}
			return \$»r;
		");
		if(_hx_field($this, "resource") === null) {
			throw new HException("Could not load image");
		}
	}}
	public $fileName;
	public $resource;
	public $queue;
	public $forceNoCache;
	public $cache;
	public $cacheFolder;
	public $quality;
	public $format;
	public $hash;
	public $dateModified;
	public function getWidth() {
		return imagesx($this->resource);
	}
	public function getHeight() {
		return imagesy($this->resource);
	}
	public function queueFitSize($maxWidth, $maxHeight) {
		$this->queue->add(_hx_anonymous(array("type" => poko_utils_ImageAction::$FIT, "maxWidth" => $maxWidth, "maxHeight" => $maxHeight)));
	}
	public function queueCropToAspect($w, $h) {
		$this->queue->add(_hx_anonymous(array("type" => poko_utils_ImageAction::$ASPECT, "w" => $w, "h" => $h)));
	}
	public function queueCrop($x, $y, $width, $height) {
		$this->queue->add(_hx_anonymous(array("type" => poko_utils_ImageAction::$CROP, "x" => $x, "y" => $y, "width" => $width, "height" => $height)));
	}
	public function queueResize($width, $height) {
		$this->queue->add(_hx_anonymous(array("type" => poko_utils_ImageAction::$RESIZE, "width" => $width, "height" => $height)));
	}
	public function queueScale($scaleX, $scaleY) {
		$this->queue->add(_hx_anonymous(array("type" => poko_utils_ImageAction::$SCALE, "scaleX" => $scaleX, "scaleY" => $scaleY)));
	}
	public function queueRotate($CW) {
		if($CW === null) {
			$CW = true;
		}
		$this->queue->add(_hx_anonymous(array("type" => poko_utils_ImageAction::$ROTATE, "CW" => $CW)));
	}
	public function queueCustom($func, $cacheIdentifier) {
		$this->queue->add(_hx_anonymous(array("type" => poko_utils_ImageAction::$CUSTOM, "func" => $func, "cacheIdentifier" => $cacheIdentifier)));
	}
	public function applyFitSize($maxWidth, $maxHeight) {
		$ow = imagesx($this->resource);
		$oh = imagesy($this->resource);
		$scale = 1;
		if($ow > $maxWidth) {
			$scale = $maxWidth / $ow;
		}
		if($oh * $scale > $maxHeight) {
			$scale *= $maxHeight / ($oh * $scale);
		}
		$nw = intval($ow * $scale);
		$nh = intval($oh * $scale);
		if($nw < 1) {
			$nw = 1;
		}
		if($nh < 1) {
			$nh = 1;
		}
		$newResource = poko_utils_GD::imageCreateTrueColor($nw, $nh);
		$success = imagecopyresampled($newResource, $this->resource, 0, 0, 0, 0, $nw, $nh, $ow, $oh);
		if(!$success) {
			throw new HException("There was an error resizing the image");
		}
		$this->resource = $newResource;
	}
	public function applyCropToAspect($w, $h) {
		$ow = imagesx($this->resource);
		$oh = imagesy($this->resource);
		$oaspect = $ow / $oh;
		$taspect = $w / $h;
		$nh = $oh;
		$nw = $ow;
		if($taspect > $oaspect) {
			$nh = intval(1 / $taspect * $ow);
		}
		else {
			$nw = intval($taspect * $oh);
		}
		if($nw < 1) {
			$nw = 1;
		}
		if($nh < 1) {
			$nh = 1;
		}
		$newResource = poko_utils_GD::imageCreateTrueColor($nw, $nh);
		$success = imagecopyresampled($newResource, $this->resource, 0, 0, intval(($ow - $nw) / 2), intval(($oh - $nh) / 2), $nw, $nh, $nw, $nh);
		if(!$success) {
			throw new HException("There was an error cropping the image to aspect");
		}
		$this->resource = $newResource;
	}
	public function applyCrop($x, $y, $width, $height) {
		$newResource = poko_utils_GD::imageCreateTrueColor($width, $height);
		$success = imagecopyresampled($newResource, $this->resource, 0, 0, $x, $y, $width, $height, $width, $height);
		if(!$success) {
			throw new HException("There was an error cropping the image to aspect");
		}
		$this->resource = $newResource;
	}
	public function applyResize($width, $height) {
		$ow = imagesx($this->resource);
		$oh = imagesy($this->resource);
		$newResource = poko_utils_GD::imageCreateTrueColor($width, $height);
		$success = imagecopyresampled($newResource, $this->resource, 0, 0, 0, 0, $width, $height, $ow, $oh);
		if(!$success) {
			throw new HException("There was an error resizing the image");
		}
		$this->resource = $newResource;
	}
	public function applyScale($scaleX, $scaleY) {
		$ow = imagesx($this->resource);
		$oh = imagesy($this->resource);
		$nw = intval($ow * $scaleX);
		$nh = intval($oh * $scaleY);
		if($nw < 1) {
			$nw = 1;
		}
		if($nh < 1) {
			$nh = 1;
		}
		$newResource = poko_utils_GD::imageCreateTrueColor($nw, $nh);
		$success = imagecopyresampled($newResource, $this->resource, 0, 0, 0, 0, $nw, $nh, $ow, $oh);
		if(!$success) {
			throw new HException("There was an error applying scale to the image");
		}
		$this->resource = $newResource;
	}
	public function applyRotation($angle, $CW, $bgcolor, $transparentBg) {
		if($transparentBg === null) {
			$transparentBg = false;
		}
		if($bgcolor === null) {
			$bgcolor = 0;
		}
		if($CW === null) {
			$CW = true;
		}
		$this->resource = imagerotate($this->resource, ($CW ? $angle : $angle * -1), $bgcolor, ($transparentBg ? 1 : 0));
	}
	public function applyCustom($func, $cacheIdentifier) {
		Reflect::callMethod(null, $func, new _hx_array(array()));
	}
	public function processQueue() {
		$»it = $this->queue->iterator();
		while($»it->hasNext()) {
		$action = $»it->next();
		{
			switch($action->type) {
			case poko_utils_ImageAction::$FIT:{
				$this->applyFitSize($action->maxWidth, $action->maxHeight);
			}break;
			case poko_utils_ImageAction::$ASPECT:{
				$this->applyCropToAspect($action->w, $action->h);
			}break;
			case poko_utils_ImageAction::$RESIZE:{
				$this->applyResize($action->width, $action->height);
			}break;
			case poko_utils_ImageAction::$SCALE:{
				$this->applyScale($action->scaleX, $action->scaleY);
			}break;
			case poko_utils_ImageAction::$CROP:{
				$this->applyCrop($action->x, $action->y, $action->width, $action->height);
			}break;
			case poko_utils_ImageAction::$ROTATE:{
				$this->applyRotation($action->angle, $action->CW, $action->bgcolor, $action->transparent);
			}break;
			case poko_utils_ImageAction::$CUSTOM:{
				$this->applyCustom(isset($action->func) ? $action->func: array($action, "func"), $action->cacheIdentifier);
			}break;
			}
			;
		}
		}
		$this->queue->clear();
	}
	public function getFileFormat($file) {
		$ext = strtolower(_hx_substr($file, _hx_last_index_of($file, ".", null) + 1, null));
		return eval("if(isset(\$this)) \$»this =& \$this;switch(\$ext) {
			case \"jpg\":case \"jpeg\":{
				\$»r = poko_utils_ImageOutputFormat::\$JPG;
			}break;
			case \"gif\":{
				\$»r = poko_utils_ImageOutputFormat::\$GIF;
			}break;
			case \"png\":{
				\$»r = poko_utils_ImageOutputFormat::\$PNG;
			}break;
			default:{
				\$»r = null;
			}break;
			}
			return \$»r;
		");
	}
	public function getHash() {
		$s = $this->fileName;
		$»it = $this->queue->iterator();
		while($»it->hasNext()) {
		$action = $»it->next();
		{
			$_g = 0; $_g1 = Reflect::fields($action);
			while($_g < $_g1->length) {
				$field = $_g1[$_g];
				++$_g;
				$s .= "&" . $field . "=" . Reflect::field($action, $field);
				unset($field);
			}
			unset($field,$_g1,$_g);
		}
		}
		$stat = php_FileSystem::stat($this->fileName);
		$s .= "&" . $stat->mtime . "&" . $stat->ctime . "&" . poko_utils_ImageProcessor::$revision;
		$hash = haxe_Md5::encode($s);
		$this->dateModified = $stat->mtime;
		return $hash;
	}
	public function output() {
		ob_start();
		switch($this->format) {
		case poko_utils_ImageOutputFormat::$GIF:{
			imagegif($this->resource, null);
		}break;
		case poko_utils_ImageOutputFormat::$JPG:{
			poko_utils_GD::imageJpeg($this->resource, null, intval($this->quality * 100));
		}break;
		case poko_utils_ImageOutputFormat::$PNG:{
			imagepng($this->resource, null);
		}break;
		}
		$out = ob_get_contents();
		ob_end_clean();
		return $out;
	}
	public function getCacheName() {
		$hash = $this->getHash();
		$ext = eval("if(isset(\$this)) \$»this =& \$this;switch(\$»this->format) {
			case poko_utils_ImageOutputFormat::\$GIF:{
				\$»r = \"GIF\";
			}break;
			case poko_utils_ImageOutputFormat::\$JPG:{
				\$»r = \"JPG\";
			}break;
			case poko_utils_ImageOutputFormat::\$PNG:{
				\$»r = \"PNG\";
			}break;
			default:{
				\$»r = null;
			}break;
			}
			return \$»r;
		");
		return $hash . "." . $ext;
	}
	public function getOutput($flush) {
		if($flush === null) {
			$flush = false;
		}
		if($this->cache && !$this->forceNoCache) {
			$cacheFile = $this->cacheFolder . "/" . $this->getCacheName();
			if(file_exists($cacheFile)) {
				return php_io_File::getContent($cacheFile);
			}
			else {
				if(!$this->queue->isEmpty()) {
					$this->processQueue();
				}
				$output = $this->output();
				php_io_File::putContent($cacheFile, $output);
				return $output;
			}
		}
		else {
			if(!$this->queue->isEmpty()) {
				$this->processQueue();
			}
			return $this->output();
		}
	}
	public function flushOutput() {
		$cacheName = $this->getCacheName();
		$cacheFile = $this->cacheFolder . "/" . $cacheName;
		header("content-type" . ": " . "image");
		header("Content-Transfer-Encoding" . ": " . "binary");
		if(file_exists($cacheFile)) {
			header("Content-Disposition" . ": " . "inline; filename=" . $cacheName);
			header("Content-Length" . ": " . filesize($cacheFile));
			readfile($cacheFile);
			php_Sys::hexit(1);
		}
		else {
			php_Lib::hprint($this->getOutput(null));
		}
		php_Sys::hexit(1);
	}
	public function __call($m, $a) {
		if(isset($this->$m) && is_callable($this->$m))
			return call_user_func_array($this->$m, $a);
		else if(isset($this->»dynamics[$m]) && is_callable($this->»dynamics[$m]))
			return call_user_func_array($this->»dynamics[$m], $a);
		else
			throw new HException('Unable to call «'.$m.'»');
	}
	static $revision = "0.1";
	function __toString() { return 'poko.utils.ImageProcessor'; }
}
