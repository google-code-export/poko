<?php

class poko_form_elements_Richtext extends poko_form_FormElement {
	public function __construct($name, $label, $value, $required, $attibutes) {
		if( !php_Boot::$skip_constructor ) {
		if($attibutes === null) {
			$attibutes = "";
		}
		if($required === null) {
			$required = false;
		}
		parent::__construct();
		$this->name = $name;
		$this->label = $label;
		$this->value = $value;
		$this->required = $required;
		$this->attributes = $attibutes;
		$this->width = 300;
		$this->height = 300;
		$this->content_css = "css/cms/richtext_default.css";
		$this->mode = poko_form_elements_RichtextMode::$SIMPLE;
	}}
	public $width;
	public $height;
	public $content_css;
	public $mode;
	public function render() {
		$n = $this->form->name . "_" . $this->name;
		if($this->content_css != "") {
			$this->content_css .= "?" . Date::now()->getTime();
		}
		$str = new StringBuf();
		$str->b .= "\x0A <textarea name=\"" . $n . "\" id=\"" . $n . "\" >" . $this->value . "</textarea>";
		$str->b .= "\x0A <script>\x09\x09\x09\x09\x09";
		$str->b .= "\x0A\x09tinyMCE.init({ \x09\x09\x09\x09";
		$str->b .= "\x0A\x09\x09mode : \"exact\", \x09\x09";
		$str->b .= "\x0A\x09\x09elements : \"" . $n . "\",\x09";
		$str->b .= "\x0A\x09\x09theme : \"" . (($this->mode == poko_form_elements_RichtextMode::$SIMPLE ? "simple" : "advanced")) . "\",\x09 \x09";
		$str->b .= "\x0A\x09\x09width : \"" . $this->width . "\",\x09 \x09\x09\x09";
		$str->b .= "\x0A\x09\x09height : \"" . $this->height . "\",\x09 \x09\x09";
		$str->b .= "\x0A\x09\x09content_css : \"" . $this->content_css . "\",\x09";
		$»t = ($this->mode);
		switch($»t->index) {
		case 0:
		{
			;
		}break;
		case 1:
		{
			$str->b .= "\x0A plugins : \"advlink,inlinepopups,paste\", ";
			$str->b .= "\x0A theme_advanced_buttons1 : \"bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,outdent,indent,|,link,unlink,|,pastetext,cleanup,removeformat\", ";
			$str->b .= "\x0A theme_advanced_buttons2 : \"\", ";
			$str->b .= "\x0A theme_advanced_toolbar_location : \"top\", ";
			$str->b .= "\x0A theme_advanced_statusbar_location : \"bottom\", ";
			$str->b .= "\x0A theme_advanced_resizing : true, ";
		}break;
		case 2:
		{
			$str->b .= "\x0A plugins : \"table,advlink,inlinepopups,paste\", ";
			$str->b .= "\x0A theme_advanced_buttons1 : \"bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,outdent,indent,|,link,unlink,|,pastetext,cleanup,removeformat\", ";
			$str->b .= "\x0A theme_advanced_buttons2 : \"table,tablecontrols\", ";
			$str->b .= "\x0A theme_advanced_buttons3 : \"\", ";
			$str->b .= "\x0A theme_advanced_toolbar_location : \"top\", ";
			$str->b .= "\x0A theme_advanced_statusbar_location : \"bottom\", ";
			$str->b .= "\x0A theme_advanced_resizing : true, ";
		}break;
		case 3:
		{
			$str->b .= "\x0A plugins : \"safari,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template\", ";
			$str->b .= "\x0A theme_advanced_buttons1 : \"bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect\", ";
			$str->b .= "\x0A theme_advanced_buttons2 : \"cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup\", ";
			$str->b .= "\x0A theme_advanced_buttons3 : \"tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen\", ";
			$str->b .= "\x0A theme_advanced_buttons4 : \"insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak\", ";
			$str->b .= "\x0A theme_advanced_toolbar_location : \"top\", ";
			$str->b .= "\x0A theme_advanced_toolbar_align : \"left\", ";
			$str->b .= "\x0A theme_advanced_statusbar_location : \"bottom\", ";
			$str->b .= "\x0A theme_advanced_resizing : false, ";
		}break;
		}
		$str->b .= "\x0A file_browser_callback : 'myFileBrowser', ";
		$str->b .= "\x0A\x09}); \x09\x09\x09\x09\x09\x09";
		$str->b .= "\x0A </script>\x0A\x09\x09\x09\x09\x09";
		if(!$this->isValid()) {
			$str->b .= " required";
		}
		return $str->b;
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
