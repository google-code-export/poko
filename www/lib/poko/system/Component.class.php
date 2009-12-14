<?php

class poko_system_Component implements poko_views_Renderable{
	public function __construct() {
		if( !php_Boot::$skip_constructor ) {
		$this->app = poko_Poko::$instance;
		$this->app->controller->components->add($this);
		$this->view = new poko_views_View($this, null, null, null);
		$this->view->findTemplate($this, null);
	}}
	public $app;
	public $view;
	public function init() {
		;
	}
	public function main() {
		;
	}
	public function post() {
		;
	}
	public function setOutput($value) {
		$this->view->setOutput($value);
	}
	public function render() {
		return $this->view->render();
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
