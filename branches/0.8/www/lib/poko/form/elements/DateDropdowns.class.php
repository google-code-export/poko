<?php

class poko_form_elements_DateDropdowns extends poko_form_FormElement {
	public function __construct($name, $label, $value, $required, $yearMin, $yearMax, $validators, $attibutes) {
		if( !php_Boot::$skip_constructor ) {
		if($attibutes === null) {
			$attibutes = "";
			;
		}
		if($yearMin === null) {
			$yearMin = 1950;
			;
		}
		if($required === null) {
			$required = false;
			;
		}
		parent::__construct();
		$this->name = $name;
		$this->label = $label;
		$this->datetime = Std::string($value);
		$this->value = $value;
		$this->required = $required;
		$this->attributes = $attibutes;
		$this->yearMin = $yearMin;
		$this->yearMax = $yearMax;
		$this->maxOffset = null;
		$this->minOffset = null;
		$day = "";
		$month = "";
		$year = "";
		if($value !== null) {
			$day = "" . ($value->getDate());
			$month = "" . ($value->getMonth() + 1);
			$year = "" . $value->getFullYear();
			;
		}
		$this->daySelector = new poko_form_elements_Selectbox("", "Birth Day", poko_utils_ListData::getDays(null), $day, true, "-Day-", "title=\"Day\"");
		$this->monthSelector = new poko_form_elements_Selectbox("", "Birth Month", poko_utils_ListData::arrayToList(poko_utils_ListData::$months, 1), $month, true, "-Month-", "title=\"Month\"");
		$this->yearSelector = new poko_form_elements_Selectbox("", "Birth Year", poko_utils_ListData::getYears(1920, Date::now()->getFullYear(), true), $year, true, "-Year-", "title=\"Year\"");
		$this->daySelector->internal = $this->monthSelector->internal = $this->yearSelector->internal = true;
		unset($year,$month,$day);
	}}
	public $maxOffset;
	public $minOffset;
	public $datetime;
	public $yearMin;
	public $yearMax;
	public $daySelector;
	public $monthSelector;
	public $yearSelector;
	public function shortLabels() {
		$this->daySelector->nullMessage = "-D-";
		$this->monthSelector->nullMessage = "-M-";
		$this->yearSelector->nullMessage = "-Y-";
		$this->monthSelector->data = poko_utils_ListData::arrayToList(poko_utils_ListData::$months_short, 1);
		;
	}
	public function init() {
		parent::init();
		$this->form->addElement($this->daySelector, null);
		$this->form->addElement($this->monthSelector, null);
		$this->form->addElement($this->yearSelector, null);
		;
	}
	public function populate() {
		$n = ($this->form->name . "_") . $this->name;
		$day = Std::parseInt(poko_Poko::$instance->params->get($n . "Day"));
		$month = Std::parseInt(poko_Poko::$instance->params->get($n . "Month"));
		$year = Std::parseInt(poko_Poko::$instance->params->get($n . "Year"));
		$this->value = poko_form_elements_DateDropdowns_0($this, $day, $month, $n, $year);
		unset($year,$n,$month,$day);
	}
	public function isValid() {
		$valid = parent::isValid();
		if($this->required && $valid) {
			$n = ($this->form->name . "_") . $this->name;
			$day = Std::parseInt(poko_Poko::$instance->params->get($n . "Day"));
			$month = Std::parseInt(poko_Poko::$instance->params->get($n . "Month"));
			$year = Std::parseInt(poko_Poko::$instance->params->get($n . "Year"));
			if($day === null || $month === null || $year === null) {
				$this->errors->add(("<span class=\"formErrorsField\">" . (poko_form_elements_DateDropdowns_1($this, $day, $month, $n, $valid, $year))) . "</span> is an invalid date.");
				return false;
				;
			}
			return true;
			unset($year,$n,$month,$day);
		}
		return $valid;
		unset($valid);
	}
	public function getValue() {
		$n = ($this->form->name . "_") . $this->name;
		$day = Std::parseInt(poko_Poko::$instance->params->get($n . "Day"));
		$month = Std::parseInt(poko_Poko::$instance->params->get($n . "Month"));
		$year = Std::parseInt(poko_Poko::$instance->params->get($n . "Year"));
		return ((($year . "-") . $month) . "-") . $day;
		unset($year,$n,$month,$day);
	}
	public function render() {
		parent::render();
		$s = "";
		$this->daySelector->name = $this->name . "Day";
		$this->monthSelector->name = $this->name . "Month";
		$this->yearSelector->name = $this->name . "Year";
		if(!_hx_equal($this->value, "") && _hx_field($this, "value") !== null && !_hx_equal($this->value, "null")) {
			$v = $this->value;
			$this->daySelector->value = $v->getDate();
			$this->monthSelector->value = $v->getMonth() + 1;
			$this->yearSelector->value = $v->getFullYear();
			unset($v);
		}
		$s .= $this->daySelector->render();
		$s .= " / ";
		$s .= $this->monthSelector->render();
		$s .= " / ";
		$s .= $this->yearSelector->render();
		return $s;
		unset($s);
	}
	public function toString() {
		return $this->render();
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
	function __toString() { return $this->toString(); }
}
;
function poko_form_elements_DateDropdowns_0(&$»this, &$day, &$month, &$n, &$year) {
if($day !== null && $month !== null && $year !== null) {
	return new Date($year, $month - 1, $day, 0, 0, 0);
	;
}
}
function poko_form_elements_DateDropdowns_1(&$»this, &$day, &$month, &$n, &$valid, &$year) {
if($»this->label !== null && $»this->label !== "") {
	return $»this->label;
	;
}
else {
	return $»this->name;
	;
}
}