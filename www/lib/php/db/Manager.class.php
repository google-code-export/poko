<?php

class php_db_Manager {
	public function __construct($classval) {
		if( !php_Boot::$skip_constructor ) {
		$this->cls = $classval;
		$clname = Type::getClassName($this->cls);
		$this->table_name = $this->quoteField(php_db_Manager_0($this, $classval, $clname));
		$this->table_keys = php_db_Manager_1($this, $classval, $clname);
		$apriv = $this->cls->PRIVATE_FIELDS;
		$apriv = php_db_Manager_2($this, $apriv, $classval, $clname);
		$apriv->push("__cache__");
		$apriv->push("__noupdate__");
		$apriv->push("__manager__");
		$apriv->push("update");
		$this->table_fields = new HList();
		$stub = Type::createEmptyInstance($this->cls);
		$instance_fields = Type::getInstanceFields($this->cls);
		$scls = Type::getSuperClass($this->cls);
		while($scls !== null) {
			{
				$_g = 0; $_g1 = Type::getInstanceFields($scls);
				while($_g < $_g1->length) {
					$remove = $_g1[$_g];
					++$_g;
					$instance_fields->remove($remove);
					unset($remove);
				}
				unset($_g1,$_g);
			}
			$scls = Type::getSuperClass($scls);
			;
		}
		{
			$_g = 0;
			while($_g < $instance_fields->length) {
				$f = $instance_fields[$_g];
				++$_g;
				$isfield = !Reflect::isFunction(Reflect::field($stub, $f));
				if($isfield) {
					$_g1 = 0;
					while($_g1 < $apriv->length) {
						$f2 = $apriv[$_g1];
						++$_g1;
						if($f === $f2) {
							$isfield = false;
							break;
							;
						}
						unset($f2);
					}
					unset($_g1);
				}
				if($isfield) {
					$this->table_fields->add($f);
					;
				}
				unset($isfield,$f);
			}
			unset($_g);
		}
		php_db_Manager::$managers->set($clname, $this);
		$rl = null;
		try {
			$rl = $this->cls->RELATIONS();
			;
		}catch(Exception $»e) {
		$_ex_ = ($»e instanceof HException) ? $»e->e : $»e;
		;
		{ $e = $_ex_;
		{
			return;
			;
		}}}
		{
			$_g = 0;
			while($_g < $rl->length) {
				$r = $rl[$_g];
				++$_g;
				$this->table_fields->remove($r->prop);
				$this->table_fields->remove("get_" . $r->prop);
				$this->table_fields->remove("set_" . $r->prop);
				$this->table_fields->remove($r->key);
				$this->table_fields->add($r->key);
				unset($r);
			}
			unset($_g);
		}
		unset($stub,$scls,$rl,$instance_fields,$e,$clname,$apriv);
	}}
	public $table_name;
	public $table_fields;
	public $table_keys;
	public $cls;
	public function get($id, $lock) {
		if($lock === null) {
			$lock = true;
			;
		}
		if($this->table_keys->length !== 1) {
			throw new HException("Invalid number of keys");
			;
		}
		if($id === null) {
			return null;
			;
		}
		$x = php_db_Manager::$object_cache->get($id . $this->table_name);
		if($x !== null && (!$lock || !$x->__noupdate__)) {
			return $x;
			;
		}
		$s = new StringBuf();
		$s->b .= "SELECT * FROM ";
		$s->b .= $this->table_name;
		$s->b .= " WHERE ";
		$s->b .= $this->quoteField($this->table_keys[0]);
		$s->b .= " = ";
		php_db_Manager::$cnx->addValue($s, $id);
		if($lock) {
			$s->b .= php_db_Manager::$FOR_UPDATE;
			;
		}
		return $this->object($s->b, $lock);
		unset($x,$s);
	}
	public function getWithKeys($keys, $lock) {
		if($lock === null) {
			$lock = true;
			;
		}
		$x = $this->getFromCache($keys, false);
		if($x !== null && (!$lock || !$x->__noupdate__)) {
			return $x;
			;
		}
		$s = new StringBuf();
		$s->b .= "SELECT * FROM ";
		$s->b .= $this->table_name;
		$s->b .= " WHERE ";
		$this->addKeys($s, $keys);
		if($lock) {
			$s->b .= php_db_Manager::$FOR_UPDATE;
			;
		}
		return $this->object($s->b, $lock);
		unset($x,$s);
	}
	public function delete($x) {
		$s = new StringBuf();
		$s->b .= "DELETE FROM ";
		$s->b .= $this->table_name;
		$s->b .= " WHERE ";
		$this->addCondition($s, $x);
		$this->execute($s->b);
		unset($s);
	}
	public function search($x, $lock) {
		if($lock === null) {
			$lock = true;
			;
		}
		$s = new StringBuf();
		$s->b .= "SELECT * FROM ";
		$s->b .= $this->table_name;
		$s->b .= " WHERE ";
		$this->addCondition($s, $x);
		if($lock) {
			$s->b .= php_db_Manager::$FOR_UPDATE;
			;
		}
		return $this->objects($s->b, $lock);
		unset($s);
	}
	public function addCondition($s, $x) {
		$first = true;
		if($x !== null) {
			$_g = 0; $_g1 = Reflect::fields($x);
			while($_g < $_g1->length) {
				$f = $_g1[$_g];
				++$_g;
				if($first) {
					$first = false;
					;
				}
				else {
					$s->b .= " AND ";
					;
				}
				$s->b .= $this->quoteField($f);
				$d = Reflect::field($x, $f);
				if($d === null) {
					$s->b .= " IS NULL";
					;
				}
				else {
					$s->b .= " = ";
					php_db_Manager::$cnx->addValue($s, $d);
					;
				}
				unset($f,$d);
			}
			unset($_g1,$_g);
		}
		if($first) {
			$s->b .= "1";
			;
		}
		unset($first);
	}
	public function all($lock) {
		if($lock === null) {
			$lock = true;
			;
		}
		return $this->objects(("SELECT * FROM " . $this->table_name) . php_db_Manager_3($this, $lock), $lock);
		;
	}
	public function count($x) {
		$s = new StringBuf();
		$s->b .= "SELECT COUNT(*) FROM ";
		$s->b .= $this->table_name;
		$s->b .= " WHERE ";
		$this->addCondition($s, $x);
		return $this->execute($s->b)->getIntResult(0);
		unset($s);
	}
	public function quote($s) {
		return php_db_Manager::$cnx->quote($s);
		;
	}
	public function result($sql) {
		return php_db_Manager::$cnx->request($sql)->next();
		;
	}
	public function results($sql) {
		return php_db_Manager::$cnx->request($sql)->results();
		;
	}
	public function doInsert($x) {
		$this->unmake($x);
		$s = new StringBuf();
		$fields = new HList();
		$values = new HList();
		if(null == $this->table_fields) throw new HException('null iterable');
		$»it = $this->table_fields->iterator();
		while($»it->hasNext()) {
		$f = $»it->next();
		{
			$v = Reflect::field($x, $f);
			if($v !== null) {
				$fields->add($this->quoteField($f));
				$values->add($v);
				;
			}
			unset($v);
		}
		}
		$s->b .= "INSERT INTO ";
		$s->b .= $this->table_name;
		$s->b .= " (";
		$s->b .= $fields->join(",");
		$s->b .= ") VALUES (";
		$first = true;
		if(null == $values) throw new HException('null iterable');
		$»it = $values->iterator();
		while($»it->hasNext()) {
		$v = $»it->next();
		{
			if($first) {
				$first = false;
				;
			}
			else {
				$s->b .= ", ";
				;
			}
			php_db_Manager::$cnx->addValue($s, $v);
			;
		}
		}
		$s->b .= ")";
		$this->execute($s->b);
		if($this->table_keys->length === 1 && Reflect::field($x, $this->table_keys[0]) === null) {
			$x->{$this->table_keys[0]} = php_db_Manager::$cnx->lastInsertId();
			;
		}
		$this->addToCache($x);
		unset($values,$s,$first,$fields);
	}
	public function doUpdate($x) {
		$this->unmake($x);
		$s = new StringBuf();
		$s->b .= "UPDATE ";
		$s->b .= $this->table_name;
		$s->b .= " SET ";
		$cache = Reflect::field($x, php_db_Manager::$cache_field);
		if(null === $cache) {
			$cache = $this->cacheObject($x, false);
			$x->{php_db_Manager::$cache_field} = $cache;
			;
		}
		$mod = false;
		if(null == $this->table_fields) throw new HException('null iterable');
		$»it = $this->table_fields->iterator();
		while($»it->hasNext()) {
		$f = $»it->next();
		{
			$v = Reflect::field($x, $f);
			$vc = Reflect::field($cache, $f);
			if(!_hx_equal($v, $vc)) {
				if($mod) {
					$s->b .= ", ";
					;
				}
				else {
					$mod = true;
					;
				}
				$s->b .= $this->quoteField($f);
				$s->b .= " = ";
				php_db_Manager::$cnx->addValue($s, $v);
				$cache->{$f} = $v;
				;
			}
			unset($vc,$v);
		}
		}
		if(!$mod) {
			return;
			;
		}
		$s->b .= " WHERE ";
		$this->addKeys($s, $x);
		$this->execute($s->b);
		unset($s,$mod,$cache);
	}
	public function doDelete($x) {
		$s = new StringBuf();
		$s->b .= "DELETE FROM ";
		$s->b .= $this->table_name;
		$s->b .= " WHERE ";
		$this->addKeys($s, $x);
		$this->execute($s->b);
		unset($s);
	}
	public function doSync($i) {
		php_db_Manager::$object_cache->remove($this->makeCacheKey($i));
		$i2 = $this->getWithKeys($i, !$i->__noupdate__);
		{
			$_g = 0; $_g1 = Reflect::fields($i);
			while($_g < $_g1->length) {
				$f = $_g1[$_g];
				++$_g;
				Reflect::deleteField($i, $f);
				unset($f);
			}
			unset($_g1,$_g);
		}
		{
			$_g = 0; $_g1 = Reflect::fields($i2);
			while($_g < $_g1->length) {
				$f = $_g1[$_g];
				++$_g;
				$i->{$f} = Reflect::field($i2, $f);
				unset($f);
			}
			unset($_g1,$_g);
		}
		$i->{php_db_Manager::$cache_field} = Reflect::field($i2, php_db_Manager::$cache_field);
		$this->addToCache($i);
		unset($i2);
	}
	public function objectToString($it) {
		$s = new StringBuf();
		$s->b .= $this->table_name;
		if($this->table_keys->length === 1) {
			$s->b .= "#";
			$s->b .= Reflect::field($it, $this->table_keys[0]);
			;
		}
		else {
			$s->b .= "(";
			$first = true;
			{
				$_g = 0; $_g1 = $this->table_keys;
				while($_g < $_g1->length) {
					$f = $_g1[$_g];
					++$_g;
					if($first) {
						$first = false;
						;
					}
					else {
						$s->b .= ",";
						;
					}
					$s->b .= $this->quoteField($f);
					$s->b .= ":";
					$s->b .= Reflect::field($it, $f);
					unset($f);
				}
				unset($_g1,$_g);
			}
			$s->b .= ")";
			unset($first);
		}
		return $s->b;
		unset($s);
	}
	public function cacheObject($x, $lock) {
		$o = Type::createEmptyInstance($this->cls);
		{
			$_g = 0; $_g1 = Reflect::fields($x);
			while($_g < $_g1->length) {
				$field = $_g1[$_g];
				++$_g;
				$o->{$field} = Reflect::field($x, $field);
				unset($field);
			}
			unset($_g1,$_g);
		}
		$o->__init_object();
		$this->addToCache($o);
		$o->{php_db_Manager::$cache_field} = Type::createEmptyInstance($this->cls);
		if(!$lock) {
			$o->__noupdate__ = true;
			;
		}
		return $o;
		unset($o);
	}
	public function make($x) {
		;
		;
	}
	public function unmake($x) {
		;
		;
	}
	public function quoteField($f) {
		$fsmall = strtolower($f);
		if($fsmall === "read" || $fsmall === "desc" || $fsmall === "out" || $fsmall === "group" || $fsmall === "version" || $fsmall === "option") {
			return ("`" . $f) . "`";
			;
		}
		return $f;
		unset($fsmall);
	}
	public function addKeys($s, $x) {
		$first = true;
		{
			$_g = 0; $_g1 = $this->table_keys;
			while($_g < $_g1->length) {
				$k = $_g1[$_g];
				++$_g;
				if($first) {
					$first = false;
					;
				}
				else {
					$s->b .= " AND ";
					;
				}
				$s->b .= $this->quoteField($k);
				$s->b .= " = ";
				$f = Reflect::field($x, $k);
				if($f === null) {
					throw new HException(("Missing key " . $k));
					;
				}
				php_db_Manager::$cnx->addValue($s, $f);
				unset($k,$f);
			}
			unset($_g1,$_g);
		}
		unset($first);
	}
	public function execute($sql) {
		return php_db_Manager::$cnx->request($sql);
		;
	}
	public function select($cond) {
		$s = new StringBuf();
		$s->b .= "SELECT * FROM ";
		$s->b .= $this->table_name;
		$s->b .= " WHERE ";
		$s->b .= $cond;
		$s->b .= php_db_Manager::$FOR_UPDATE;
		return $s->b;
		unset($s);
	}
	public function selectReadOnly($cond) {
		$s = new StringBuf();
		$s->b .= "SELECT * FROM ";
		$s->b .= $this->table_name;
		$s->b .= " WHERE ";
		$s->b .= $cond;
		return $s->b;
		unset($s);
	}
	public function object($sql, $lock) {
		$r = php_db_Manager::$cnx->request($sql)->next();
		if($r === null) {
			return null;
			;
		}
		$c = $this->getFromCache($r, $lock);
		if($c !== null) {
			return $c;
			;
		}
		$o = $this->cacheObject($r, $lock);
		$this->make($o);
		return $o;
		unset($r,$o,$c);
	}
	public function objects($sql, $lock) {
		$me = $this;
		$l = php_db_Manager::$cnx->request($sql)->results();
		$l2 = new HList();
		if(null == $l) throw new HException('null iterable');
		$»it = $l->iterator();
		while($»it->hasNext()) {
		$x = $»it->next();
		{
			$c = $this->getFromCache($x, $lock);
			if($c !== null) {
				$l2->add($c);
				;
			}
			else {
				$o = $this->cacheObject($x, $lock);
				$this->make($o);
				$l2->add($o);
				unset($o);
			}
			unset($c);
		}
		}
		return $l2;
		unset($me,$l2,$l);
	}
	public function dbClass() {
		return $this->cls;
		;
	}
	public function initRelation($o, $r) {
		$manager = $r->manager;
		$hkey = $r->key;
		$lock = $r->lock;
		if($lock === null) {
			$lock = true;
			;
		}
		if($manager === null || $manager->table_keys === null) {
			throw new HException(((("Invalid manager for relation " . $this->table_name) . ":") . $r->prop));
			;
		}
		if($manager->table_keys->length !== 1) {
			throw new HException((((("Relation " . $r->prop) . "(") . $r->key) . ") on a multiple key table"));
			;
		}
		$o->{"get_" . $r->prop} = array(new _hx_lambda(array(&$hkey, &$lock, &$manager, &$o, &$r), "php_db_Manager_4"), 'execute');
		$o->{"set_" . $r->prop} = array(new _hx_lambda(array(&$hkey, &$lock, &$manager, &$o, &$r), "php_db_Manager_5"), 'execute');
		unset($manager,$lock,$hkey);
	}
	public function makeCacheKey($x) {
		if($this->table_keys->length === 1) {
			$k = Reflect::field($x, $this->table_keys[0]);
			if($k === null) {
				throw new HException(("Missing key " . $this->table_keys[0]));
				;
			}
			return Std::string($k) . $this->table_name;
			unset($k);
		}
		$s = new StringBuf();
		{
			$_g = 0; $_g1 = $this->table_keys;
			while($_g < $_g1->length) {
				$k = $_g1[$_g];
				++$_g;
				$v = Reflect::field($x, $k);
				if($k === null) {
					throw new HException(("Missing key " . $k));
					;
				}
				$s->b .= $v;
				$s->b .= "#";
				unset($v,$k);
			}
			unset($_g1,$_g);
		}
		$s->b .= $this->table_name;
		return $s->b;
		unset($s);
	}
	public function addToCache($x) {
		php_db_Manager::$object_cache->set($this->makeCacheKey($x), $x);
		;
	}
	public function getFromCache($x, $lock) {
		$c = php_db_Manager::$object_cache->get($this->makeCacheKey($x));
		if($c !== null && $lock && $c->__noupdate__) {
			$c->__noupdate__ = false;
			;
		}
		return $c;
		unset($c);
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
	static $cnx;
	static $object_cache;
	static $cache_field = "__cache__";
	static $FOR_UPDATE = "";
	static $managers;
	static function setConnection($c) { return call_user_func_array(self::$setConnection, array($c)); }
	public static $setConnection = null;
	static function initialize() {
		;
		;
	}
	static function cleanup() {
		php_db_Manager::$object_cache = new Hash();
		;
	}
	function __toString() { return 'php.db.Manager'; }
}
php_db_Manager::$object_cache = new Hash();
php_db_Manager::$managers = new Hash();
php_db_Manager::$setConnection = array(new _hx_lambda(array(), "php_db_Manager_6"), 'execute');
;
function php_db_Manager_0(&$»this, &$classval, &$clname) {
if(_hx_field($»this->cls, "TABLE_NAME") !== null) {
	return $»this->cls->TABLE_NAME;
	;
}
else {
	return _hx_explode(".", $clname)->pop();
	;
}
}
function php_db_Manager_1(&$»this, &$classval, &$clname) {
if(_hx_field($»this->cls, "TABLE_IDS") !== null) {
	return $»this->cls->TABLE_IDS;
	;
}
else {
	return new _hx_array(array("id"));
	;
}
}
function php_db_Manager_2(&$»this, &$apriv, &$classval, &$clname) {
if($apriv === null) {
	return new _hx_array(array());
	;
}
else {
	return $apriv->copy();
	;
}
}
function php_db_Manager_3(&$»this, &$lock) {
if($lock) {
	return php_db_Manager::$FOR_UPDATE;
	;
}
else {
	return "";
	;
}
}
function php_db_Manager_4(&$hkey, &$lock, &$manager, &$o, &$r) {
{
	return $manager->get(Reflect::field($o, $hkey), $lock);
	;
}
}
function php_db_Manager_5(&$hkey, &$lock, &$manager, &$o, &$r, $f) {
{
	$o->{$hkey} = Reflect::field($f, $manager->table_keys[0]);
	return $f;
	;
}
}
function php_db_Manager_6($c) {
{
	_hx_qtype("php.db.Manager")->{"cnx"} = $c;
	if($c !== null) {
		php_db_Manager::$FOR_UPDATE = php_db_Manager_7($c);
		;
	}
	return $c;
	;
}
}
function php_db_Manager_7(&$c) {
if($c->dbName() === "MySQL") {
	return " FOR UPDATE";
	;
}
else {
	return "";
	;
}
}