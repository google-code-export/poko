<?php

class poko_views_renderers_IRenderer {
	public function __construct(){}
	public $data;
	public $template;
	public function assign($field, $value) {
		;
	}
	public function ren() {
		;
	}
	public function __call($m, $a) {
		if(isset($this->$m) && is_callable($this->$m))
			return call_user_func_array($this->$m, $a);
		else if(isset($this->»dynamics[$m]) && is_callable($this->»dynamics[$m]))
			return call_user_func_array($this->»dynamics[$m], $a);
		else
			throw new HException('Unable to call «'.$m.'»');
	}
	function __toString() { return 'poko.views.renderers.IRenderer'; }
}
