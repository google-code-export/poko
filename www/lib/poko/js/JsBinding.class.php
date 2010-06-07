<?php

class poko_js_JsBinding {
	public function __construct($jsRequest) {
		if( !php_Boot::$skip_constructor ) {
		poko_Application::$instance->request->jsBindings->set($jsRequest, $this);
		$this->jsRequest = $jsRequest;
	}}
	public $jsRequest;
	public function getCall($method, $args) {
		$str = $this . ".call('" . $method . "', ";
		$str .= "'" . haxe_Serializer::run($args) . "'";
		$str .= ")";
		return $str;
	}
	public function queueCall($method, $args, $afterPageLoad) {
		if($afterPageLoad === null) {
			$afterPageLoad = true;
		}
		$call = $this->getCall($method, $args);
		if($afterPageLoad) {
			poko_Application::$instance->request->jsCalls->add($call);
		}
		else {
			poko_Application::$instance->request->jsCalls->add($call);
		}
	}
	public function getRawCall($method) {
		return $this . "." . $method;
	}
	public function toString() {
		return "poko.js.JsApplication.instance.resolveRequest('" . $this->jsRequest . "')";
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
