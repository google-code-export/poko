<?php

class poko_form_elements_RichtextWym extends poko_form_FormElement {
	public function __construct($name, $label, $value, $required, $attibutes) {
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
		$this->value = $value;
		$this->required = $required;
		$this->attributes = $attibutes;
		$this->width = 500;
		$this->height = 300;
		$this->allowImages = true;
		$this->allowTables = false;
		$this->editorStyles = "";
		$this->containersItems = "";
		$this->classesItems = "";
		;
	}}
	public $width;
	public $height;
	public $allowImages;
	public $allowTables;
	public $editorStyles;
	public $containersItems;
	public $classesItems;
	public function render() {
		$n = ($this->form->name . "_") . $this->name;
		$this->editorStyles = str_replace("\x0A", " ", $this->editorStyles);
		$this->editorStyles = str_replace("\x0D", " ", $this->editorStyles);
		$str = new StringBuf();
		$str->b .= ((((("\x0A <textarea name=\"" . $n) . "\" id=\"") . $n) . "\" >") . $this->value) . "</textarea>";
		$str->b .= "<script type=\"text/javascript\">";
		$str->b .= "jQuery(function() {";
		$str->b .= ("\x09jQuery('#" . $n) . "').wymeditor({";
		$str->b .= "logoHtml: '',";
		$str->b .= ("editorStyles: [\"" . $this->editorStyles) . "\"],";
		$str->b .= "postInit: function(wym) {";
		$str->b .= "\x09jQuery(wym._box).find(wym._options.containersSelector).removeClass('wym_dropdown').addClass('wym_panel').find('h2 > span').remove();";
		$str->b .= ((("\x09jQuery(wym._box).find(wym._options.iframeSelector).css('height', '" . $this->height) . "px').css('width', '") . $this->width) . "px');";
		$str->b .= "},";
		$str->b .= "toolsItems: [";
		$str->b .= "\x09{'name': 'Bold', 'title': 'Strong', 'css': 'wym_tools_strong'}, ";
		$str->b .= "\x09{'name': 'Italic', 'title': 'Emphasis', 'css': 'wym_tools_emphasis'},";
		$str->b .= "\x09{'name': 'CreateLink', 'title': 'Link', 'css': 'wym_tools_link'},";
		$str->b .= "\x09{'name': 'Unlink', 'title': 'Unlink', 'css': 'wym_tools_unlink'},";
		if($this->allowImages) {
			$str->b .= "{'name': 'InsertImage', 'title': 'Image', 'css': 'wym_tools_image'},";
			;
		}
		$str->b .= "\x09{'name': 'InsertOrderedList', 'title': 'Ordered_List', 'css': 'wym_tools_ordered_list'},";
		$str->b .= "\x09{'name': 'InsertUnorderedList', 'title': 'Unordered_List', 'css': 'wym_tools_unordered_list'},";
		if($this->allowTables) {
			$str->b .= "{'name': 'InsertTable', 'title': 'Table', 'css': 'wym_tools_table'},";
			;
		}
		$str->b .= "\x09{'name': 'Paste', 'title': 'Paste_From_Word', 'css': 'wym_tools_paste'},";
		$str->b .= "\x09{'name': 'Undo', 'title': 'Undo', 'css': 'wym_tools_undo'},";
		$str->b .= "\x09{'name': 'Redo', 'title': 'Redo', 'css': 'wym_tools_redo'},";
		$str->b .= "\x09{'name': 'ToggleHtml', 'title': 'HTML', 'css': 'wym_tools_html'}";
		$str->b .= "],";
		$str->b .= ("containersItems: [" . $this->containersItems) . "],";
		if($this->classesItems !== "") {
			$str->b .= ("classesItems: [" . $this->classesItems) . "],";
			;
		}
		else {
			$str->b .= "classesHtml: '',";
			;
		}
		$str->b .= "postInitDialog: function (wym, wdw) { if(wymeditor_filebrowser != null) wymeditor_filebrowser(wym, wdw); }";
		$str->b .= "\x09});";
		$str->b .= "});";
		$str->b .= "</script>";
		if(!$this->isValid()) {
			$str->b .= " required";
			;
		}
		return $str->b;
		unset($str,$n);
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
