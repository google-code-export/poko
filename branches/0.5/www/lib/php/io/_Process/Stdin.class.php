<?php

class php_io__Process_Stdin extends haxe_io_Output {
	public function __construct($p) {
		if( !php_Boot::$skip_constructor ) {
		$this->p = $p;
		$this->buf = haxe_io_Bytes::alloc(1);
	}}
	public $p;
	public $buf;
	public function close() {
		parent::close();
		fclose($this->p);
	}
	public function writeByte($c) {
		$this->buf->b[0] = chr($c);
		$this->writeBytes($this->buf, 0, 1);
	}
	public function writeBytes($b, $pos, $l) {
		$s = $b->readString($pos, $l);
		if(feof($this->p)) {
			return eval("if(isset(\$this)) \$»this =& \$this;throw new HException(new haxe_io_Eof());
				return \$»r;
			");
		}
		$r = fwrite($this->p, $s, $l);
		if($r === false) {
			return eval("if(isset(\$this)) \$»this =& \$this;throw new HException(haxe_io_Error::Custom(\"An error occurred\"));
				return \$»r2;
			");
		}
		return $r;
	}
	public function __call($m, $a) {
		if(isset($this->$m) && is_callable($this->$m))
			return call_user_func_array($this->$m, $a);
		else if(isset($this->»dynamics[$m]) && is_callable($this->»dynamics[$m]))
			return call_user_func_array($this->»dynamics[$m], $a);
		else
			throw new HException('Unable to call «'.$m.'»');
	}
	function __toString() { return 'php.io._Process.Stdin'; }
}
