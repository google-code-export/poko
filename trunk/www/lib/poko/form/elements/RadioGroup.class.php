<?php

class poko_form_elements_RadioGroup extends poko_form_FormElement {
	public function __construct($name, $label, $data, $selected, $defaultValue, $verticle, $labelRight) {
		if( !php_Boot::$skip_constructor ) {
		if($labelRight === null) {
			$labelRight = true;
		}
		if($verticle === null) {
			$verticle = true;
		}
		parent::__construct();
		$this->name = $name;
		$this->label = $label;
		$this->data = $data;
		$this->value = ($selected !== null ? $selected : $defaultValue);
		$this->verticle = $verticle;
		$this->labelRight = $labelRight;
	}}
	public $data;
	public $selectMessage;
	public $labelLeft;
	public $verticle;
	public $labelRight;
	public function render() {
		$s = "";
		$n = $this->form->name . "_" . $this->name;
		$c = 0;
		if($this->data !== null) {
			$»it = $this->data->iterator();
			while($»it->hasNext()) {
			$row = $»it->next();
			{
				$radio = "<input type=\"radio\" name=\"" . $n . "\" id=\"" . $n . $c . "\" value=\"" . $row->value . "\" " . (($row->value == Std::string($this->value) ? "checked" : "")) . " />\x0A";
				$label = "<label for=\"" . $n . $c . "\" >" . $row->key . "</label>";
				$s .= ($this->labelRight ? $radio . " " . $label . " " : $label . " " . $radio . " ");
				if($this->verticle) {
					$s .= "<br />";
				}
				$c++;
				unset($radio,$label);
			}
			}
		}
		return $s;
	}
	public function toString() {
		return $this->render();
	}
	public function __call($m, $a) {
		if(isset($this->$m) && is_callable($this->$m))
			return call_user_func_array($this->$m, $a);
		else if(isset($this->»dynamics[$m]) && is_callable($this->»dynamics[$m]))
			return call_user_func_array($this->»dynamics[$m], $a);
		else
			throw new HException('Unable to call «'.$m.'»');
	}
	function __toString() { return $this->toString(); }
}
