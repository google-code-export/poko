<?php

class poko_form_validators_StringValidator extends poko_form_Validator {
	public function __construct($minChars, $maxChars, $charList, $mode, $regex, $regexError) {
		if( !php_Boot::$skip_constructor ) {
		if($charList === null) {
			$charList = "";
		}
		if($maxChars === null) {
			$maxChars = 999999;
		}
		if($minChars === null) {
			$minChars = 0;
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
		}
		$this->regex = $regex;
		$this->regexError = ($regexError !== null ? $regexError : "Doesn't match required input.");
		$this->errors = new HList();
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
		}
		if($this->maxChars !== null && $this->maxChars > 0 && strlen($s) > $this->maxChars) {
			$valid = false;
			$this->errors->add(poko_utils_StringTools2::printf($this->errorMaxChars, new _hx_array(array($this->maxChars))));
		}
		if(strlen($this->charList) > 0) {
			switch($this->mode) {
			case poko_form_validators_StringValidatorMode::$ALLOW:{
				{
					$_g1 = 0; $_g = strlen($s);
					while($_g1 < $_g) {
						$i = $_g1++;
						$letter = substr($s, $i, 1);
						if(_hx_index_of($this->charList, $letter, null) === -1) {
							$valid = false;
							$this->errors->add(poko_utils_StringTools2::printf($this->errorAllowChars, new _hx_array(array($this->charList))));
							break;
						}
						unset($letter,$i);
					}
				}
			}break;
			case poko_form_validators_StringValidatorMode::$DENY:{
				{
					$_g12 = 0; $_g2 = strlen($s);
					while($_g12 < $_g2) {
						$i2 = $_g12++;
						$letter2 = substr($s, $i2, 1);
						if(_hx_index_of($this->charList, $letter2, null) !== -1) {
							$valid = false;
							$this->errors->add(poko_utils_StringTools2::printf($this->errorDenyChars, new _hx_array(array($this->charList))));
							break;
						}
						unset($letter2,$i2);
					}
				}
			}break;
			}
		}
		if($this->regex !== null) {
			if(!$this->regex->match($s)) {
				$valid = false;
				$this->errors->add($this->regexError);
			}
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
	function __toString() { return 'poko.form.validators.StringValidator'; }
}
