<?php

class poko_controllers_HtmlHeader {
	public function __construct() {
		if( !php_Boot::$skip_constructor ) {
		$this->title = $this->description = $this->meta = $this->keywords = $this->publisher = $this->date = "";
		$this->js = new HList();
		$this->css = new HList();
		$this->cssPrint = new HList();
		$this->cssIe6 = new HList();
		$this->cssIe7 = new HList();
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
		$»it = $this->js->iterator();
		while($»it->hasNext()) {
		$jsItem = $»it->next();
		$str .= "<script type=\"text/javascript\" src=\"" . $jsItem . "\" ></script> \x0A";
		}
		$jsBindings = _hx_deref(eval("if(isset(\$this)) \$»this =& \$this;\$tmp = poko_Poko::\$instance->controller;
			\$»r = (Std::is(\$tmp, _hx_qtype(\"poko.controllers.HtmlController\")) ? \$tmp : eval(\"if(isset(\\\$this)) \\\$»this =& \\\$this;throw new HException(\\\"Class cast error\\\");
				return \\\$»r2;
			\"));
			return \$»r;
		"))->jsBindings;
		$»it2 = $jsBindings->keys();
		while($»it2->hasNext()) {
		$jsBinding = $»it2->next();
		$str .= "<script> poko.js.JsPoko.instance.addRequest(\"" . $jsBinding . "\") </script> \x0A";
		}
		return $str;
	}
	public function getJsCalls() {
		$str = "";
		$jsCalls = _hx_deref(eval("if(isset(\$this)) \$»this =& \$this;\$tmp = poko_Poko::\$instance->controller;
			\$»r = (Std::is(\$tmp, _hx_qtype(\"poko.controllers.HtmlController\")) ? \$tmp : eval(\"if(isset(\\\$this)) \\\$»this =& \\\$this;throw new HException(\\\"Class cast error\\\");
				return \\\$»r2;
			\"));
			return \$»r;
		"))->jsCalls;
		$»it = $jsCalls->iterator();
		while($»it->hasNext()) {
		$jsCall = $»it->next();
		$str .= "<script> " . $jsCall . " </script> \x0A";
		}
		return $str;
	}
	public function getCssIe6() {
		$str = "<!--[if lte IE 6]>";
		$»it = $this->cssIe6->iterator();
		while($»it->hasNext()) {
		$cssItem = $»it->next();
		$str .= "<script type=\"text/javascript\" src=\"" . $cssItem . "\" ></script> \x0A";
		}
		$str .= "<![endif]-->";
		return $str;
	}
	public function getCssIe7() {
		$str = "<!--[if IE 7]>";
		$»it = $this->cssIe7->iterator();
		while($»it->hasNext()) {
		$cssItem = $»it->next();
		$str .= "<script type=\"text/javascript\" src=\"" . $cssItem . "\" ></script> \x0A";
		}
		$str .= "<![endif]-->";
		return $str;
	}
	public function getCss() {
		$str = "";
		$»it = $this->css->iterator();
		while($»it->hasNext()) {
		$cssItem = $»it->next();
		$str .= "<link rel=\"stylesheet\" href=\"" . $cssItem . "\" type=\"text/css\" /> \x0A";
		}
		return $str;
	}
	public function getCssPrint() {
		$str = "";
		$»it = $this->cssPrint->iterator();
		while($»it->hasNext()) {
		$cssItem = $»it->next();
		$str .= "<link rel=\"stylesheet\" href=\"" . $cssItem . "\" type=\"text/css\" media=\"print\" /> \x0A";
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
	function __toString() { return 'poko.controllers.HtmlHeader'; }
}
