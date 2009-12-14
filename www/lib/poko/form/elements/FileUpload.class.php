<?php

class poko_form_elements_FileUpload extends poko_form_FormElement {
	public function __construct($name, $label, $value, $required) { if( !php_Boot::$skip_constructor ) {
		if($required === null) {
			$required = false;
		}
		parent::__construct();
		$this->name = $name;
		$this->label = $label;
		$this->value = $value;
		$this->required = $required;
	}}
	public function populate() {
		$n = $this->form->name . "_" . $this->name;
		$file = poko_utils_PhpTools::getFilesInfo()->get($n);
		if($file !== null && $file->get("error") == "0") {
			$v = $file->get("name");
			if($v !== null) {
				$this->value = $v;
			}
		}
	}
	public function render() {
		$n = $this->form->name . "_" . $this->name;
		$str = "";
		$str .= "<input type=\"file\" name=\"" . $n . "\" id=\"" . $n . "\" " . $this->attributes . " />";
		return $str;
	}
	public function toString() {
		return $this->render();
	}
	function __toString() { return $this->toString(); }
}
