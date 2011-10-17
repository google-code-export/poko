<?php

class poko_form_validators_StringValidator extends poko_form_Validator {
	public function __construct($minChars, $maxChars, $charList, $mode, $regex, $regexError) {
		if( !php_Boot::$skip_constructor ) {
		if($charList === null) {
			$charList = "";
			;
		}
		if($maxChars === null) {
			$maxChars = 999999;
			;
		}
		if($minChars === null) {
			$minChars = 0;
			;
		}
		parent::__construct();
		$this->errorMinChars = "Must be at least %s characters long";
		$this->errorMaxChars = "Must be less than  %s characters long";
		$this->errorDenyChars = "Cannot contain the characters '%s'";
		$this->errorAllowChars = "Must contain only the characers '%s'";
		$this->minChars = $minChars;
		$this->maxChars = $maxChars;
		$this->charList = $charList;
		$this->mode = $mode;
		if($this->mode === null) {
			$this->mode = poko_form_validators_StringValidatorMode::$ALLOW;
			;
		}
		$this->regex = $regex;
		$this->regexError = poko_form_validators_StringValidator_0($this, $charList, $maxChars, $minChars, $mode, $regex, $regexError);
		$this->errors = new HList();
		;
	}}
	public $minChars;
	public $maxChars;
	public $charList;
	public $mode;
	public $regex;
	public $regexError;
	public $errorMinChars;
	public $errorMaxChars;
	public $errorDenyChars;
	public $errorAllowChars;
	public function isValid($value) {
		parent::isValid($value);
		$valid = true;
		$s = Std::string($value);
		if($this->minChars !== null && $this->minChars > 0 && strlen($s) < $this->minChars) {
			$valid = false;
			$this->errors->add(poko_utils_StringTools2::printf($this->errorMinChars, new _hx_array(array($this->minChars))));
			;
		}
		if($this->maxChars !== null && $this->maxChars > 0 && strlen($s) > $this->maxChars) {
			$valid = false;
			$this->errors->add(poko_utils_StringTools2::printf($this->errorMaxChars, new _hx_array(array($this->maxChars))));
			;
		}
		if(strlen($this->charList) > 0) {
			switch($this->mode) {
			case poko_form_validators_StringValidatorMode::$ALLOW:{
				{
					$_g1 = 0; $_g = strlen($s);
					while($_g1 < $_g) {
						$i = $_g1++;
						$letter = _hx_char_at($s, $i);
						if(_hx_index_of($this->charList, $letter, null) === -1) {
							$valid = false;
							$this->errors->add(poko_utils_StringTools2::printf($this->errorAllowChars, new _hx_array(array($this->charList))));
							break;
							;
						}
						unset($letter,$i);
					}
					unset($_g1,$_g);
				}
				;
			}break;
			case poko_form_validators_StringValidatorMode::$DENY:{
				{
					$_g1 = 0; $_g = strlen($s);
					while($_g1 < $_g) {
						$i = $_g1++;
						$letter = _hx_char_at($s, $i);
						if(_hx_index_of($this->charList, $letter, null) !== -1) {
							$valid = false;
							$this->errors->add(poko_utils_StringTools2::printf($this->errorDenyChars, new _hx_array(array($this->charList))));
							break;
							;
						}
						unset($letter,$i);
					}
					unset($_g1,$_g);
				}
				;
			}break;
			}
			;
		}
		if($this->regex !== null) {
			if(!$this->regex->match($s)) {
				$valid = false;
				$this->errors->add($this->regexError);
				;
			}
			;
		}
		return $valid;
		unset($valid,$s);
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
	function __toString() { return 'poko.form.validators.StringValidator'; }
}
;
function poko_form_validators_StringValidator_0(&$»this, &$charList, &$maxChars, &$minChars, &$mode, &$regex, &$regexError) {
if($regexError !== null) {
	return $regexError;
	;
}
else {
	return "Doesn't match required input.";
	;
}
}