<?php

class site_cms_common_Procedure {
	public function __construct() {
		if( !php_Boot::$skip_constructor ) {
		$this->poko = poko_Poko::$instance;
	}}
	public $poko;
	public function postCreate($table, $data, $id) {
		return false;
	}
	public function postUpdate($table, $oldData, $newData) {
		return false;
	}
	public function postDelete($table, $data) {
		return false;
	}
	public function postDuplicate($table, $oldData, $newData) {
		return false;
	}
	public function __call($m, $a) {
		if(isset($this->$m) && is_callable($this->$m))
			return call_user_func_array($this->$m, $a);
		else if(isset($this->»dynamics[$m]) && is_callable($this->»dynamics[$m]))
			return call_user_func_array($this->»dynamics[$m], $a);
		else
			throw new HException('Unable to call «'.$m.'»');
	}
	function __toString() { return 'site.cms.common.Procedure'; }
}
