<?php

class poko_utils_mailtest_Part {
	public function __construct() {
		;
		;
		;
	}
	public $content;
	public $contentID;
	public $contentType;
	public $contentEncoding;
	public $contentDisposition;
	public $contentName;
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
	static function image($url, $cid, $type) {
		$part = new poko_utils_mailtest_Part();
		$part->content = chunk_split(base64_encode(php_io_File::getContent($url)));
		$part->contentID = $cid;
		$part->contentType = "image/" . $type;
		$part->contentEncoding = "base64";
		return $part;
		unset($part);
	}
	static function html($content) {
		$part = new poko_utils_mailtest_Part();
		$part->content = $content;
		$part->contentType = "text/html";
		return $part;
		unset($part);
	}
	static function attachment($url, $name, $type) {
		$part = new poko_utils_mailtest_Part();
		$part->content = chunk_split(base64_encode(php_io_File::getContent($url)));
		$part->contentType = "attachment/" . $type;
		$part->contentName = $name;
		$part->contentEncoding = "base64";
		$part->contentDisposition = "attachment";
		return $part;
		unset($part);
	}
	function __toString() { return 'poko.utils.mailtest.Part'; }
}
