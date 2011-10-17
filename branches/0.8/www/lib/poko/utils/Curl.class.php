<?php

class poko_utils_Curl {
	public function __construct(){}
	static $OPTION_RETURN_TRANSFER = "CURLOPT_RETURNTRANSFER";
	static $OPTION_URL = "CURLOPT_URL";
	static $OPTION_POST = "CURLOPT_POST";
	static $OPTION_POST_FIELDS = "CURLOPT_POSTFIELDS";
	static $OPTION_SSL_VERIFY_PEER = "CURLOPT_SSL_VERIFYPEER";
	static $OPTION_SLL_VERIFY_HOST = "CURLOPT_SSL_VERIFYHOST";
	static $OPTION_USER_AGENT = "CURLOPT_USERAGENT";
	static $OPTION_CERTIFICATE_AUTHORITY_BUNDLE_FILE_LOCATION = "CURLOPT_CAINFO";
	static $INFO_HTTP_CODE = "CURLINFO_HTTP_CODE";
	static function init($url) {
		return curl_init($url);
		;
	}
	static function close($resource) {
		return curl_close($resource);
		;
	}
	static function execToOutput($resource) {
		return curl_exec($resource);
		;
	}
	static function execToVariable($resource) {
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
		unset($val);
	}
	static function setOption($resource, $option, $value) {
		return curl_setopt($resource, call_user_func_array($__php__, array($option)), $value);
		;
	}
	static function getInfo($resource, $option) {
		return curl_getinfo($resource, call_user_func_array($__php__, array($option)));
		;
	}
	static function getLastError($resource) {
		return curl_error($resource);
		;
	}
	function __toString() { return 'poko.utils.Curl'; }
}
