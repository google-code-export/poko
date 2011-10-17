<?php

class site_cms_modules_base_formElements_LinkTable extends poko_form_FormElement {
	public function __construct($name, $label, $linkTable, $linkTo, $linkValue, $validatorsKey, $validatorsValue, $attibutes) {
		if( !php_Boot::$skip_constructor ) {
		if($attibutes === null) {
			$attibutes = "";
			;
		}
		parent::__construct();
		$this->name = $name;
		$this->label = $label;
		$this->attributes = $attibutes;
		$this->linkTable = $linkTable;
		$this->linkTo = $linkTo;
		$this->linkValue = $linkValue;
		;
	}}
	public $linkTable;
	public $linkTo;
	public $linkValue;
	public function render() {
		$str = "";
		$linkDefId = site_cms_common_Definition::tableToDefinitionId($this->linkTable);
		$linkToField = null;
		$linkValueField = null;
		$linkdef = new site_cms_common_Definition($linkDefId);
		{
			$_g = 0; $_g1 = $linkdef->elements;
			while($_g < $_g1->length) {
				$el = $_g1[$_g];
				++$_g;
				if($el->type === "link-to") {
					$linkToField = $el->name;
					break;
					;
				}
				unset($el);
			}
			unset($_g1,$_g);
		}
		{
			$_g = 0; $_g1 = $linkdef->elements;
			while($_g < $_g1->length) {
				$el = $_g1[$_g];
				++$_g;
				if($el->type === "link-value") {
					$linkValueField = $el->name;
					break;
					;
				}
				unset($el);
			}
			unset($_g1,$_g);
		}
		if($this->linkValue === null) {
			$str .= "Please edit link tables after adding item.";
			;
		}
		else {
			if($linkValueField === null) {
				if($linkValueField === null) {
					$str .= "Could not find a 'link-value' in dataset definition<br/>";
					;
				}
				;
			}
			else {
				$url = ("?request=cms.modules.base.Dataset&dataset=" . $linkDefId) . "&linkMode=true";
				$url .= "&linkToField=" . $linkToField;
				$url .= "&linkTo=" . $this->linkTo;
				$url .= "&linkValueField=" . $linkValueField;
				$url .= "&linkValue=" . $this->linkValue;
				$str .= ((((("<iframe id=" . $this->name) . " width=\"100%\" height=\"400\" src=\"") . $url) . "\" ") . $this->attributes) . " >";
				$str .= $this->name;
				$str .= "</iframe>";
				unset($url);
			}
			;
		}
		return $str;
		unset($str,$linkdef,$linkValueField,$linkToField,$linkDefId);
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
