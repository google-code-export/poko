<?php

class poko_form_validators_RegexValidator extends poko_form_Validator {
	public function __construct($regex, $errorMessage) {
		if( !php_Boot::$skip_constructor ) {
		parent::__construct();
		$this->regex = $regex;
		$this->errorRegex = poko_form_validators_RegexValidator_0($this, $errorMessage, $regex);
		;
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
	function __toString() { return 'poko.form.validators.RegexValidator'; }
}
;
function poko_form_validators_RegexValidator_0(&$»this, &$errorMessage, &$regex) {
if($errorMessage !== null) {
	return $errorMessage;
	;
}
else {
	return "Regex Failed";
	;
}
}