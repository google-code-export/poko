<?php

class site_cms_modules_media_Galleries extends site_cms_modules_media_MediaBase {
	public function __construct() {
		if( !php_Boot::$skip_constructor ) {
		parent::__construct();
		;
	}}
	public $galleries;
	public function init() {
		parent::init();
		if($this->app->params->get("action")) {
			$this->process();
			;
		}
		;
	}
	public function main() {
		$this->galleries = new HList();
		$dir = php_FileSystem::readDirectory($this->imageRoot);
		{
			$_g = 0;
			while($_g < $dir->length) {
				$d = $dir[$_g];
				++$_g;
				if(is_dir(($this->imageRoot . "/") . $d) && $d !== "." && $d !== ".." && $d !== ".svn") {
					$this->galleries->add(_hx_anonymous(array("name" => $d)));
					;
				}
				unset($d);
			}
			unset($_g);
		}
		$this->setupLeftNav();
		unset($dir);
	}
	public function process() {
		switch($this->app->params->get("action")) {
		case "add":{
			$dir = ($this->imageRoot . "/") . $this->app->params->get("newGallery");
			if(!file_exists($dir)) {
				@mkdir($dir, 493);
				$this->messages->addMessage("Gallery Added");
				;
			}
			else {
				$this->messages->addError("A Gallery of this name already exists");
				;
			}
			unset($dir);
		}break;
		}
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
	function __toString() { return 'site.cms.modules.media.Galleries'; }
}
