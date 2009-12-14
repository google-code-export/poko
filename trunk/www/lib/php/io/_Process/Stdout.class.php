<?php

class php_io__Process_Stdout extends haxe_io_Input {
	public function __construct($p) {
		if( !php_Boot::$skip_constructor ) {
		$this->p = $p;
		$this->buf = haxe_io_Bytes::alloc(1);
	}}
	public $p;
	public $buf;
	public function readByte() {
		if($this->readBytes($this->buf, 0, 1) === 0) {
			throw new HException(haxe_io_Error::$Blocked);
		}
		return ord($this->buf->b[0]);
	}
	public function readBytes($str, $pos, $l) {
		if(feof($this->p)) {
			return eval("if(isset(\$this)) \$»this =& \$this;throw new HException(new haxe_io_Eof());
				return \$»r;
			");
		}
		$r = fread($this->p, $l);
		if($r === "") {
			return eval("if(isset(\$this)) \$»this =& \$this;throw new HException(new haxe_io_Eof());
				return \$»r2;
			");
		}
		if($r === false) {
			return eval("if(isset(\$this)) \$»this =& \$this;throw new HException(haxe_io_Error::Custom(\"An error occurred\"));
				return \$»r3;
			");
		}
		$b = haxe_io_Bytes::ofString($r);
		$str->blit($pos, $b, 0, strlen($r));
		return strlen($r);
	}
	public function __call($m, $a) {
		if(isset($this->$m) && is_callable($this->$m))
			return call_user_func_array($this->$m, $a);
		else if(isset($this->»dynamics[$m]) && is_callable($this->»dynamics[$m]))
			return call_user_func_array($this->»dynamics[$m], $a);
		else
			throw new HException('Unable to call «'.$m.'»');
	}
	function __toString() { return 'php.io._Process.Stdout'; }
}
