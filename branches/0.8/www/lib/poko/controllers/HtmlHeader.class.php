<?php

class poko_controllers_HtmlHeader extends poko_utils_html_ScriptList {
	public function __construct() {
		if( !php_Boot::$skip_constructor ) {
		parent::__construct();
		$this->title = $this->description = $this->meta = $this->keywords = $this->publisher = $this->date = "";
		$this->js = new HList();
		$this->css = new HList();
		$this->cssPrint = new HList();
		$this->cssIe6 = new HList();
		$this->cssIe7 = new HList();
		;
	}}
	public $title;
	public $description;
	public $meta;
	public $keywords;
	public $publisher;
	public $date;
	public $favicon;
	public $author;
	public $js;
	public $css;
	public $cssPrint;
	public $cssIe6;
	public $cssIe7;
	public function getJs() {
		$str = "";
		if(null == $this->js) throw new HException('null iterable');
		$»it = $this->js->iterator();
		while($»it->hasNext()) {
		$jsItem = $»it->next();
		$str .= ("<script type=\"text/javascript\" src=\"" . $jsItem) . "\" ></script> \x0A";
		}
		$jsBindings = _hx_cast(poko_Poko::$instance->controller, _hx_qtype("poko.controllers.HtmlController"))->jsBindings;
		if(null == $jsBindings) throw new HException('null iterable');
		$»it = $jsBindings->keys();
		while($»it->hasNext()) {
		$jsBinding = $»it->next();
		$str .= ("<script> poko.js.JsPoko.instance.addRequest(\"" . $jsBinding) . "\") </script> \x0A";
		}
		return $str;
		unset($str,$jsBindings);
	}
	public function getJsCalls() {
		$str = "";
		$jsCalls = _hx_cast(poko_Poko::$instance->controller, _hx_qtype("poko.controllers.HtmlController"))->jsCalls;
		if(null == $jsCalls) throw new HException('null iterable');
		$»it = $jsCalls->iterator();
		while($»it->hasNext()) {
		$jsCall = $»it->next();
		$str .= ("<script> " . $jsCall) . " </script> \x0A";
		}
		return $str;
		unset($str,$jsCalls);
	}
	public function getCssIe6() {
		$str = "<!--[if lte IE 6]>";
		if(null == $this->cssIe6) throw new HException('null iterable');
		$»it = $this->cssIe6->iterator();
		while($»it->hasNext()) {
		$cssItem = $»it->next();
		$str .= ("<script type=\"text/javascript\" src=\"" . $cssItem) . "\" ></script> \x0A";
		}
		$str .= "<![endif]-->";
		return $str;
		unset($str);
	}
	public function getCssIe7() {
		$str = "<!--[if IE 7]>";
		if(null == $this->cssIe7) throw new HException('null iterable');
		$»it = $this->cssIe7->iterator();
		while($»it->hasNext()) {
		$cssItem = $»it->next();
		$str .= ("<script type=\"text/javascript\" src=\"" . $cssItem) . "\" ></script> \x0A";
		}
		$str .= "<![endif]-->";
		return $str;
		unset($str);
	}
	public function getCss() {
		$str = "";
		if(null == $this->css) throw new HException('null iterable');
		$»it = $this->css->iterator();
		while($»it->hasNext()) {
		$cssItem = $»it->next();
		$str .= ("<link rel=\"stylesheet\" href=\"" . $cssItem) . "\" type=\"text/css\" /> \x0A";
		}
		return $str;
		unset($str);
	}
	public function getCssPrint() {
		$str = "";
		if(null == $this->cssPrint) throw new HException('null iterable');
		$»it = $this->cssPrint->iterator();
		while($»it->hasNext()) {
		$cssItem = $»it->next();
		$str .= ("<link rel=\"stylesheet\" href=\"" . $cssItem) . "\" type=\"text/css\" media=\"print\" /> \x0A";
		}
		return $str;
		unset($str);
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
	function __toString() { return 'poko.controllers.HtmlHeader'; }
}
