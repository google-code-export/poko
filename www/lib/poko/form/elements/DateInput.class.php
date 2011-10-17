<?php

class poko_form_elements_DateInput extends poko_form_elements_DateDropdowns {
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
		parent::__construct($name,$label,$value,$required,$yearMin,$yearMax,$validators,$attibutes);
		$data = _hx_anonymous(array());
		$this->hourSelector = new poko_form_elements_Selectbox("", "Hour", poko_utils_ListData::getDateElement(0, 23, null), $data->childsBirthDay, true, "-", "title=\"Hour\"");
		$this->minuteSelector = new poko_form_elements_Selectbox("", "Minute", poko_utils_ListData::getDateElement(0, 59, null), $data->childsBirthMonth, true, "-", "title=\"Minute\"");
		$this->hiddenData = new poko_form_elements_Hidden("", $value, false, null, null);
		$this->nullBox = new poko_form_elements_Checkbox("", "Null", false, false, null);
		unset($data);
	}}
	public $hourSelector;
	public $minuteSelector;
	public $hiddenData;
	public $nullBox;
	public function init() {
		parent::init();
		$this->form->addElement($this->hourSelector, null);
		$this->form->addElement($this->minuteSelector, null);
		$this->form->addElement($this->hiddenData, null);
		$this->form->addElement($this->nullBox, null);
		;
	}
	public function isValid() {
		$valid = parent::isValid();
		return $valid;
		unset($valid);
	}
	public function render() {
		$s = parent::render() . " : ";
		$this->hourSelector->name = $this->name . "Hour";
		$this->minuteSelector->name = $this->name . "Minute";
		$this->hiddenData->name = $this->name;
		$this->nullBox->name = $this->name . "Null";
		if(!_hx_equal($this->value, "") && _hx_field($this, "value") !== null && !_hx_equal($this->value, "null")) {
			$v = $this->value;
			$this->hourSelector->value = $v->getHours();
			$this->minuteSelector->value = $v->getMinutes();
			unset($v);
		}
		$this->hiddenData->value = $this->value;
		$s .= $this->hourSelector->render() . "h ";
		$s .= $this->minuteSelector->render() . "m ";
		$s .= $this->nullBox->render() . " Clear?";
		$s .= $this->hiddenData->render();
		return $s;
		unset($s);
	}
	public function populate() {
		$n = ($this->form->name . "_") . $this->name;
		$makeNull = poko_Poko::$instance->params->exists((($this->form->name . "_") . $this->name) . "Null");
		if(!$makeNull) {
			$minute = Std::parseInt(poko_Poko::$instance->params->get($n . "Minute"));
			$hour = Std::parseInt(poko_Poko::$instance->params->get($n . "Hour"));
			$day = Std::parseInt(poko_Poko::$instance->params->get($n . "Day"));
			$month = Std::parseInt(poko_Poko::$instance->params->get($n . "Month"));
			$year = Std::parseInt(poko_Poko::$instance->params->get($n . "Year"));
			if($minute !== null && $hour !== null && $day !== null && $month !== null && $year !== null) {
				$this->value = new Date($year, $month - 1, $day, $hour, $minute, 0);
				;
			}
			else {
				$hidden = poko_form_elements_DateInput_0($this, $day, $hour, $makeNull, $minute, $month, $n, $year);
				$this->value = poko_form_elements_DateInput_1($this, $day, $hidden, $hour, $makeNull, $minute, $month, $n, $year);
				unset($hidden);
			}
			unset($year,$month,$minute,$hour,$day);
		}
		else {
			$this->value = null;
			;
		}
		unset($n,$makeNull);
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
	function __toString() { return 'poko.form.elements.DateInput'; }
}
;
function poko_form_elements_DateInput_0(&$»this, &$day, &$hour, &$makeNull, &$minute, &$month, &$n, &$year) {
if(poko_Poko::$instance->params->exists($n)) {
	return poko_Poko::$instance->params->get($n);
	;
}
else {
	return $»this->value;
	;
}
}
function poko_form_elements_DateInput_1(&$»this, &$day, &$hidden, &$hour, &$makeNull, &$minute, &$month, &$n, &$year) {
if($hidden !== null && $hidden !== "") {
	return Date::fromString($hidden);
	;
}
}