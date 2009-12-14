<?php

class poko_form_validators_BoolValidator extends poko_form_Validator {
	public function __construct($valid, $error) {
		if( !php_Boot::$skip_constructor ) {
		parent::__construct();
		$this->valid = $valid;
		if($error !== null) {
			$this->errorNotValid = $error;
		}
		else {
			$this->errorNotValid = "Not valid.";
		}
	}}
	public $errorNotValid;
	public $valid;
	public function isValid($value) {
		if(!$this->valid) {
			$this->errors->push($this->errorNotValid);
		}
		return $this->valid;
	}
	public function __call($m, $a) {
		if(isset($this->$m) && is_callable($this->$m))
			return call_user_func_array($this->$m, $a);
		else if(isset($this->»dynamics[$m]) && is_callable($this->»dynamics[$m]))
			return call_user_func_array($this->»dynamics[$m], $a);
		else
			throw new HException('Unable to call «'.$m.'»');
	}
	function __toString() { return 'poko.form.validators.BoolValidator'; }
}
