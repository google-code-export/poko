<?php

class poko_form_Validator {
	public function __construct() {
		if( !php_Boot::$skip_constructor ) {
		$this->errors = new HList();
	}}
	public $errors;
	public function isValid($value) {
		$this->errors->clear();
		return true;
	}
	public function reset() {
		$this->errors->clear();
	}
	public function __call($m, $a) {
		if(isset($this->$m) && is_callable($this->$m))
			return call_user_func_array($this->$m, $a);
		else if(isset($this->»dynamics[$m]) && is_callable($this->»dynamics[$m]))
			return call_user_func_array($this->»dynamics[$m], $a);
		else
			throw new HException('Unable to call «'.$m.'»');
	}
	function __toString() { return 'poko.form.Validator'; }
}
