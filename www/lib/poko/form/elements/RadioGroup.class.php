<?php

class poko_form_elements_RadioGroup extends poko_form_FormElement {
	public function __construct($name, $label, $data, $selected, $defaultValue, $vertical, $labelRight) {
		if( !php_Boot::$skip_constructor ) {
		if($labelRight === null) {
			$labelRight = true;
			;
		}
		if($vertical === null) {
			$vertical = true;
			;
		}
		parent::__construct();
		$this->name = $name;
		$this->label = $label;
		$this->data = poko_form_elements_RadioGroup_0($this, $data, $defaultValue, $label, $labelRight, $name, $selected, $vertical);
		$this->value = poko_form_elements_RadioGroup_1($this, $data, $defaultValue, $label, $labelRight, $name, $selected, $vertical);
		$this->vertical = $vertical;
		$this->labelRight = $labelRight;
		;
	}}
	public $data;
	public $selectMessage;
	public $labelLeft;
	public $labelRight;
	public $vertical;
	public function addOption($key, $value) {
		$this->data->add(_hx_anonymous(array("key" => $key, "value" => $value)));
		;
	}
	public function render() {
		$s = "";
		$n = ($this->form->name . "_") . $this->name;
		$c = 0;
		if($this->data !== null) {
			if(null == $this->data) throw new HException('null iterable');
			$»it = $this->data->iterator();
			while($»it->hasNext()) {
			$row = $»it->next();
			{
				$vClass = poko_form_elements_RadioGroup_2($this, $c, $n, $row, $s);
				$s .= ("<div class=\"radioItem" . $vClass) . "\">";
				$radio = (((((((("<input type=\"radio\" name=\"" . $n) . "\" id=\"") . $n) . $c) . "\" value=\"") . $row->key) . "\" ") . (poko_form_elements_RadioGroup_3($this, $c, $n, $row, $s, $vClass))) . " />\x0A";
				$label = (((("<label for=\"" . $n) . $c) . "\" >") . $row->value) . "</label>";
				$s .= poko_form_elements_RadioGroup_4($this, $c, $label, $n, $radio, $row, $s, $vClass);
				$s .= "</div>";
				$c++;
				unset($vClass,$radio,$label);
			}
			}
			;
		}
		return $s;
		unset($s,$n,$c);
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
function poko_form_elements_RadioGroup_0(&$»this, &$data, &$defaultValue, &$label, &$labelRight, &$name, &$selected, &$vertical) {
if($data !== null) {
	return $data;
	;
}
else {
	return new HList();
	;
}
}
function poko_form_elements_RadioGroup_1(&$»this, &$data, &$defaultValue, &$label, &$labelRight, &$name, &$selected, &$vertical) {
if($selected !== null) {
	return $selected;
	;
}
else {
	return $defaultValue;
	;
}
}
function poko_form_elements_RadioGroup_2(&$»this, &$c, &$n, &$row, &$s) {
if($»this->vertical) {
	return " radioItemVertical";
	;
}
else {
	return " radioItemHorizontal";
	;
}
}
function poko_form_elements_RadioGroup_3(&$»this, &$c, &$n, &$row, &$s, &$vClass) {
if($row->key === Std::string($»this->value)) {
	return "checked";
	;
}
else {
	return "";
	;
}
}
function poko_form_elements_RadioGroup_4(&$»this, &$c, &$label, &$n, &$radio, &$row, &$s, &$vClass) {
if($»this->labelRight) {
	return (($radio . " ") . $label) . " ";
	;
}
else {
	return (($label . " ") . $radio) . " ";
	;
}
}