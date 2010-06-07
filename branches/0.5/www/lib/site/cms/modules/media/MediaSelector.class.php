<?php

class site_cms_modules_media_MediaSelector extends site_cms_templates_CmsPopup {
	public function __construct() {
		if( !php_Boot::$skip_constructor ) {
		parent::__construct();
	}}
	public $elementId;
	public $form;
	public $selector;
	public $items;
	public $gallery;
	public $jsBind;
	public $jsBindTop;
	public $from;
	public $baseUrl;
	public function main() {
		$this->elementId = $this->application->params->get("elementId");
		$this->from = site_cms_modules_media_MediaSelector::$FROM_CMS;
		if($this->application->params->get("from") !== null) {
			$this->from = $this->application->params->get("from");
		}
		$this->baseUrl = "http://" . $_SERVER['SERVER_NAME'] . php_Web::getURI();
		$this->head->css->add("css/cms/media.css");
		$this->head->css->add("css/cms/popup.css");
		$this->head->js->add("js/cms/jquery.qtip.min.js");
		if($this->from == site_cms_modules_media_MediaSelector::$FROM_TINYMCE) {
			$this->head->js->add("js/cms/tiny_mce/tiny_mce_popup.js");
			$this->head->js->add("js/cms/tiny_mce_browser.js");
		}
		$this->jsBind = new poko_js_JsBinding("site.cms.modules.media.js.JsMediaSelector");
		$this->jsBindTop = new poko_js_JsBinding("site.cms.modules.base.js.JsDatasetItem");
		$exclude = new _hx_array(array(".", "..", ".svn"));
		$imageRoot = "./res/media/galleries";
		$galleries = new HList();
		$dir = php_FileSystem::readDirectory($imageRoot);
		{
			$_g = 0;
			while($_g < $dir->length) {
				$d = $dir[$_g];
				++$_g;
				if(is_dir($imageRoot . "/" . $d) && !Lambda::has($exclude, $d, null)) {
					$galleries->add(_hx_anonymous(array("key" => $d, "value" => $d)));
				}
				unset($d);
			}
		}
		$selector = new poko_form_elements_Selectbox("galleryList", "Select Gallery", $galleries, null, null, null);
		$this->form = new poko_form_Form("form1", null, null);
		$this->form->addElement($selector, null);
		$this->form->setSubmitButton($this->form->addElement(new poko_form_elements_Button("submit", "submit", null, null), null));
		$this->form->populateElements();
		$this->gallery = $this->application->params->get("form1_galleryList");
		$this->items = new HList();
		if($this->gallery !== null && $this->gallery != "") {
			$dir1 = php_FileSystem::readDirectory($imageRoot . "/" . $this->gallery);
			{
				$_g2 = 0;
				while($_g2 < $dir1->length) {
					$d2 = $dir1[$_g2];
					++$_g2;
					if(!Lambda::has($exclude, $d2, null)) {
						$this->items->add($d2);
					}
					unset($d2);
				}
			}
		}
	}
	public function getItem($name) {
		$onClick = eval("if(isset(\$this)) \$»this =& \$this;switch(\$»this->from) {
			case site_cms_modules_media_MediaSelector::\$FROM_CMS:{
				\$»r = \"window.top.\" . \$»this->jsBindTop->getCall(\"updateItemFromMedia\", new _hx_array(array(\$»this->elementId, \$»this->gallery, \$name)));
			}break;
			case site_cms_modules_media_MediaSelector::\$FROM_TINYMCE:{
				\$»r = \"updateTinyMceWithValue('/res/media/galleries/\" . \$»this->gallery . \"/\" . \$name . \"');\";
			}break;
			case site_cms_modules_media_MediaSelector::\$FROM_WYM:{
				\$»r = \"opener.document.getElementById('filebrowser').value = '\" . \$»this->baseUrl . \"res/media/galleries/\" . \$»this->gallery . \"/\" . \$name . \"'; window.close();\";
			}break;
			default:{
				\$»r = null;
			}break;
			}
			return \$»r;
		");
		$ext = strtolower(_hx_substr($name, _hx_last_index_of($name, ".", null) + 1, null));
		$str = "";
		switch($ext) {
		case "jpg":case "gif":case "png":{
			$str .= "<img class=\"qTip\" title=\"File: " . $name . "\" src=\"?request=cms.services.Image&preset=thumb&src=../media/galleries/" . $this->gallery . "/" . $name . "\" onClick=\"" . $onClick . "\" />";
		}break;
		case "txt":{
			$str .= "<img class=\"qTip\" title=\"File: " . $name . "\" src=\"./res/cms/media/file_txt.png\" onClick=\"" . $onClick . "\" />";
		}break;
		case "pdf":{
			$str .= "<img class=\"qTip\" title=\"File: " . $name . "\" src=\"./res/cms/media/file_pdf.png\" onClick=\"" . $onClick . "\" />";
		}break;
		default:{
			$str .= "<img class=\"qTip\" title=\"File: " . $name . "\" src=\"./res/cms/media/file_misc.png\" onClick=\"" . $onClick . "\" />";
		}break;
		}
		return $str;
	}
	public function __call($m, $a) {
		if(isset($this->$m) && is_callable($this->$m))
			return call_user_func_array($this->$m, $a);
		else if(isset($this->»dynamics[$m]) && is_callable($this->»dynamics[$m]))
			return call_user_func_array($this->»dynamics[$m], $a);
		else
			throw new HException('Unable to call «'.$m.'»');
	}
	static $FROM_CMS = "cms";
	static $FROM_TINYMCE = "tinyMce";
	static $FROM_WYM = "wym";
	function __toString() { return 'site.cms.modules.media.MediaSelector'; }
}
