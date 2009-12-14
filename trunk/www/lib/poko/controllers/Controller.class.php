<?php

class poko_controllers_Controller {
	public function __construct() {
		if( !php_Boot::$skip_constructor ) {
		$this->app = poko_Poko::$instance;
		$this->app->controller = $this;
		$this->view = new poko_views_View(null, null, null, null);
		$this->view->findTemplate($this, false);
		$this->remoting = new haxe_remoting_Context();
		$this->components = new HList();
	}}
	public $app;
	public $view;
	public $remoting;
	public $components;
	public function handleRequest() {
		$this->init();
		$»it = $this->components->iterator();
		while($»it->hasNext()) {
		$comp = $»it->next();
		$comp->init();
		}
		if(!haxe_remoting_HttpConnection::handleRequest($this->remoting)) {
			$this->main();
			$»it2 = $this->components->iterator();
			while($»it2->hasNext()) {
			$comp2 = $»it2->next();
			$comp2->main();
			}
			$this->render();
		}
		$this->post();
		$»it3 = $this->components->iterator();
		while($»it3->hasNext()) {
		$comp3 = $»it3->next();
		$comp3->post();
		}
		$this->onFinal();
	}
	public function render() {
		php_Lib::hprint($this->view->render());
	}
	public function setOutput($value) {
		$this->view->setOutput($value);
	}
	public function init() {
		;
	}
	public function main() {
		;
	}
	public function post() {
		;
	}
	public function onFinal() {
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
	function __toString() { return 'poko.controllers.Controller'; }
}
