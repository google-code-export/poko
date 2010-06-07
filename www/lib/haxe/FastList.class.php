<?php

class haxe_FastList {
	public function __construct() {
		;
		;
	}
	public $head;
	public function add($item) {
		$this->head = new haxe_FastCell($item, $this->head);
	}
	public function first() {
		return ($this->head === null ? null : $this->head->elt);
	}
	public function pop() {
		$k = $this->head;
		if($k === null) {
			return null;
		}
		else {
			$this->head = $k->next;
			return $k->elt;
		}
	}
	public function isEmpty() {
		return ($this->head === null);
	}
	public function remove($v) {
		$prev = null;
		$l = $this->head;
		while($l !== null) {
			if($l->elt === $v) {
				if($prev === null) {
					$this->head = $l->next;
				}
				else {
					$prev->next = $l->next;
				}
				break;
			}
			$prev = $l;
			$l = $l->next;
			;
		}
		return ($l !== null);
	}
	public function iterator() {
		$l = $this->head;
		return _hx_anonymous(array("hasNext" => array(new _hx_lambda(array("l" => &$l), null, array(), "{
			return \$l !== null;
		}"), 'execute0'), "next" => array(new _hx_lambda(array("l" => &$l), null, array(), "{
			\$k = \$l;
			\$l = \$k->next;
			return \$k->elt;
		}"), 'execute0')));
	}
	public function toString() {
		$a = new _hx_array(array());
		$l = $this->head;
		while($l !== null) {
			$a->push($l->elt);
			$l = $l->next;
			;
		}
		return "{" . $a->join(",") . "}";
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
