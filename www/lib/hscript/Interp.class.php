<?php

class hscript_Interp {
	public function __construct() {
		if( !php_Boot::$skip_constructor ) {
		$this->locals = new Hash();
		$this->declared = new _hx_array(array());
		$this->variables = new Hash();
		$this->variables->set("null", null);
		$this->variables->set("true", true);
		$this->variables->set("false", false);
		$this->variables->set("trace", array(new _hx_lambda(array(), null, array('e'), "{
			haxe_Log::trace(Std::string(\$e), _hx_anonymous(array(\"fileName\" => \"hscript\", \"lineNumber\" => 0)));
		}"), 'execute1'));
		$this->initOps();
	}}
	public $variables;
	public $locals;
	public $binops;
	public $declared;
	public function initOps() {
		$me = $this;
		$this->binops = new Hash();
		$this->binops->set("+", array(new _hx_lambda(array("me" => &$me), null, array('e1','e2'), "{
			return _hx_add(\$me->expr(\$e1), \$me->expr(\$e2));
		}"), 'execute2'));
		$this->binops->set("-", array(new _hx_lambda(array("me" => &$me), null, array('e1','e2'), "{
			return \$me->expr(\$e1) - \$me->expr(\$e2);
		}"), 'execute2'));
		$this->binops->set("*", array(new _hx_lambda(array("me" => &$me), null, array('e1','e2'), "{
			return \$me->expr(\$e1) * \$me->expr(\$e2);
		}"), 'execute2'));
		$this->binops->set("/", array(new _hx_lambda(array("me" => &$me), null, array('e1','e2'), "{
			return \$me->expr(\$e1) / \$me->expr(\$e2);
		}"), 'execute2'));
		$this->binops->set("%", array(new _hx_lambda(array("me" => &$me), null, array('e1','e2'), "{
			return \$me->expr(\$e1) % \$me->expr(\$e2);
		}"), 'execute2'));
		$this->binops->set("&", array(new _hx_lambda(array("me" => &$me), null, array('e1','e2'), "{
			return \$me->expr(\$e1) & \$me->expr(\$e2);
		}"), 'execute2'));
		$this->binops->set("|", array(new _hx_lambda(array("me" => &$me), null, array('e1','e2'), "{
			return \$me->expr(\$e1) | \$me->expr(\$e2);
		}"), 'execute2'));
		$this->binops->set("^", array(new _hx_lambda(array("me" => &$me), null, array('e1','e2'), "{
			return \$me->expr(\$e1) ^ \$me->expr(\$e2);
		}"), 'execute2'));
		$this->binops->set("<<", array(new _hx_lambda(array("me" => &$me), null, array('e1','e2'), "{
			return \$me->expr(\$e1) << \$me->expr(\$e2);
		}"), 'execute2'));
		$this->binops->set(">>", array(new _hx_lambda(array("me" => &$me), null, array('e1','e2'), "{
			return \$me->expr(\$e1) >> \$me->expr(\$e2);
		}"), 'execute2'));
		$this->binops->set(">>>", array(new _hx_lambda(array("me" => &$me), null, array('e1','e2'), "{
			return _hx_shift_right(\$me->expr(\$e1), \$me->expr(\$e2));
		}"), 'execute2'));
		$this->binops->set("==", array(new _hx_lambda(array("me" => &$me), null, array('e1','e2'), "{
			return _hx_equal(\$me->expr(\$e1), \$me->expr(\$e2));
		}"), 'execute2'));
		$this->binops->set("!=", array(new _hx_lambda(array("me" => &$me), null, array('e1','e2'), "{
			return !_hx_equal(\$me->expr(\$e1), \$me->expr(\$e2));
		}"), 'execute2'));
		$this->binops->set(">=", array(new _hx_lambda(array("me" => &$me), null, array('e1','e2'), "{
			return \$me->expr(\$e1) >= \$me->expr(\$e2);
		}"), 'execute2'));
		$this->binops->set("<=", array(new _hx_lambda(array("me" => &$me), null, array('e1','e2'), "{
			return \$me->expr(\$e1) <= \$me->expr(\$e2);
		}"), 'execute2'));
		$this->binops->set(">", array(new _hx_lambda(array("me" => &$me), null, array('e1','e2'), "{
			return \$me->expr(\$e1) > \$me->expr(\$e2);
		}"), 'execute2'));
		$this->binops->set("<", array(new _hx_lambda(array("me" => &$me), null, array('e1','e2'), "{
			return \$me->expr(\$e1) < \$me->expr(\$e2);
		}"), 'execute2'));
		$this->binops->set("||", array(new _hx_lambda(array("me" => &$me), null, array('e1','e2'), "{
			return _hx_equal(\$me->expr(\$e1), true) || _hx_equal(\$me->expr(\$e2), true);
		}"), 'execute2'));
		$this->binops->set("&&", array(new _hx_lambda(array("me" => &$me), null, array('e1','e2'), "{
			return _hx_equal(\$me->expr(\$e1), true) && _hx_equal(\$me->expr(\$e2), true);
		}"), 'execute2'));
		$this->binops->set("=", isset($this->assign) ? $this->assign: array($this, "assign"));
		$this->binops->set("...", array(new _hx_lambda(array("me" => &$me), null, array('e1','e2'), "{
			return new IntIter(\$me->expr(\$e1), \$me->expr(\$e2));
		}"), 'execute2'));
		$this->assignOp("+=", array(new _hx_lambda(array("me" => &$me), null, array('v1','v2'), "{
			return _hx_add(\$v1, \$v2);
		}"), 'execute2'));
		$this->assignOp("-=", array(new _hx_lambda(array("me" => &$me), null, array('v1','v2'), "{
			return \$v1 - \$v2;
		}"), 'execute2'));
		$this->assignOp("*=", array(new _hx_lambda(array("me" => &$me), null, array('v1','v2'), "{
			return \$v1 * \$v2;
		}"), 'execute2'));
		$this->assignOp("/=", array(new _hx_lambda(array("me" => &$me), null, array('v1','v2'), "{
			return \$v1 / \$v2;
		}"), 'execute2'));
		$this->assignOp("%=", array(new _hx_lambda(array("me" => &$me), null, array('v1','v2'), "{
			return \$v1 % \$v2;
		}"), 'execute2'));
		$this->assignOp("&=", array(new _hx_lambda(array("me" => &$me), null, array('v1','v2'), "{
			return \$v1 & \$v2;
		}"), 'execute2'));
		$this->assignOp("|=", array(new _hx_lambda(array("me" => &$me), null, array('v1','v2'), "{
			return \$v1 | \$v2;
		}"), 'execute2'));
		$this->assignOp("^=", array(new _hx_lambda(array("me" => &$me), null, array('v1','v2'), "{
			return \$v1 ^ \$v2;
		}"), 'execute2'));
		$this->assignOp("<<=", array(new _hx_lambda(array("me" => &$me), null, array('v1','v2'), "{
			return \$v1 << \$v2;
		}"), 'execute2'));
		$this->assignOp(">>=", array(new _hx_lambda(array("me" => &$me), null, array('v1','v2'), "{
			return \$v1 >> \$v2;
		}"), 'execute2'));
		$this->assignOp(">>>=", array(new _hx_lambda(array("me" => &$me), null, array('v1','v2'), "{
			return _hx_shift_right(\$v1, \$v2);
		}"), 'execute2'));
	}
	public function assign($e1, $e2) {
		$v = $this->expr($e2);
		$»t = ($e1);
		switch($»t->index) {
		case 1:
		$id = $»t->params[0];
		{
			$l = $this->locals->get($id);
			if($l === null) {
				$this->variables->set($id, $v);
			}
			else {
				$l->r = $v;
			}
		}break;
		case 5:
		$f = $»t->params[1]; $e = $»t->params[0];
		{
			$v = $this->set($this->expr($e), $f, $v);
		}break;
		case 16:
		$index = $»t->params[1]; $e3 = $»t->params[0];
		{
			_hx_array_assign($this->expr($e3), $this->expr($index), $v);
		}break;
		default:{
			throw new HException(hscript_Error::EInvalidOp("="));
		}break;
		}
		return $v;
	}
	public function assignOp($op, $fop) {
		$me = $this;
		$this->binops->set($op, array(new _hx_lambda(array("fop" => &$fop, "me" => &$me, "op" => &$op), null, array('e1','e2'), "{
			return \$me->evalAssignOp(\$op, \$fop, \$e1, \$e2);
		}"), 'execute2'));
	}
	public function evalAssignOp($op, $fop, $e1, $e2) {
		$v = null;
		$»t = ($e1);
		switch($»t->index) {
		case 1:
		$id = $»t->params[0];
		{
			$l = $this->locals->get($id);
			$v = call_user_func_array($fop, array($this->expr($e1), $this->expr($e2)));
			if($l === null) {
				$this->variables->set($id, $v);
			}
			else {
				$l->r = $v;
			}
		}break;
		case 5:
		$f = $»t->params[1]; $e = $»t->params[0];
		{
			$obj = $this->expr($e);
			$v = call_user_func_array($fop, array($this->get($obj, $f), $this->expr($e2)));
			$v = $this->set($obj, $f, $v);
		}break;
		case 16:
		$index = $»t->params[1]; $e3 = $»t->params[0];
		{
			$arr = $this->expr($e3);
			$index1 = $this->expr($index);
			$v = call_user_func_array($fop, array($arr[$index1], $this->expr($e2)));
			$arr[$index1] = $v;
		}break;
		default:{
			throw new HException(hscript_Error::EInvalidOp($op));
		}break;
		}
		return $v;
	}
	public function increment($e, $prefix, $delta) {
		$»t = ($e);
		switch($»t->index) {
		case 1:
		$id = $»t->params[0];
		{
			$l = $this->locals->get($id);
			$v = (($l === null) ? $this->variables->get($id) : $l->r);
			if($prefix) {
				$v += $delta;
				if($l === null) {
					$this->variables->set($id, $v);
				}
				else {
					$l->r = $v;
				}
			}
			else {
				if($l === null) {
					$this->variables->set($id, $v + $delta);
				}
				else {
					$l->r = $v + $delta;
				}
			}
			return $v;
		}break;
		case 5:
		$f = $»t->params[1]; $e1 = $»t->params[0];
		{
			$obj = $this->expr($e1);
			$v2 = $this->get($obj, $f);
			if($prefix) {
				$v2 += $delta;
				$this->set($obj, $f, $v2);
			}
			else {
				$this->set($obj, $f, $v2 + $delta);
			}
			return $v2;
		}break;
		case 16:
		$index = $»t->params[1]; $e12 = $»t->params[0];
		{
			$arr = $this->expr($e12);
			$index1 = $this->expr($index);
			$v3 = $arr[$index1];
			if($prefix) {
				$v3 += $delta;
				$arr[$index1] = $v3;
			}
			else {
				$arr[$index1] = $v3 + $delta;
			}
			return $v3;
		}break;
		default:{
			throw new HException(hscript_Error::EInvalidOp((($delta > 0) ? "++" : "--")));
		}break;
		}
	}
	public function execute($expr) {
		$this->locals = new Hash();
		return $this->exprReturn($expr);
	}
	public function exprReturn($e) {
		try {
			return $this->expr($e);
		}catch(Exception $»e) {
		$_ex_ = ($»e instanceof HException) ? $»e->e : $»e;
		;
		if(($e1 = $_ex_) instanceof hscript__Interp_Stop){
			$»t = ($e1);
			switch($»t->index) {
			case 0:
			{
				throw new HException("Invalid break");
			}break;
			case 1:
			{
				throw new HException("Invalid continue");
			}break;
			case 2:
			$v = $»t->params[0];
			{
				return $v;
			}break;
			}
		} else throw $»e; }
		return null;
	}
	public function duplicate($h) {
		$h2 = new Hash();
		$»it = $h->keys();
		while($»it->hasNext()) {
		$k = $»it->next();
		$h2->set($k, $h->get($k));
		}
		return $h2;
	}
	public function restore($old) {
		while($this->declared->length > $old) {
			$d = $this->declared->pop();
			$this->locals->set($d->n, $d->old);
			unset($d);
		}
	}
	public function expr($e) {
		$»t = ($e);
		switch($»t->index) {
		case 0:
		$c = $»t->params[0];
		{
			$»t2 = ($c);
			switch($»t2->index) {
			case 0:
			$v = $»t2->params[0];
			{
				return $v;
			}break;
			case 3:
			$v2 = $»t2->params[0];
			{
				return $v2;
			}break;
			case 1:
			$f = $»t2->params[0];
			{
				return $f;
			}break;
			case 2:
			$s = $»t2->params[0];
			{
				return $s;
			}break;
			}
		}break;
		case 1:
		$id = $»t->params[0];
		{
			$l = $this->locals->get($id);
			if($l !== null) {
				return $l->r;
			}
			$v3 = $this->variables->get($id);
			if($v3 === null && !$this->variables->exists($id)) {
				throw new HException(hscript_Error::EUnknownVariable($id));
			}
			return $v3;
		}break;
		case 2:
		$e1 = $»t->params[1]; $n = $»t->params[0];
		{
			$this->declared->push(_hx_anonymous(array("n" => $n, "old" => $this->locals->get($n))));
			$this->locals->set($n, _hx_anonymous(array("r" => (($e1 === null) ? null : $this->expr($e1)))));
			return null;
		}break;
		case 3:
		$e12 = $»t->params[0];
		{
			return $this->expr($e12);
		}break;
		case 4:
		$exprs = $»t->params[0];
		{
			$old = $this->declared->length;
			$v4 = null;
			{
				$_g = 0;
				while($_g < $exprs->length) {
					$e13 = $exprs[$_g];
					++$_g;
					$v4 = $this->expr($e13);
					unset($e13);
				}
			}
			$this->restore($old);
			return $v4;
		}break;
		case 5:
		$f2 = $»t->params[1]; $e14 = $»t->params[0];
		{
			return $this->get($this->expr($e14), $f2);
		}break;
		case 6:
		$e2 = $»t->params[2]; $e15 = $»t->params[1]; $op = $»t->params[0];
		{
			$fop = $this->binops->get($op);
			if($fop === null) {
				throw new HException(hscript_Error::EInvalidOp($op));
			}
			return call_user_func_array($fop, array($e15, $e2));
		}break;
		case 7:
		$e16 = $»t->params[2]; $prefix = $»t->params[1]; $op2 = $»t->params[0];
		{
			switch($op2) {
			case "!":{
				return !_hx_equal($this->expr($e16), true);
			}break;
			case "-":{
				return -$this->expr($e16);
			}break;
			case "++":{
				return $this->increment($e16, $prefix, 1);
			}break;
			case "--":{
				return $this->increment($e16, $prefix, -1);
			}break;
			case "~":{
				return ~$this->expr($e16);
			}break;
			default:{
				throw new HException(hscript_Error::EInvalidOp($op2));
			}break;
			}
		}break;
		case 8:
		$params = $»t->params[1]; $e17 = $»t->params[0];
		{
			$args = new _hx_array(array());
			{
				$_g2 = 0;
				while($_g2 < $params->length) {
					$p = $params[$_g2];
					++$_g2;
					$args->push($this->expr($p));
					unset($p);
				}
			}
			$»t3 = ($e17);
			switch($»t3->index) {
			case 5:
			$f3 = $»t3->params[1]; $e22 = $»t3->params[0];
			{
				$obj = $this->expr($e22);
				if($obj === null) {
					throw new HException(hscript_Error::EInvalidAccess($f3));
				}
				return $this->call($obj, Reflect::field($obj, $f3), $args);
			}break;
			default:{
				return $this->call(null, $this->expr($e17), $args);
			}break;
			}
		}break;
		case 9:
		$e23 = $»t->params[2]; $e18 = $»t->params[1]; $econd = $»t->params[0];
		{
			return (_hx_equal($this->expr($econd), true) ? $this->expr($e18) : ($e23 === null ? null : $this->expr($e23)));
		}break;
		case 10:
		$e19 = $»t->params[1]; $econd2 = $»t->params[0];
		{
			$this->whileLoop($econd2, $e19);
			return null;
		}break;
		case 11:
		$e110 = $»t->params[2]; $it = $»t->params[1]; $v5 = $»t->params[0];
		{
			$this->forLoop($v5, $it, $e110);
			return null;
		}break;
		case 12:
		{
			throw new HException(hscript__Interp_Stop::$SBreak);
		}break;
		case 13:
		{
			throw new HException(hscript__Interp_Stop::$SContinue);
		}break;
		case 15:
		$e111 = $»t->params[0];
		{
			throw new HException(hscript__Interp_Stop::SReturn((($e111 === null) ? null : $this->expr($e111))));
		}break;
		case 14:
		$name = $»t->params[2]; $fexpr = $»t->params[1]; $params2 = $»t->params[0];
		{
			$capturedLocals = $this->duplicate($this->locals);
			$me = $this;
			$f4 = array(new _hx_lambda(array("_g" => &$_g, "_g2" => &$_g2, "args" => &$args, "c" => &$c, "capturedLocals" => &$capturedLocals, "e" => &$e, "e1" => &$e1, "e110" => &$e110, "e111" => &$e111, "e12" => &$e12, "e13" => &$e13, "e14" => &$e14, "e15" => &$e15, "e16" => &$e16, "e17" => &$e17, "e18" => &$e18, "e19" => &$e19, "e2" => &$e2, "e22" => &$e22, "e23" => &$e23, "econd" => &$econd, "econd2" => &$econd2, "exprs" => &$exprs, "f" => &$f, "f2" => &$f2, "f3" => &$f3, "f4" => &$f4, "fexpr" => &$fexpr, "fop" => &$fop, "id" => &$id, "it" => &$it, "l" => &$l, "me" => &$me, "n" => &$n, "name" => &$name, "obj" => &$obj, "old" => &$old, "op" => &$op, "op2" => &$op2, "p" => &$p, "params" => &$params, "params2" => &$params2, "prefix" => &$prefix, "s" => &$s, "v" => &$v, "v2" => &$v2, "v3" => &$v3, "v4" => &$v4, "v5" => &$v5, "»t" => &$»t, "»t2" => &$»t2, "»t3" => &$»t3), null, array('args2'), "{
				if(\$args2->length !== \$params2->length) {
					throw new HException(\"Invalid number of parameters\");
				}
				\$old2 = \$me->locals;
				\$me->locals = \$me->duplicate(\$capturedLocals);
				{
					\$_g1 = 0; \$_g3 = \$params2->length;
					while(\$_g1 < \$_g3) {
						\$i = \$_g1++;
						\$me->locals->set(\$params2[\$i], _hx_anonymous(array(\"r\" => \$args2[\$i])));
						unset(\$i);
					}
				}
				\$r = null;
				try {
					\$r = \$me->exprReturn(\$fexpr);
				}catch(Exception \$»e) {
				\$_ex_ = (\$»e instanceof HException) ? \$»e->e : \$»e;
				;
				{ \$e112 = \$_ex_;
				{
					\$me->locals = \$old2;
					throw new HException(\$e112);
				}}}
				\$me->locals = \$old2;
				return \$r;
			}"), 'execute1');
			$f1 = Reflect::makeVarArgs($f4);
			if($name !== null) {
				$this->variables->set($name, $f1);
			}
			return $f1;
		}break;
		case 17:
		$arr = $»t->params[0];
		{
			$a = new _hx_array(array());
			{
				$_g3 = 0;
				while($_g3 < $arr->length) {
					$e112 = $arr[$_g3];
					++$_g3;
					$a->push($this->expr($e112));
					unset($e112);
				}
			}
			return $a;
		}break;
		case 16:
		$index = $»t->params[1]; $e113 = $»t->params[0];
		{
			return _hx_array_get($this->expr($e113), $this->expr($index));
		}break;
		case 18:
		$params3 = $»t->params[1]; $cl = $»t->params[0];
		{
			$a2 = new _hx_array(array());
			{
				$_g4 = 0;
				while($_g4 < $params3->length) {
					$e114 = $params3[$_g4];
					++$_g4;
					$a2->push($this->expr($e114));
					unset($e114);
				}
			}
			return $this->cnew($cl, $a2);
		}break;
		case 19:
		$e115 = $»t->params[0];
		{
			throw new HException($this->expr($e115));
		}break;
		case 20:
		$ecatch = $»t->params[2]; $n2 = $»t->params[1]; $e116 = $»t->params[0];
		{
			$old2 = $this->declared->length;
			try {
				$v6 = $this->expr($e116);
				$this->restore($old2);
				return $v6;
			}catch(Exception $»e) {
			$_ex_ = ($»e instanceof HException) ? $»e->e : $»e;
			;
			if(($err = $_ex_) instanceof hscript__Interp_Stop){
				throw new HException($err);
			}
			else { $err2 = $_ex_;
			{
				$this->restore($old2);
				$this->declared->push(_hx_anonymous(array("n" => $n2, "old" => $this->locals->get($n2))));
				$this->locals->set($n2, _hx_anonymous(array("r" => $err2)));
				$v7 = $this->expr($ecatch);
				$this->restore($old2);
				return $v7;
			}}}
		}break;
		}
		return null;
	}
	public function whileLoop($econd, $e) {
		$old = $this->declared->length;
		try {
			while(_hx_equal($this->expr($econd), true)) {
				try {
					$this->expr($e);
				}catch(Exception $»e) {
				$_ex_ = ($»e instanceof HException) ? $»e->e : $»e;
				;
				if(($err = $_ex_) instanceof hscript__Interp_Stop){
					$»t = ($err);
					switch($»t->index) {
					case 1:
					{
						;
					}break;
					case 0:
					{
						throw new _hx_break_exception();
					}break;
					case 2:
					{
						throw new HException($err);
					}break;
					}
				} else throw $»e; }
				unset($»t,$»e,$err,$_ex_);
			}
		} catch(_hx_break_exception $»e){}
		$this->restore($old);
	}
	public function makeIterator($v) {
		try {
			$v = $v->iterator();
		}catch(Exception $»e) {
		$_ex_ = ($»e instanceof HException) ? $»e->e : $»e;
		;
		{ $e = $_ex_;
		{
			;
		}}}
		if(_hx_field($v, "hasNext") === null || _hx_field($v, "next") === null) {
			throw new HException(hscript_Error::EInvalidIterator($v));
		}
		return $v;
	}
	public function forLoop($n, $it, $e) {
		$old = $this->declared->length;
		$this->declared->push(_hx_anonymous(array("n" => $n, "old" => $this->locals->get($n))));
		$it1 = $this->makeIterator($this->expr($it));
		try {
			while($it1->hasNext()) {
				$this->locals->set($n, _hx_anonymous(array("r" => $it1->next())));
				try {
					$this->expr($e);
				}catch(Exception $»e) {
				$_ex_ = ($»e instanceof HException) ? $»e->e : $»e;
				;
				if(($err = $_ex_) instanceof hscript__Interp_Stop){
					$»t = ($err);
					switch($»t->index) {
					case 1:
					{
						;
					}break;
					case 0:
					{
						throw new _hx_break_exception();
					}break;
					case 2:
					{
						throw new HException($err);
					}break;
					}
				} else throw $»e; }
				unset($»t,$»e,$err,$_ex_);
			}
		} catch(_hx_break_exception $»e){}
		$this->restore($old);
	}
	public function get($o, $f) {
		if($o === null) {
			throw new HException(hscript_Error::EInvalidAccess($f));
		}
		return Reflect::field($o, $f);
	}
	public function set($o, $f, $v) {
		if($o === null) {
			throw new HException(hscript_Error::EInvalidAccess($f));
		}
		$o->{$f} = $v;
		return $v;
	}
	public function call($o, $f, $args) {
		return Reflect::callMethod($o, $f, $args);
	}
	public function cnew($cl, $args) {
		return Type::createInstance(Type::resolveClass($cl), $args);
	}
	public function __call($m, $a) {
		if(isset($this->$m) && is_callable($this->$m))
			return call_user_func_array($this->$m, $a);
		else if(isset($this->»dynamics[$m]) && is_callable($this->»dynamics[$m]))
			return call_user_func_array($this->»dynamics[$m], $a);
		else
			throw new HException('Unable to call «'.$m.'»');
	}
	function __toString() { return 'hscript.Interp'; }
}
