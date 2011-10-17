<?php

class poko_form_elements_DateSelector extends poko_form_FormElement {
	public function __construct($name, $label, $value, $required, $validators, $attibutes) {
		if( !php_Boot::$skip_constructor ) {
		if($attibutes === null) {
			$attibutes = "";
			;
		}
		if($required === null) {
			$required = false;
			;
		}
		parent::__construct();
		$this->name = $name;
		$this->label = $label;
		if($value !== null) {
			$this->datetime = Std::string($value);
			$this->value = $this->datetime;
			;
		}
		else {
			$this->datetime = null;
			$this->value = null;
			;
		}
		$this->mode = site_cms_common_DateTimeMode::$date;
		$this->required = $required;
		$this->attributes = $attibutes;
		$this->maxOffset = null;
		$this->minOffset = null;
		;
	}}
	public $maxOffset;
	public $minOffset;
	public $datetime;
	public $mode;
	public function render() {
		$n = ($this->form->name . "_") . $this->name;
		$sb = new StringBuf();
		$s = new StringBuf();
		$n_time = $n . "__time";
		$n_date = $n . "__date";
		$dtDate = _hx_substr($this->datetime, 0, 10);
		$dtHour = _hx_substr($this->datetime, 11, 2);
		$dtMin = _hx_substr($this->datetime, 14, 2);
		$dtSec = _hx_substr($this->datetime, 17, 2);
		if($this->mode == site_cms_common_DateTimeMode::$date || $this->mode == site_cms_common_DateTimeMode::$dateTime) {
			$s->b .= ((((("<input type=\"text\" name=\"" . $n_date) . "\" id=\"") . $n_date) . "\" value=\"") . $dtDate) . "\" /> \x0A";
			;
		}
		if($this->mode == site_cms_common_DateTimeMode::$time || $this->mode == site_cms_common_DateTimeMode::$dateTime) {
			$s->b .= (((" H: <select name=\"" . $n_time) . "_hour\" id=\"") . $n_time) . "_hour\"> \x0A";
			{
				$_g = 0;
				while($_g < 24) {
					$i = $_g++;
					$hour = poko_form_elements_DateSelector_0($this, $_g, $dtDate, $dtHour, $dtMin, $dtSec, $i, $n, $n_date, $n_time, $s, $sb);
					if($hour === $dtHour) {
						$s->b .= ((("\x09\x09<option value=\"" . $hour) . "\" selected=\"selected\">") . $hour) . "</option> \x0A";
						;
					}
					else {
						$s->b .= ((("\x09\x09<option value=\"" . $hour) . "\">") . $hour) . "</option> \x0A";
						;
					}
					unset($i,$hour);
				}
				unset($_g);
			}
			$s->b .= "</select> \x0A";
			$s->b .= (((" M: <select name=\"" . $n_time) . "_min\" id=\"") . $n_time) . "_min\"> \x0A";
			{
				$_g = 0;
				while($_g < 60) {
					$i = $_g++;
					$minute = poko_form_elements_DateSelector_1($this, $_g, $dtDate, $dtHour, $dtMin, $dtSec, $i, $n, $n_date, $n_time, $s, $sb);
					if($minute === $dtMin) {
						$s->b .= ((("\x09\x09<option value=\"" . $minute) . "\" selected=\"selected\">") . $minute) . "</option> \x0A";
						;
					}
					else {
						$s->b .= ((("\x09\x09<option value=\"" . $minute) . "\">") . $minute) . "</option> \x0A";
						;
					}
					unset($minute,$i);
				}
				unset($_g);
			}
			$s->b .= "</select> \x0A";
			$s->b .= (((" S: <select name=\"" . $n_time) . "_sec\" id=\"") . $n_time) . "_sec\"> \x0A";
			{
				$_g = 0;
				while($_g < 60) {
					$i = $_g++;
					$second = poko_form_elements_DateSelector_2($this, $_g, $dtDate, $dtHour, $dtMin, $dtSec, $i, $n, $n_date, $n_time, $s, $sb);
					if($second === $dtSec) {
						$s->b .= ((("\x09\x09<option value=\"" . $second) . "\" selected=\"selected\">") . $second) . "</option> \x0A";
						;
					}
					else {
						$s->b .= ((("\x09\x09<option value=\"" . $second) . "\">") . $second) . "</option> \x0A";
						;
					}
					unset($second,$i);
				}
				unset($_g);
			}
			$s->b .= "</select> \x0A";
			;
		}
		$s->b .= ((((("<input type=\"hidden\" name=\"" . $n) . "\" id=\"") . $n) . "\" value=\"") . $this->value) . "\" /> \x0A";
		$s->b .= "<script type=\"text/javascript\">\x09\x09\x09\x0A";
		$s->b .= "\x09\x09\$(function() {\x09\x09\x09\x09\x09\x09\x09\x0A";
		$maxOffsetStr = poko_form_elements_DateSelector_3($this, $dtDate, $dtHour, $dtMin, $dtSec, $n, $n_date, $n_time, $s, $sb);
		$minOffsetStr = poko_form_elements_DateSelector_4($this, $dtDate, $dtHour, $dtMin, $dtSec, $maxOffsetStr, $n, $n_date, $n_time, $s, $sb);
		$s->b .= (((("\x09\x09\x09\$(\"#" . $n_date) . "\").datepicker({ clickInput:true, dateFormat: \"yy-mm-dd\" ") . $minOffsetStr) . $maxOffsetStr) . " });\x09\x09\x0A";
		if($this->mode == site_cms_common_DateTimeMode::$date || $this->mode == site_cms_common_DateTimeMode::$dateTime) {
			$s->b .= ("\x09\x09\x09\$(\"#" . $n_date) . "\").change( updateDateTime ); \x0A";
			;
		}
		if($this->mode == site_cms_common_DateTimeMode::$time || $this->mode == site_cms_common_DateTimeMode::$dateTime) {
			$s->b .= ("\x09\x09\x09\$(\"#" . $n_time) . "_hour\").change( updateDateTime ); \x0A";
			$s->b .= ("\x09\x09\x09\$(\"#" . $n_time) . "_min\").change( updateDateTime ); \x0A";
			$s->b .= ("\x09\x09\x09\$(\"#" . $n_time) . "_sec\").change( updateDateTime ); \x0A";
			;
		}
		$s->b .= "\x09\x09}); \x09\x09\x09\x09\x09\x09\x09\x09\x09\x0A";
		$s->b .= "\x09\x09function updateDateTime() {\x0A";
		if($this->mode == site_cms_common_DateTimeMode::$time) {
			$s->b .= ((((((("\x09\x09\x09\$('#" . $n) . "').val( \$('#") . $n_time) . "_hour').val() + ':' + \$('#") . $n_time) . "_min').val() + ':' + \$('#") . $n_time) . "_sec').val() ); \x0A";
			;
		}
		else {
			if($this->mode == site_cms_common_DateTimeMode::$date) {
				$s->b .= ((("\x09\x09\x09\$('#" . $n) . "').val( \$('#") . $n_date) . "').val() ); \x0A";
				;
			}
			else {
				$s->b .= ((((((((("\x09\x09\x09\$('#" . $n) . "').val( \$('#") . $n_date) . "').val() + ' ' + \$('#") . $n_time) . "_hour').val() + ':' + \$('#") . $n_time) . "_min').val() + ':' + \$('#") . $n_time) . "_sec').val() ); \x0A";
				;
			}
			;
		}
		$s->b .= "\x09\x09}\x0A";
		$s->b .= "</script> \x09\x09\x09\x09\x09\x09\x09\x09\x09\x0A";
		return $s->b;
		unset($sb,$s,$n_time,$n_date,$n,$minOffsetStr,$maxOffsetStr,$dtSec,$dtMin,$dtHour,$dtDate);
	}
	public function toString() {
		return $this->render();
		;
	}
	public function populate() {
		$n = ($this->form->name . "_") . $this->name;
		$v = poko_Poko::$instance->params->get($n);
		if($v !== null) {
			$this->datetime = Std::string($v);
			$this->value = $v;
			;
		}
		unset($v,$n);
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
function poko_form_elements_DateSelector_0(&$»this, &$_g, &$dtDate, &$dtHour, &$dtMin, &$dtSec, &$i, &$n, &$n_date, &$n_time, &$s, &$sb) {
if($i < 10) {
	return "0" . Std::string($i);
	;
}
else {
	return Std::string($i);
	;
}
}
function poko_form_elements_DateSelector_1(&$»this, &$_g, &$dtDate, &$dtHour, &$dtMin, &$dtSec, &$i, &$n, &$n_date, &$n_time, &$s, &$sb) {
if($i < 10) {
	return "0" . Std::string($i);
	;
}
else {
	return Std::string($i);
	;
}
}
function poko_form_elements_DateSelector_2(&$»this, &$_g, &$dtDate, &$dtHour, &$dtMin, &$dtSec, &$i, &$n, &$n_date, &$n_time, &$s, &$sb) {
if($i < 10) {
	return "0" . Std::string($i);
	;
}
else {
	return Std::string($i);
	;
}
}
function poko_form_elements_DateSelector_3(&$»this, &$dtDate, &$dtHour, &$dtMin, &$dtSec, &$n, &$n_date, &$n_time, &$s, &$sb) {
if($»this->minOffset !== null) {
	return (", minDate: '-" . $»this->minOffset) . "m'";
	;
}
else {
	return "";
	;
}
}
function poko_form_elements_DateSelector_4(&$»this, &$dtDate, &$dtHour, &$dtMin, &$dtSec, &$maxOffsetStr, &$n, &$n_date, &$n_time, &$s, &$sb) {
if($»this->maxOffset !== null) {
	return (", maxDate: '+" . $»this->maxOffset) . "m'";
	;
}
else {
	return "";
	;
}
}