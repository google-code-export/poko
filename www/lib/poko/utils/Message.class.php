<?php

class poko_utils_Message {
	public function __construct($text, $type) {
		if( !php_Boot::$skip_constructor ) {
		if($type === null) {
			$type = poko_utils_MessageType::$MESSAGE;
		}
		$this->text = $text;
		$this->type = $type;
	}}
	public $text;
	public $type;
	public function toString() {
		return ($this->text);
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
