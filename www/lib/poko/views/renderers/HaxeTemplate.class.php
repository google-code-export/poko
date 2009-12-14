<?php

class poko_views_renderers_HaxeTemplate implements poko_views_Renderable{
	public function __construct($template) {
		if( !php_Boot::$skip_constructor ) {
		$this->template = $template;
	}}
	public $template;
	public $data;
	public function render() {
		return "";
	}
	public function assign($field, $value) {
		$this->data->{$field} = $value;
	}
	public function __call($m, $a) {
		if(isset($this->$m) && is_callable($this->$m))
			return call_user_func_array($this->$m, $a);
		else if(isset($this->»dynamics[$m]) && is_callable($this->»dynamics[$m]))
			return call_user_func_array($this->»dynamics[$m], $a);
		else
			throw new HException('Unable to call «'.$m.'»');
	}
	function __toString() { return 'poko.views.renderers.HaxeTemplate'; }
}
