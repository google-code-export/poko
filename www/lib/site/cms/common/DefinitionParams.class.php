<?php

class site_cms_common_DefinitionParams {
	public function __construct() {
		if( !php_Boot::$skip_constructor ) {
		$this->usePaging = false;
		$this->perPage = 20;
		$this->pagingRange = 4;
		$this->useTabulation = false;
		$this->tabulationFields = null;
		;
	}}
	public $usePaging;
	public $perPage;
	public $pagingRange;
	public $useTabulation;
	public $tabulationFields;
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
	function __toString() { return 'site.cms.common.DefinitionParams'; }
}
