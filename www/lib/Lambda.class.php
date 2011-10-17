<?php

class Lambda {
	public function __construct(){}
	static function harray($it) {
		$a = new _hx_array(array());
		if(null == $it) throw new HException('null iterable');
		$»it = $it->iterator();
		while($»it->hasNext()) {
		$i = $»it->next();
		$a->push($i);
		}
		return $a;
		unset($a);
	}
	static function hlist($it) {
		$l = new HList();
		if(null == $it) throw new HException('null iterable');
		$»it = $it->iterator();
		while($»it->hasNext()) {
		$i = $»it->next();
		$l->add($i);
		}
		return $l;
		unset($l);
	}
	static function map($it, $f) {
		$l = new HList();
		if(null == $it) throw new HException('null iterable');
		$»it = $it->iterator();
		while($»it->hasNext()) {
		$x = $»it->next();
		$l->add(call_user_func_array($f, array($x)));
		}
		return $l;
		unset($l);
	}
	static function mapi($it, $f) {
		$l = new HList();
		$i = 0;
		if(null == $it) throw new HException('null iterable');
		$»it = $it->iterator();
		while($»it->hasNext()) {
		$x = $»it->next();
		$l->add(call_user_func_array($f, array($i++, $x)));
		}
		return $l;
		unset($l,$i);
	}
	static function has($it, $elt, $cmp) {
		if($cmp === null) {
			if(null == $it) throw new HException('null iterable');
			$»it = $it->iterator();
			while($»it->hasNext()) {
			$x = $»it->next();
			if($x === $elt) {
				return true;
				;
			}
			}
			;
		}
		else {
			if(null == $it) throw new HException('null iterable');
			$»it = $it->iterator();
			while($»it->hasNext()) {
			$x = $»it->next();
			if(call_user_func_array($cmp, array($x, $elt))) {
				return true;
				;
			}
			}
			;
		}
		return false;
		;
	}
	static function exists($it, $f) {
		if(null == $it) throw new HException('null iterable');
		$»it = $it->iterator();
		while($»it->hasNext()) {
		$x = $»it->next();
		if(call_user_func_array($f, array($x))) {
			return true;
			;
		}
		}
		return false;
		;
	}
	static function hforeach($it, $f) {
		if(null == $it) throw new HException('null iterable');
		$»it = $it->iterator();
		while($»it->hasNext()) {
		$x = $»it->next();
		if(!call_user_func_array($f, array($x))) {
			return false;
			;
		}
		}
		return true;
		;
	}
	static function iter($it, $f) {
		if(null == $it) throw new HException('null iterable');
		$»it = $it->iterator();
		while($»it->hasNext()) {
		$x = $»it->next();
		call_user_func_array($f, array($x));
		}
		;
	}
	static function filter($it, $f) {
		$l = new HList();
		if(null == $it) throw new HException('null iterable');
		$»it = $it->iterator();
		while($»it->hasNext()) {
		$x = $»it->next();
		if(call_user_func_array($f, array($x))) {
			$l->add($x);
			;
		}
		}
		return $l;
		unset($l);
	}
	static function fold($it, $f, $first) {
		if(null == $it) throw new HException('null iterable');
		$»it = $it->iterator();
		while($»it->hasNext()) {
		$x = $»it->next();
		$first = call_user_func_array($f, array($x, $first));
		}
		return $first;
		;
	}
	static function count($it) {
		$n = 0;
		if(null == $it) throw new HException('null iterable');
		$»it = $it->iterator();
		while($»it->hasNext()) {
		$_ = $»it->next();
		++$n;
		}
		return $n;
		unset($n);
	}
	static function hempty($it) {
		return !$it->iterator()->hasNext();
		;
	}
	function __toString() { return 'Lambda'; }
}
