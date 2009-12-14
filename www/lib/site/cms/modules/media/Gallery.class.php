<?php

class site_cms_modules_media_Gallery extends site_cms_modules_media_MediaBase {
	public function __construct() {
		if( !php_Boot::$skip_constructor ) {
		parent::__construct();
	}}
	public $gallery;
	public $jsBinding;
	public function init() {
		parent::init();
		$this->head->css->add("css/cms/media.css");
		$this->head->js->add("js/cms/media/swfobject.js");
		$this->head->js->add("js/cms/media/jquery.uploadify.v2.1.0.min.js");
		$this->gallery = $this->app->params->get("name");
		$this->remoting->addObject("api", _hx_anonymous(array("getContent" => isset($this->getContent) ? $this->getContent: array($this, "getContent"), "deleteItem" => isset($this->deleteItem) ? $this->deleteItem: array($this, "deleteItem"))), null);
		$this->jsBinding = new poko_js_JsBinding("site.cms.modules.media.js.JsGallery");
		$this->jsBinding->queueCall("init", new _hx_array(array($this->gallery)), null);
	}
	public function main() {
		$this->setupLeftNav();
	}
	public function getContent() {
		return Lambda::hlist(php_FileSystem::readDirectory($this->imageRoot . "/" . $this->gallery));
	}
	public function deleteItem($file) {
		@unlink($this->imageRoot . "/" . $this->gallery . "/" . $file);
	}
	public function __call($m, $a) {
		if(isset($this->$m) && is_callable($this->$m))
			return call_user_func_array($this->$m, $a);
		else if(isset($this->»dynamics[$m]) && is_callable($this->»dynamics[$m]))
			return call_user_func_array($this->»dynamics[$m], $a);
		else
			throw new HException('Unable to call «'.$m.'»');
	}
	function __toString() { return 'site.cms.modules.media.Gallery'; }
}
