<?php

class poko_form_validators_DateTimeValidator extends poko_form_Validator {
	public function __construct($mode, $minDate, $maxDate) {
		if( !php_Boot::$skip_constructor ) {
		parent::__construct();
		if($mode === site_cms_common_DateTimeMode::$date) {
			$this->format = new EReg("[0-9]{4}-[0-9]{2}-[0-9]{2}", null);
			$this->errorDateNotValid = "Is not in the correct format. YYYY-MM-DD is required.";
			;
		}
		else {
			if($mode === site_cms_common_DateTimeMode::$time) {
				$this->format = new EReg("[0-9]{2}:[0-9]{2}:[0-9]{2}", null);
				$this->errorDateNotValid = "Is not in the correct format. HH:MM:SS is required.";
				;
			}
			else {
				$this->format = new EReg("[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}:[0-9]{2}", null);
				$this->errorDateNotValid = "Is not in the correct format. YYYY-MM-DD HH:MM:SS is required.";
				;
			}
			;
		}
		$this->errorDateOutOfRange = "Must be between %s and %s";
		if($minDate !== null) {
			$this->minDate = $minDate;
			;
		}
		if($maxDate !== null) {
			$this->maxDate = $maxDate;
			;
		}
		;
	}}
	public $format;
	public $minDate;
	public $maxDate;
	public $errorDateOutOfRange;
	public $errorDateNotValid;
	public $errorDateNotExist;
	public function isValid($value) {
		$valid = true;
		$d = null;
		if(Type::getClass($value) == _hx_qtype("Date")) {
			$d = $value;
			;
		}
		else {
			if(Type::getClass($value) === _hx_qtype("String") && $this->format->match($value)) {
				$d = Date::fromString($value);
				;
			}
			else {
				$this->errors->add($this->errorDateNotValid);
				return false;
				;
			}
			;
		}
		if($this->minDate !== null) {
			if($d->getTime() < $this->minDate->getTime()) {
				$valid = false;
				;
			}
			;
		}
		if($this->maxDate !== null) {
			if($d->getTime() > $this->maxDate->getTime()) {
				$valid = false;
				;
			}
			;
		}
		if(!$valid) {
			$this->errors->add(poko_utils_StringTools2::printf($this->errorDateOutOfRange, new _hx_array(array($this->dateOnly($this->minDate), $this->dateOnly($this->maxDate)))));
			;
		}
		return $valid;
		unset($valid,$d);
	}
	public function dateOnly($d) {
		return (((str_pad(Std::string($d->getFullYear()), 4, "0", STR_PAD_LEFT) . "-") . str_pad(Std::string($d->getMonth()), 2, "0", STR_PAD_LEFT)) . "-") . str_pad(Std::string($d->getDate()), 2, "0", STR_PAD_LEFT);
		;
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
	function __toString() { return 'poko.form.validators.DateTimeValidator'; }
}
