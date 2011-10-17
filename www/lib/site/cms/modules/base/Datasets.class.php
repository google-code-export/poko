<?php

class site_cms_modules_base_Datasets extends site_cms_modules_base_DatasetBase {
	public function __construct() {
		if( !php_Boot::$skip_constructor ) {
		parent::__construct();
		;
	}}
	public $data;
	public function main() {
		if($this->app->params->get("manage") === null) {
			$str = "";
			if($this->user->isAdmin() || $this->user->isSuper()) {
				$str .= poko_views_renderers_Templo::parse("cms/modules/base/blocks/datasets.mtt", _hx_anonymous(array()));
				;
			}
			$this->setContentOutput($str);
			$this->setupLeftNav();
			return;
			unset($str);
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
	function __toString() { return 'site.cms.modules.base.Datasets'; }
}
