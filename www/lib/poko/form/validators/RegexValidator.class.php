<?php

class poko_form_validators_RegexValidator extends poko_form_Validator {
	public function __construct($regex, $errorMessage) {
		if( !php_Boot::$skip_constructor ) {
		parent::__construct();
		$this->regex = $regex;
		$this->errorRegex = (($errorMessage !== null) ? $errorMessage : "Regex Failed");
	}}
	public $regex;
	public $regexOptions;
	public $errorRegex;
	public function isValid($value) {
		parent::isValid($value);
		$valid = true;
		if(!$this->regex->match(Std::string($value))) {
			$this->errors->add(poko_utils_StringTools2::printf($this->errorRegex, new _hx_array(array($this->regex))));
			$valid = false;
		}
		return $valid;
	}
	public function __call($m, $a) {
		if(isset($this->$m) && is_callable($this->$m))
			return call_user_func_array($this->$m, $a);
		else if(isset($this->»dynamics[$m]) && is_callable($this->»dynamics[$m]))
			return call_user_func_array($this->»dynamics[$m], $a);
		else
			throw new HException('Unable to call «'.$m.'»');
	}
	function __toString() { return 'poko.form.validators.RegexValidator'; }
}
