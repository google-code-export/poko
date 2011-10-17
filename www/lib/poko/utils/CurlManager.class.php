<?php

class poko_utils_CurlManager {
	public function __construct($url) {
		if( !php_Boot::$skip_constructor ) {
		if($url !== null) {
			$this->resource = curl_init(null);
			$this->setUrl($url);
			;
		}
		else {
			$this->resource = curl_init(null);
			;
		}
		$this->setUserAgent("haXe CurlManager");
		;
	}}
	public $resource;
	public $currentUrl;
	public $userAgent;
	public $lastRequest;
	public function setUrl($url) {
		$this->currentUrl = $url;
		curl_setopt($this->resource, CURLOPT_URL, $url);
		if(_hx_substr($url, 0, 5) === "https") {
			curl_setopt($this->resource, CURLOPT_SSL_VERIFYPEER, true);
			curl_setopt($this->resource, CURLOPT_SSL_VERIFYHOST, true);
			if($_SERVER['SERVER_NAME'] === "localhost") {
				curl_setopt($this->resource, CURLOPT_CAINFO, "c:\\xampp\\ca-bundle.crt");
				;
			}
			;
		}
		;
	}
	public function setUserAgent($agent) {
		$this->userAgent = $agent;
		curl_setopt($this->resource, CURLOPT_USERAGENT, $this->userAgent);
		;
	}
	public function exists($accept400AsOk, $url) {
		if($accept400AsOk === null) {
			$accept400AsOk = false;
			;
		}
		if($url === null && $this->currentUrl === null) {
			throw new HException(new Exception("No URL defined", null));
			;
		}
		$this->lastRequest = poko_utils_CurlManager_0($this, $accept400AsOk, $url);
		if($this->lastRequest !== null) {
			if(!$accept400AsOk) {
				$c = $this->httpCode();
				if($c >= 400 && $c < 500) {
					return false;
					;
				}
				unset($c);
			}
			return true;
			;
		}
		return false;
		;
	}
	public function httpCode() {
		if($this->lastRequest === null) {
			throw new HException(new Exception("No resource", null));
			;
		}
		return Std::parseInt(curl_getinfo($this->resource, CURLINFO_HTTP_CODE));
		;
	}
	public function get($url) {
		if($url === null && $this->currentUrl === null) {
			throw new HException(new Exception("No URL defined", null));
			;
		}
		$this->lastRequest = poko_utils_CurlManager_1($this, $url);
		return ($this->lastRequest);
		;
	}
	public function hprint($url) {
		if($url === null && $this->currentUrl === null) {
			throw new HException(new Exception("No URL defined", null));
			;
		}
		$this->lastRequest = null;
		return (curl_exec($this->resource));
		;
	}
	public function setPost($params) {
		$paramStr = poko_utils_CurlManager::buildPostString($params);
		curl_setopt($this->resource, CURLOPT_POST, true);
		curl_setopt($this->resource, CURLOPT_POSTFIELDS, $paramStr);
		return $paramStr;
		unset($paramStr);
	}
	public function getLastError() {
		if($this->currentUrl === null) {
			throw new HException(new Exception("No URL defined", null));
			;
		}
		return curl_error($this->resource);
		;
	}
	public function close() {
		$this->currentUrl = null;
		curl_close($this->resource);
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
	static function buildPostString($params) {
		$paramStr = "";
		{
			$_g = 0; $_g1 = Reflect::fields($params);
			while($_g < $_g1->length) {
				$param = $_g1[$_g];
				++$_g;
				if($paramStr !== "") {
					$paramStr .= "&";
					;
				}
				$paramStr .= ($param . "=") . rawurlencode(_hx_cast(Reflect::field($params, $param), _hx_qtype("String")));
				unset($param);
			}
			unset($_g1,$_g);
		}
		return $paramStr;
		unset($paramStr);
	}
	function __toString() { return 'poko.utils.CurlManager'; }
}
;
function poko_utils_CurlManager_0(&$»this, &$accept400AsOk, &$url) {
{
	$resource = $»this->resource;
	curl_setopt($resource, CURLOPT_RETURNTRANSFER, true);
	$val = curl_exec($resource);
	if($val === false) {
		return null;
		;
	}
	else {
		return $val;
		;
	}
	unset($val,$resource);
}
}
function poko_utils_CurlManager_1(&$»this, &$url) {
{
	$resource = $»this->resource;
	curl_setopt($resource, CURLOPT_RETURNTRANSFER, true);
	$val = curl_exec($resource);
	if($val === false) {
		return null;
		;
	}
	else {
		return $val;
		;
	}
	unset($val,$resource);
}
}