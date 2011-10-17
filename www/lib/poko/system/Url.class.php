<?php

class poko_system_Url {
	public function __construct($uri) {
		if( !php_Boot::$skip_constructor ) {
		$this->uri = $uri;
		;
	}}
	public $uri;
	public function getSegments() {
		$config = poko_Poko::$instance->config;
		$s = $this->uri;
		$s = poko_system_Url_0($this, $config, $s);
		$s = poko_system_Url_1($this, $config, $s);
		if(StringTools::startsWith($s, "/")) {
			$s = _hx_substr($s, 1, null);
			;
		}
		if(StringTools::endsWith($s, "/")) {
			$s = _hx_substr($s, 0, strlen($s) - 1);
			;
		}
		return _hx_explode("/", $s);
		unset($s,$config);
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
	function __toString() { return 'poko.system.Url'; }
}
;
function poko_system_Url_0(&$»this, &$config, &$s) {
if(StringTools::startsWith($»this->uri, $config->indexPath)) {
	return _hx_substr($»this->uri, strlen($config->indexPath), null);
	;
}
else {
	return $s;
	;
}
}
function poko_system_Url_1(&$»this, &$config, &$s) {
if(StringTools::startsWith($»this->uri, $config->indexPath . $config->indexFile)) {
	return _hx_substr($»this->uri, strlen(($config->indexPath . $config->indexFile)), null);
	;
}
else {
	return $s;
	;
}
}