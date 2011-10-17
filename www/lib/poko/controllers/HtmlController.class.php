<?php

class poko_controllers_HtmlController extends poko_controllers_Controller {
	public function __construct() {
		if( !php_Boot::$skip_constructor ) {
		parent::__construct();
		$this->head = new poko_controllers_HtmlHeader();
		$this->head->title = "haxe poko";
		$this->jsBindings = new Hash();
		$this->jsCalls = new HList();
		$this->layoutView = new poko_views_View(null, null, null, null);
		$this->layoutView->findTemplate($this, true);
		;
	}}
	public $head;
	public $jsBindings;
	public $jsCalls;
	public $layoutView;
	public function render() {
		if($this->layoutView->template !== null) {
			php_Lib::hprint($this->layoutView->render());
			;
		}
		else {
			parent::render();
			;
		}
		;
	}
	public function setOutput($value) {
		$this->layoutView->setOutput($value);
		;
	}
	public function setContentOutput($value) {
		$this->view->setOutput($value);
		;
	}
	public function nl2br($input) {
		return nl2br($input);
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
	function __toString() { return 'poko.controllers.HtmlController'; }
}
