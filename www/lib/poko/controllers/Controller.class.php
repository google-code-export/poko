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
		$s = Type::getClassName(Type::getClass($this));
		$this->identifier = _hx_substr($s, _hx_index_of($s, ".", null) + 1, null);
		unset($s);
	}}
	public $app;
	public $view;
	public $remoting;
	public $components;
	public $identifier;
	public function handleRequest() {
		$this->init();
		if(null == $this->components) throw new HException('null iterable');
		$»it = $this->components->iterator();
		while($»it->hasNext()) {
		$comp = $»it->next();
		$comp->init();
		}
		if(!haxe_remoting_HttpConnection::handleRequest($this->remoting)) {
			$this->main();
			if(null == $this->components) throw new HException('null iterable');
			$»it = $this->components->iterator();
			while($»it->hasNext()) {
			$comp = $»it->next();
			$comp->main();
			}
			$this->render();
			;
		}
		$this->post();
		if(null == $this->components) throw new HException('null iterable');
		$»it = $this->components->iterator();
		while($»it->hasNext()) {
		$comp = $»it->next();
		$comp->post();
		}
		$this->onFinal();
		;
	}
	public function parse($tpl) {
		return poko_views_Parse::template($tpl, $this);
		;
	}
	public function render() {
		if($this->view->template !== null) {
			php_Lib::hprint($this->view->render());
			;
		}
		;
	}
	public function setOutput($value) {
		$this->view->setOutput($value);
		;
	}
	public function init() {
		;
		;
	}
	public function main() {
		;
		;
	}
	public function post() {
		;
		;
	}
	public function onFinal() {
		;
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
	function __toString() { return 'poko.controllers.Controller'; }
}
