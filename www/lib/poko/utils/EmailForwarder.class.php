<?php

class poko_utils_EmailForwarder {
	public function __construct(){}
	static $defaultProxyURL = "http://poko.touchmypixel.com/email_forwarder.php";
	static $defaultProxyCode = "yourendofthebargain";
	static $useProxy = true;
	static $disableHtml = false;
	static $parts;
	static function addPart($part) {
		if(poko_utils_EmailForwarder::$parts === null) {
			poko_utils_EmailForwarder::$parts = new _hx_array(array());
			;
		}
		poko_utils_EmailForwarder::$parts->push($part);
		;
	}
	static function forwardEmail($to, $subject, $message, $headers, $proxyUrl, $proxyCode) {
		$proxyUrl = poko_utils_EmailForwarder_0($headers, $message, $proxyCode, $proxyUrl, $subject, $to);
		$proxyCode = poko_utils_EmailForwarder_1($headers, $message, $proxyCode, $proxyUrl, $subject, $to);
		if(poko_utils_EmailForwarder::$useProxy) {
			$curl = new poko_utils_CurlManager($proxyUrl);
			$hash = haxe_Md5::encode(poko_utils_CurlManager::buildPostString(_hx_anonymous(array("to" => $to, "subject" => $subject, "message" => $message, "headers" => $headers))) . $proxyCode);
			$paramStr = $curl->setPost(_hx_anonymous(array("to" => $to, "subject" => $subject, "message" => $message, "headers" => $headers, "hash" => $hash)));
			$response = $curl->get(null);
			return $response === "OK";
			unset($response,$paramStr,$hash,$curl);
		}
		else {
			poko_utils_PhpTools::mail($to, $subject, $message, $headers, null);
			return true;
			;
		}
		;
	}
	static function forwardMultipart($to, $subject, $from, $plainMessage, $htmlMessage, $replyTo, $proxyUrl, $proxyCode) {
		$notice_text = "This is a multi-part message in MIME format.";
		$semi_rand = haxe_Md5::encode(Std::string(haxe_Timer::stamp()));
		$mime_boundary = "==MULTIPART_BOUNDARY_" . $semi_rand;
		$mime_boundary_header = ("\"" . $mime_boundary) . "\"";
		$body = $notice_text . "\x0A\x0A";
		$body .= ("--" . $mime_boundary) . "\x0A";
		$body .= "Content-Type: text/plain; charset=us-ascii\x0A";
		$body .= "Content-Transfer-Encoding: 7bit\x0A\x0A";
		$body .= $plainMessage . "\x0A\x0A";
		if(!poko_utils_EmailForwarder::$disableHtml) {
			$body .= ("--" . $mime_boundary) . "\x0A";
			$body .= "Content-Type: text/html; charset=us-ascii\x0A";
			$body .= "Content-Transfer-Encoding: 7bit\x0A\x0A";
			$body .= $htmlMessage . "\x0A\x0A";
			;
		}
		$body .= ("--" . $mime_boundary) . "--";
		$headers = ("From: " . $from) . "\x0A";
		if($replyTo !== null && $replyTo !== "") {
			$headers .= ("Reply-To: " . $replyTo) . "\x0A";
			;
		}
		$headers .= (("MIME-Version: 1.0\x0A" . "Content-Type: multipart/alternative;\x0A") . "     boundary=") . $mime_boundary_header;
		return poko_utils_EmailForwarder::forwardEmail($to, $subject, $body, $headers, $proxyUrl, $proxyCode);
		unset($semi_rand,$notice_text,$mime_boundary_header,$mime_boundary,$headers,$body);
	}
	function __toString() { return 'poko.utils.EmailForwarder'; }
}
;
function poko_utils_EmailForwarder_0(&$headers, &$message, &$proxyCode, &$proxyUrl, &$subject, &$to) {
if($proxyUrl === null) {
	return poko_utils_EmailForwarder::$defaultProxyURL;
	;
}
else {
	return $proxyUrl;
	;
}
}
function poko_utils_EmailForwarder_1(&$headers, &$message, &$proxyCode, &$proxyUrl, &$subject, &$to) {
if($proxyCode === null) {
	return poko_utils_EmailForwarder::$defaultProxyCode;
	;
}
else {
	return $proxyCode;
	;
}
}