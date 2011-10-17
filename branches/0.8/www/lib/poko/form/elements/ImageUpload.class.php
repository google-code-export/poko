<?php

class poko_form_elements_ImageUpload extends poko_form_elements_FileUpload {
	public function __construct($name, $label, $value, $required, $imageServiceUrl, $imageServicePreset, $toFolder, $keepFullFileName) {
		if( !php_Boot::$skip_constructor ) {
		if($keepFullFileName === null) {
			$keepFullFileName = true;
			;
		}
		if($required === null) {
			$required = false;
			;
		}
		parent::__construct($name,$label,$value,$required,$toFolder,$keepFullFileName);
		$this->imageServiceUrl = poko_form_elements_ImageUpload_0($this, $imageServicePreset, $imageServiceUrl, $keepFullFileName, $label, $name, $required, $toFolder, $value);
		$this->imageServicePreset = poko_form_elements_ImageUpload_1($this, $imageServicePreset, $imageServiceUrl, $keepFullFileName, $label, $name, $required, $toFolder, $value);
		;
	}}
	public $imageServiceUrl;
	public $imageServicePreset;
	public function render() {
		$n = ($this->form->name . "_") . $this->name;
		$path = _hx_substr($this->toFolder, strlen((poko_Poko::$instance->config->applicationPath . "res/")), null);
		$str = "";
		$str .= (((((("<img src=\"" . $this->imageServiceUrl) . "&preset=") . $this->imageServicePreset) . "&src=") . $path) . $this->getFileName()) . "\" /><br/>";
		$str .= ((((("<input type=\"file\" name=\"" . $n) . "\" id=\"") . $n) . "\" ") . $this->attributes) . " />";
		$str .= ((((("<input type=\"hidden\" name=\"" . $n) . "__previous\" id=\"") . $n) . "__previous\" value=\"") . $this->value) . "\"/>";
		return $str;
		unset($str,$path,$n);
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
function poko_form_elements_ImageUpload_0(&$»this, &$imageServicePreset, &$imageServiceUrl, &$keepFullFileName, &$label, &$name, &$required, &$toFolder, &$value) {
if($imageServiceUrl !== null) {
	return $imageServiceUrl;
	;
}
else {
	return "?request=services.Image";
	;
}
}
function poko_form_elements_ImageUpload_1(&$»this, &$imageServicePreset, &$imageServiceUrl, &$keepFullFileName, &$label, &$name, &$required, &$toFolder, &$value) {
if($imageServicePreset !== null) {
	return $imageServicePreset;
	;
}
else {
	return "thumb";
	;
}
}