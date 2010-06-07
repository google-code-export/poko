<?php

class site_cms_components_PopupURL extends poko_Component {
	public function __construct($id, $label, $contentUrl, $width, $height) {
		if( !php_Boot::$skip_constructor ) {
		if($height === null) {
			$height = 300;
		}
		if($width === null) {
			$width = 400;
		}
		parent::__construct();
		$this->id = $id;
		$this->label = $label;
		$this->contentUrl = $contentUrl;
		$this->width = $width;
		$this->height = $height;
		$application = poko_Application::$instance;
		$application->request->head->js->add("js/cms/jqModal.js");
		$application->request->head->css->add("css/cms/jqModal.css");
	}}
	public $width;
	public $height;
	public $contentUrl;
	public $id;
	public $label;
	public function __call($m, $a) {
		if(isset($this->$m) && is_callable($this->$m))
			return call_user_func_array($this->$m, $a);
		else if(isset($this->»dynamics[$m]) && is_callable($this->»dynamics[$m]))
			return call_user_func_array($this->»dynamics[$m], $a);
		else
			throw new HException('Unable to call «'.$m.'»');
	}
	function __toString() { return 'site.cms.components.PopupURL'; }
}
