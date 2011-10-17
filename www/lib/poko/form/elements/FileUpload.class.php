<?php

class poko_form_elements_FileUpload extends poko_form_FormElement {
	public function __construct($name, $label, $value, $required, $toFolder, $keepFullFileName) {
		if( !php_Boot::$skip_constructor ) {
		if($keepFullFileName === null) {
			$keepFullFileName = true;
			;
		}
		if($required === null) {
			$required = false;
			;
		}
		parent::__construct();
		$this->name = $name;
		$this->label = $label;
		$this->value = $value;
		$this->required = $required;
		$this->toFolder = poko_form_elements_FileUpload_0($this, $keepFullFileName, $label, $name, $required, $toFolder, $value);
		$this->keepFullFileName = $keepFullFileName;
		;
	}}
	public $toFolder;
	public $keepFullFileName;
	public function populate() {
		$n = ($this->form->name . "_") . $this->name;
		$previous = poko_Poko::$instance->params->get($n . "__previous");
		$file = poko_utils_PhpTools::getFilesInfo()->get($n);
		if($file !== null && _hx_equal($file->get("error"), 0)) {
			if(file_exists($file->get("tmp_name"))) {
				$oldfile = poko_form_elements_FileUpload_1($this, $file, $n, $previous);
				if($previous !== null && !_hx_equal($previous, "") && file_exists($oldfile)) {
					@unlink($oldfile);
					;
				}
				$newname = haxe_Md5::encode(haxe_Timer::stamp() + $file->get("name")) . $file->get("name");
				poko_utils_PhpTools::moveFile($file->get("tmp_name"), $this->toFolder . $newname);
				$this->value = poko_form_elements_FileUpload_2($this, $file, $n, $newname, $oldfile, $previous);
				unset($oldfile,$newname);
			}
			;
		}
		else {
			if($previous !== null) {
				$this->value = $previous;
				;
			}
			;
		}
		unset($previous,$n,$file);
	}
	public function render() {
		$n = ($this->form->name . "_") . $this->name;
		$path = _hx_substr($this->toFolder, strlen((poko_Poko::$instance->config->applicationPath . "res/")), null);
		$str = "";
		$str .= ("<span class=\"fileName\">" . $this->getOriginalFileName()) . "</span><br/>";
		$str .= ((((("<input type=\"file\" name=\"" . $n) . "\" id=\"") . $n) . "\" ") . $this->attributes) . " />";
		$str .= ((((("<input type=\"hidden\" name=\"" . $n) . "__previous\" id=\"") . $n) . "__previous\" value=\"") . $this->value) . "\"/>";
		return $str;
		unset($str,$path,$n);
	}
	public function getFileName() {
		if($this->keepFullFileName) {
			$s = Std::string($this->value);
			return _hx_substr($s, _hx_last_index_of($s, "/", null) + 1, null);
			unset($s);
		}
		else {
			return $this->value;
			;
		}
		;
	}
	public function getOriginalFileName() {
		if($this->keepFullFileName) {
			$s = Std::string($this->value);
			return _hx_substr($s, _hx_last_index_of($s, "/", null) + 33, null);
			unset($s);
		}
		else {
			return _hx_substr(Std::string($this->value), 33, null);
			;
		}
		;
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
function poko_form_elements_FileUpload_0(&$»this, &$keepFullFileName, &$label, &$name, &$required, &$toFolder, &$value) {
if($toFolder !== null) {
	return $toFolder;
	;
}
else {
	return poko_Poko::$instance->config->applicationPath . "res/temp/";
	;
}
}
function poko_form_elements_FileUpload_1(&$»this, &$file, &$n, &$previous) {
if($»this->keepFullFileName) {
	return $previous;
	;
}
else {
	return $»this->toFolder . $previous;
	;
}
}
function poko_form_elements_FileUpload_2(&$»this, &$file, &$n, &$newname, &$oldfile, &$previous) {
if($»this->keepFullFileName) {
	return $»this->toFolder . $newname;
	;
}
else {
	return $newname;
	;
}
}