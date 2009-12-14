<?php

class poko_system_Url {
	public function __construct($uri) {
		if( !php_Boot::$skip_constructor ) {
		$this->uri = $uri;
	}}
	public $uri;
	public function getSegments() {
		$config = poko_Poko::$instance->config;
		$s = $this->uri;
		$s = (StringTools::startsWith($this->uri, $config->indexPath) ? _hx_substr($this->uri, strlen($config->indexPath), null) : $s);
		$s = (StringTools::startsWith($this->uri, $config->indexPath . $config->indexFile) ? _hx_substr($this->uri, strlen(($config->indexPath . $config->indexFile)), null) : $s);
		if(StringTools::startsWith($s, "/")) {
			$s = _hx_substr($s, 1, null);
		}
		if(StringTools::endsWith($s, "/")) {
			$s = _hx_substr($s, 0, strlen($s) - 1);
		}
		return _hx_explode("/", $s);
	}
	public function __call($m, $a) {
		if(isset($this->$m) && is_callable($this->$m))
			return call_user_func_array($this->$m, $a);
		else if(isset($this->»dynamics[$m]) && is_callable($this->»dynamics[$m]))
			return call_user_func_array($this->»dynamics[$m], $a);
		else
			throw new HException('Unable to call «'.$m.'»');
	}
	function __toString() { return 'poko.system.Url'; }
}
