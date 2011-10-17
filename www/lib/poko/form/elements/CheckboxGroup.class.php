<?php

class poko_form_elements_CheckboxGroup extends poko_form_FormElement {
	public function __construct($name, $label, $data, $selected, $verticle, $labelRight) {
		if( !php_Boot::$skip_constructor ) {
		if($labelRight === null) {
			$labelRight = true;
			;
		}
		if($verticle === null) {
			$verticle = true;
			;
		}
		parent::__construct();
		$this->name = $name;
		$this->label = $label;
		$this->data = $data;
		$this->value = poko_form_elements_CheckboxGroup_0($this, $data, $label, $labelRight, $name, $selected, $verticle);
		$this->verticle = $verticle;
		$this->labelRight = $labelRight;
		$this->columns = 1;
		;
	}}
	public $data;
	public $selectMessage;
	public $labelLeft;
	public $verticle;
	public $labelRight;
	public $formatter;
	public $columns;
	public function populate() {
		$v = php_Web::getParamValues(($this->form->name . "_") . $this->name);
		if($this->form->isSubmitted()) {
			$this->value = poko_form_elements_CheckboxGroup_1($this, $v);
			;
		}
		else {
			if($v !== null) {
				$this->value = $v;
				;
			}
			;
		}
		unset($v);
	}
	public function render() {
		$s = "";
		$n = ($this->form->name . "_") . $this->name;
		if(_hx_field($this, "value") !== null) {
			$this->value = Lambda::map($this->value, array(new _hx_lambda(array(&$n, &$s), "poko_form_elements_CheckboxGroup_2"), 'execute'));
			;
		}
		$tagCss = $this->getClasses();
		$labelCss = $this->getLabelClasses();
		$c = 0;
		$array = Lambda::harray($this->data);
		if($array !== null) {
			$rowsPerColumn = Math::ceil($array->length / $this->columns);
			$s = "<table><tr>";
			{
				$_g1 = 0; $_g = $this->columns;
				while($_g1 < $_g) {
					$i = $_g1++;
					$s .= "<td valign=\"top\">\x0A";
					$s .= "<table>\x0A";
					{
						$_g2 = 0;
						while($_g2 < $rowsPerColumn) {
							$j = $_g2++;
							if($c >= $array->length) {
								break;
								;
							}
							$s .= "<tr>";
							$row = $array[$c];
							$checkbox = (((((((((("<input type=\"checkbox\" class=\"" . $tagCss) . "\" name=\"") . $n) . "[]\" id=\"") . $n) . $c) . "\" value=\"") . $row->key) . "\" ") . (poko_form_elements_CheckboxGroup_3($this, $_g, $_g1, $_g2, $array, $c, $i, $j, $labelCss, $n, $row, $rowsPerColumn, $s, $tagCss))) . " ></input>\x0A";
							$label = null;
							if($this->formatter !== null) {
								$label = (((((("<label for=\"" . $n) . $c) . "\" class=\"") . $labelCss) . "\" >") . $this->formatter->format($row->value)) . "</label>\x0A";
								;
							}
							else {
								$label = (((((("<label for=\"" . $n) . $c) . "\" class=\"") . $labelCss) . "\" >") . $row->value) . "</label>\x0A";
								;
							}
							if($this->labelRight) {
								$s .= ("<td>" . $checkbox) . "</td>\x0A";
								$s .= ("<td>" . $label) . "</td>\x0A";
								;
							}
							else {
								$s .= ("<td>" . $label) . "</td>\x0A";
								$s .= ("<td>" . $checkbox) . "</td>\x0A";
								;
							}
							$s .= "</tr>";
							$c++;
							unset($row,$label,$j,$checkbox);
						}
						unset($_g2);
					}
					$s .= "</table>";
					$s .= "</td>";
					unset($i);
				}
				unset($_g1,$_g);
			}
			$s .= "</tr></table>\x0A";
			unset($rowsPerColumn);
		}
		return $s;
		unset($tagCss,$s,$n,$labelCss,$c,$array);
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
function poko_form_elements_CheckboxGroup_0(&$»this, &$data, &$label, &$labelRight, &$name, &$selected, &$verticle) {
if($selected !== null) {
	return $selected;
	;
}
else {
	return new _hx_array(array());
	;
}
}
function poko_form_elements_CheckboxGroup_1(&$»this, &$v) {
if($v !== null) {
	return $v;
	;
}
else {
	return new _hx_array(array());
	;
}
}
function poko_form_elements_CheckboxGroup_2(&$n, &$s, $item) {
{
	return $item . "";
	;
}
}
function poko_form_elements_CheckboxGroup_3(&$»this, &$_g, &$_g1, &$_g2, &$array, &$c, &$i, &$j, &$labelCss, &$n, &$row, &$rowsPerColumn, &$s, &$tagCss) {
if(_hx_field($»this, "value") !== null) {
	if(Lambda::has($»this->value, $row->key . "", null)) {
		return "checked";
		;
	}
	else {
		return "";
		;
	}
	;
}
else {
	return "";
	;
}
}