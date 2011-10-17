<?php

class site_cms_modules_base_formElements_FileUpload extends poko_form_FormElement {
	public function __construct($name, $label, $value, $required) {
		if( !php_Boot::$skip_constructor ) {
		if($required === null) {
			$required = false;
			;
		}
		parent::__construct();
		$this->name = $name;
		$this->label = $label;
		$this->value = $value;
		$this->required = $required;
		$this->jsBind = new poko_js_JsBinding("site.cms.modules.base.js.JsFileUpload");
		$this->popupURL = new site_cms_components_PopupURL(null, null, null, null, null);
		$this->showLibrary = true;
		$this->showUpload = true;
		$this->libraryViewThumb = true;
		$this->libraryViewList = true;
		$this->showOnlyLibraries = new _hx_array(array());
		;
	}}
	public $jsBind;
	public $popupURL;
	public $showLibrary;
	public $showUpload;
	public $libraryViewThumb;
	public $libraryViewList;
	public $showOnlyLibraries;
	public function populate() {
		$n = ($this->form->name . "_") . $this->name;
		$file = poko_utils_PhpTools::getFilesInfo()->get($n);
		if($file !== null && (_hx_equal($file->get("error"), "0") || _hx_equal($file->get("error"), 0))) {
			$v = $file->get("name");
			if($v !== null) {
				$this->value = $v;
				;
			}
			unset($v);
		}
		unset($n,$file);
	}
	public function render() {
		$n = ($this->form->name . "_") . $this->name;
		$str = "";
		if(!_hx_equal($this->value, "")) {
			$str .= ("<div id=\"fileUploadDisplay_" . $this->name) . "\" class=\"cmsComponentFileImageDisplay\">";
			$s = ("" . $this->value) . "";
			$ext = strtolower(_hx_substr($s, _hx_last_index_of($s, ".", null) + 1, null));
			if(strlen($s) > 0) {
				switch($ext) {
				case "jpg":case "gif":case "png":{
					$str .= ((("<a target=\"_blank\" href=\"?request=cms.services.Image&src=" . $this->value) . "\" ><img src=\"./?request=cms.services.Image&preset=thumb&src=") . $this->value) . "\" /></a>";
					;
				}break;
				case "txt":{
					$str .= ("<a target=\"_blank\" href=\"./res/uploads/" . $this->value) . "\" ><img src=\"./res/cms/media/file_txt.png\" /></a>";
					;
				}break;
				case "pdf":{
					$str .= ("<a target=\"_blank\" href=\"./res/uploads/" . $this->value) . "\" ><img src=\"./res/cms/media/file_pdf.png\" /></a>";
					;
				}break;
				default:{
					$str .= ((("<a target=\"_blank\" href=\"./res/uploads/" . $this->value) . "\" ><img src=\"./res/cms/media/file_misc.png\" /><br />") . _hx_string_call($this->value, "substr", array(32))) . "</a><br />&nbsp;";
					;
				}break;
				}
				if(!$this->required) {
					$str .= (" <a href=\"#\" onclick=\"" . $this->jsBind->getCall("deleteFile", new _hx_array(array($s, "fileUploadDisplay_" . $this->name)))) . "; return(false);\"><img align=\"absmiddle\" title=\"delete\" src=\"./res/cms/delete.png\" /></a>";
					;
				}
				;
			}
			$str .= "</div>";
			unset($s,$ext);
		}
		$this->popupURL->id = $n . "_mediaSelectorPopup";
		$this->popupURL->label = "library";
		$this->popupURL->contentUrl = (("?request=cms.modules.media.MediaSelector&elementId=" . $n) . "&showOnlyLibraries=") . $this->showOnlyLibraries->join(":");
		$this->popupURL->contentUrl .= (("&libraryViewThumb=" . $this->libraryViewThumb) . "&libraryViewList=") . $this->libraryViewList;
		$this->popupURL->width = 700;
		$this->popupURL->height = 450;
		$str .= ((("<input type=\"hidden\" name=\"" . $n) . "_libraryItemValue\" id=\"") . $n) . "_libraryItemValue\" value=\"\" />";
		$str .= "<div class=\"cmsComponentFileImageEdit\">";
		if($this->showUpload) {
			$str .= ((((((((((((("<div class=\"cmsComponentFileImageEditUpload\"><input checked type=\"radio\" id=\"" . $n) . "_cmsComponentFileImageEditOperationUpload\" name=\"") . $n) . "_operation\" value=\"") . site_cms_modules_base_formElements_FileUpload::$OPERATION_UPLOAD) . "\" /> <input type=\"file\" name=\"") . $n) . "\" id=\"") . $n) . "\" ") . $this->attributes) . " onClick=\"document.getElementById('") . $n) . "_cmsComponentFileImageEditOperationUpload').checked = true;\" /></div>";
			;
		}
		if($this->showLibrary) {
			$str .= ((((((((("<div class=\"cmsComponentFileImageEditLibrary\"><input type=\"radio\" id=\"" . $n) . "_cmsComponentFileImageEditOperationLibrary\" name=\"") . $n) . "_operation\" value=\"") . site_cms_modules_base_formElements_FileUpload::$OPERATION_LIBRARY) . "\" /> ") . $this->popupURL->render()) . "<span id=\"") . $n) . "_libraryItemDisplay\" class=\"cmsComponentFileImageEditLibraryDisplay\"></span>";
			;
		}
		$str .= "</div>";
		return $str;
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
	static $OPERATION_UPLOAD = "operation_upload";
	static $OPERATION_LIBRARY = "operation_library";
	function __toString() { return $this->toString(); }
}
