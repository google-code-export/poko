<?php

class poko_form_validators_EmailValidator extends poko_form_Validator {
	public function __construct() {
		if( !php_Boot::$skip_constructor ) {
		parent::__construct();
		$this->errorNotValid = "Not a valid email address";
		;
	}}
	public $errorNotValid;
	public function isValid($value) {
		parent::isValid($value);
		$valid = poko_form_validators_EmailValidator::$emailRegex->match(Std::string($value));
		if(!$valid) {
			$this->errors->add($this->errorNotValid);
			;
		}
		return $valid;
		unset($valid);
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
	static function check($value) {
		$val = new poko_form_validators_EmailValidator();
		return $val->isValid($value);
		unset($val);
	}
	static $emailRegex;
	function __toString() { return 'poko.form.validators.EmailValidator'; }
}
poko_form_validators_EmailValidator::$emailRegex = new EReg("^([a-zA-Z0-9_\\-\\.\\+]+)@((\\[[0-9]{1,3}\\.[0-9]{1,3}\\.[0-9]{1,3}\\.)|(([a-zA-Z0-9\\-]+\\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\\]?)", "i");
