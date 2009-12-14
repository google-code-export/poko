<?php

class poko_views_renderers_Php implements poko_views_Renderable{
	public function __construct($template) {
		if( !php_Boot::$skip_constructor ) {
		$this->template = $template;
		$this->data = _hx_anonymous(array());
	}}
	public $template;
	public $data;
	public function render() {
		$tpl = "./tpl/php/" . $this->template;
		if(file_exists($tpl)) {
			ob_start();
			extract((array)$this->data);
			include($tpl);
			$out = ob_get_contents();
			ob_end_clean();
			return $out;
		}
		else {
			throw new HException("Php Template is missing: " . $this->template);
			return null;
		}
		return "";
	}
	public function assign($field, $value) {
		$this->data->{$field} = $value;
	}
	public function toString() {
		return "[PhpView " . $this->template . "]";
	}
	public function __call($m, $a) {
		if(isset($this->$m) && is_callable($this->$m))
			return call_user_func_array($this->$m, $a);
		else if(isset($this->»dynamics[$m]) && is_callable($this->»dynamics[$m]))
			return call_user_func_array($this->»dynamics[$m], $a);
		else
			throw new HException('Unable to call «'.$m.'»');
	}
	static function parse($template, $data) {
		$renderer = new poko_views_renderers_Php($template);
		$renderer->data = $data;
		return $renderer->render();
	}
	function __toString() { return $this->toString(); }
}
