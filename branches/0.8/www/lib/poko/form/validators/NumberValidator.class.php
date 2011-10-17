<?php

class poko_form_validators_NumberValidator extends poko_form_Validator {
	public function __construct($min, $max, $isInt) {
		if( !php_Boot::$skip_constructor ) {
		if($isInt === null) {
			$isInt = false;
			;
		}
		if($max === null) {
			$max = 999999999999;
			;
		}
		if($min === null) {
			$min = 0;
			;
		}
		parent::__construct();
		$this->min = $min;
		$this->max = $max;
		$this->isInt = $isInt;
		$this->errorNumber = "Must be a number";
		$this->errorInt = "Must be an integer";
		$this->errorMin = "Minimum number %s";
		$this->errorMax = "Maximum number %s";
		;
	}}
	public $isInt;
	public $min;
	public $max;
	public $errorNumber;
	public $errorInt;
	public $errorMin;
	public $errorMax;
	public function isValid($value) {
		parent::isValid($value);
		$valid = true;
		$f = Std::parseFloat(Std::string($value));
		$i = intval($f);
		if(Math::isNaN($f)) {
			$this->errors->add($this->errorNumber);
			$valid = false;
			;
		}
		else {
			if($this->isInt && !_hx_equal($i, $f)) {
				$this->errors->add($this->errorInt);
				$valid = false;
				;
			}
			$n = poko_form_validators_NumberValidator_0($this, $f, $i, $valid, $value);
			if($n < $this->min) {
				$this->errors->add(poko_utils_StringTools2::printf($this->errorMin, new _hx_array(array($this->min))));
				$valid = false;
				;
			}
			else {
				if($n > $this->max) {
					$this->errors->add(poko_utils_StringTools2::printf($this->errorMax, new _hx_array(array($this->max))));
					$valid = false;
					;
				}
				;
			}
			unset($n);
		}
		return $valid;
		unset($valid,$i,$f);
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
	function __toString() { return 'poko.form.validators.NumberValidator'; }
}
;
function poko_form_validators_NumberValidator_0(&$»this, &$f, &$i, &$valid, &$value) {
if($»this->isInt) {
	return $i;
	;
}
else {
	return $f;
	;
}
}