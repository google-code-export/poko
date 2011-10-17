<?php

class poko_js_JsBinding {
	public function __construct($jsRequest) {
		if( !php_Boot::$skip_constructor ) {
		_hx_cast(poko_Poko::$instance->controller, _hx_qtype("poko.controllers.HtmlController"))->jsBindings->set($jsRequest, $this);
		$this->jsRequest = $jsRequest;
		;
	}}
	public $jsRequest;
	public function getCall($method, $args) {
		$str = (($this . ".call('") . $method) . "', ";
		$str .= ("'" . haxe_Serializer::run($args)) . "'";
		$str .= ")";
		return $str;
		unset($str);
	}
	public function queueCall($method, $args, $afterPageLoad) {
		if($afterPageLoad === null) {
			$afterPageLoad = true;
			;
		}
		$controller = poko_Poko::$instance->controller;
		$call = $this->getCall($method, $args);
		if($afterPageLoad) {
			$controller->jsCalls->add($call);
			;
		}
		else {
			$controller->jsCalls->add($call);
			;
		}
		unset($controller,$call);
	}
	public function queueRawCall($method) {
		$controller = poko_Poko::$instance->controller;
		$controller->jsCalls->add(($this . ".") . $method);
		unset($controller);
	}
	public function getRawCall($method) {
		return ($this . ".") . $method;
		;
	}
	public function toString() {
		return ("poko.js.JsPoko.instance.resolveRequest('" . $this->jsRequest) . "')";
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
	function __toString() { return $this->toString(); }
}
