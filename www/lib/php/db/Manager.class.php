<?php

class php_db_Manager {
	public function __construct($classval) {
		if( !php_Boot::$skip_constructor ) {
		$this->cls = $classval;
		$clname = Type::getClassName($this->cls);
		$this->table_name = $this->quoteField(((_hx_field($this->cls, "TABLE_NAME") !== null) ? $this->cls->TABLE_NAME : _hx_explode(".", $clname)->pop()));
		$this->table_keys = (_hx_field($this->cls, "TABLE_IDS") !== null ? $this->cls->TABLE_IDS : new _hx_array(array("id")));
		$apriv = $this->cls->PRIVATE_FIELDS;
		$apriv = ($apriv === null ? new _hx_array(array()) : $apriv->copy());
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
			}
			$scls = Type::getSuperClass($scls);
			unset($remove,$_g1,$_g);
		}
		{
			$_g2 = 0;
			while($_g2 < $instance_fields->length) {
				$f = $instance_fields[$_g2];
				++$_g2;
				$isfield = !Reflect::isFunction(Reflect::field($stub, $f));
				if($isfield) {
					$_g12 = 0;
					while($_g12 < $apriv->length) {
						$f2 = $apriv[$_g12];
						++$_g12;
						if($f == $f2) {
							$isfield = false;
							break;
						}
						unset($f2);
					}
				}
				if($isfield) {
					$this->table_fields->add($f);
				}
				unset($isfield,$f2,$f,$_g12);
			}
		}
		php_db_Manager::$managers->set($clname, $this);
		$rl = null;
		try {
			$rl = $this->cls->RELATIONS();
		}catch(Exception $»e) {
		$_ex_ = ($»e instanceof HException) ? $»e->e : $»e;
		;
		{ $e = $_ex_;
		{
			return;
		}}}
		{
			$_g3 = 0;
			while($_g3 < $rl->length) {
				$r = $rl[$_g3];
				++$_g3;
				$this->table_fields->remove($r->prop);
				$this->table_fields->remove("get_" . $r->prop);
				$this->table_fields->remove("set_" . $r->prop);
				$this->table_fields->remove($r->key);
				$this->table_fields->add($r->key);
				unset($r);
			}
		}
	}}
	public $table_name;
	public $table_fields;
	public $table_keys;
	public $cls;
	public function get($id, $lock) {
		if($lock === null) {
			$lock = true;
		}
		if($this->table_keys->length !== 1) {
			throw new HException("Invalid number of keys");
		}
		if($id === null) {
			return null;
		}
		$x = php_db_Manager::$object_cache->get($id . $this->table_name);
		if($x !== null && (!$lock || !$x->__noupdate__)) {
			return $x;
		}
		$s = new StringBuf();
		$s->b .= "SELECT * FROM ";
		$s->b .= $this->table_name;
		$s->b .= " WHERE ";
		$s->b .= $this->quoteField($this->table_keys[0]);
		$s->b .= " = ";
		$this->addQuote($s, $id);
		if($lock) {
			$s->b .= php_db_Manager::$FOR_UPDATE;
		}
		return $this->object($s->b, $lock);
	}
	public function getWithKeys($keys, $lock) {
		if($lock === null) {
			$lock = true;
		}
		$x = $this->getFromCache($keys, false);
		if($x !== null && (!$lock || !$x->__noupdate__)) {
			return $x;
		}
		$s = new StringBuf();
		$s->b .= "SELECT * FROM ";
		$s->b .= $this->table_name;
		$s->b .= " WHERE ";
		$this->addKeys($s, $keys);
		if($lock) {
			$s->b .= php_db_Manager::$FOR_UPDATE;
		}
		return $this->object($s->b, $lock);
	}
	public function delete($x) {
		$s = new StringBuf();
		$s->b .= "DELETE FROM ";
		$s->b .= $this->table_name;
		$s->b .= " WHERE ";
		$this->addCondition($s, $x);
		$this->execute($s->b);
	}
	public function search($x, $lock) {
		if($lock === null) {
			$lock = true;
		}
		$s = new StringBuf();
		$s->b .= "SELECT * FROM ";
		$s->b .= $this->table_name;
		$s->b .= " WHERE ";
		$this->addCondition($s, $x);
		if($lock) {
			$s->b .= php_db_Manager::$FOR_UPDATE;
		}
		return $this->objects($s->b, $lock);
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
				}
				else {
					$s->b .= " AND ";
				}
				$s->b .= $this->quoteField($f);
				$d = Reflect::field($x, $f);
				if($d === null) {
					$s->b .= " IS NULL";
				}
				else {
					$s->b .= " = ";
					$this->addQuote($s, $d);
				}
				unset($f,$d);
			}
		}
		if($first) {
			$s->b .= "1";
		}
	}
	public function all($lock) {
		if($lock === null) {
			$lock = true;
		}
		return $this->objects("SELECT * FROM " . $this->table_name . ($lock ? php_db_Manager::$FOR_UPDATE : ""), $lock);
	}
	public function count($x) {
		$s = new StringBuf();
		$s->b .= "SELECT COUNT(*) FROM ";
		$s->b .= $this->table_name;
		$s->b .= " WHERE ";
		$this->addCondition($s, $x);
		return $this->execute($s->b)->getIntResult(0);
	}
	public function quote($s) {
		return php_db_Manager::$cnx->quote($s);
	}
	public function result($sql) {
		return php_db_Manager::$cnx->request($sql)->next();
	}
	public function results($sql) {
		return php_db_Manager::$cnx->request($sql)->results();
	}
	public function doInsert($x) {
		$this->unmake($x);
		$s = new StringBuf();
		$fields = new HList();
		$values = new HList();
		$»it = $this->table_fields->iterator();
		while($»it->hasNext()) {
		$f = $»it->next();
		{
			$v = Reflect::field($x, $f);
			if($v !== null) {
				$fields->add($this->quoteField($f));
				$values->add($v);
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
		$»it2 = $values->iterator();
		while($»it2->hasNext()) {
		$v2 = $»it2->next();
		{
			if($first) {
				$first = false;
			}
			else {
				$s->b .= ", ";
			}
			$this->addQuote($s, $v2);
			;
		}
		}
		$s->b .= ")";
		$this->execute($s->b);
		if($this->table_keys->length === 1 && Reflect::field($x, $this->table_keys[0]) === null) {
			$x->{$this->table_keys[0]} = php_db_Manager::$cnx->lastInsertId();
		}
		$this->addToCache($x);
	}
	public function doUpdate($x) {
		$this->unmake($x);
		$s = new StringBuf();
		$s->b .= "UPDATE ";
		$s->b .= $this->table_name;
		$s->b .= " SET ";
		$cache = Reflect::field($x, php_db_Manager::$cache_field);
		$mod = false;
		$»it = $this->table_fields->iterator();
		while($»it->hasNext()) {
		$f = $»it->next();
		{
			$v = Reflect::field($x, $f);
			$vc = Reflect::field($cache, $f);
			if(!_hx_equal($v, $vc)) {
				if($mod) {
					$s->b .= ", ";
				}
				else {
					$mod = true;
				}
				$s->b .= $this->quoteField($f);
				$s->b .= " = ";
				$this->addQuote($s, $v);
				$cache->{$f} = $v;
			}
			unset($vc,$v);
		}
		}
		if(!$mod) {
			return;
		}
		$s->b .= " WHERE ";
		$this->addKeys($s, $x);
		$this->execute($s->b);
	}
	public function doDelete($x) {
		$s = new StringBuf();
		$s->b .= "DELETE FROM ";
		$s->b .= $this->table_name;
		$s->b .= " WHERE ";
		$this->addKeys($s, $x);
		$this->execute($s->b);
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
		}
		{
			$_g2 = 0; $_g12 = Reflect::fields($i2);
			while($_g2 < $_g12->length) {
				$f2 = $_g12[$_g2];
				++$_g2;
				$i->{$f2} = Reflect::field($i2, $f2);
				unset($f2);
			}
		}
		$i->{php_db_Manager::$cache_field} = Reflect::field($i2, php_db_Manager::$cache_field);
		$this->addToCache($i);
	}
	public function objectToString($it) {
		$s = new StringBuf();
		$s->b .= $this->table_name;
		if($this->table_keys->length === 1) {
			$s->b .= "#";
			$s->b .= Reflect::field($it, $this->table_keys[0]);
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
					}
					else {
						$s->b .= ",";
					}
					$s->b .= $this->quoteField($f);
					$s->b .= ":";
					$s->b .= Reflect::field($it, $f);
					unset($f);
				}
			}
			$s->b .= ")";
		}
		return $s->b;
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
		}
		$o->__init_object();
		$this->addToCache($o);
		$o->{php_db_Manager::$cache_field} = Type::createEmptyInstance($this->cls);
		if(!$lock) {
			$o->__noupdate__ = true;
		}
		return $o;
	}
	public function make($x) {
		;
	}
	public function unmake($x) {
		;
	}
	public function quoteField($f) {
		$fsmall = strtolower($f);
		if($fsmall == "read" || $fsmall == "desc" || $fsmall == "out" || $fsmall == "group" || $fsmall == "version" || $fsmall == "option") {
			return "`" . $f . "`";
		}
		return $f;
	}
	public function addQuote($s, $v) {
		if(is_int($v) || is_null($v)) {
			$s->b .= $v;
		}
		else {
			if(is_bool($v)) {
				$s->b .= ($v ? 1 : 0);
			}
			else {
				$s->b .= php_db_Manager::$cnx->quote(Std::string($v));
			}
		}
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
				}
				else {
					$s->b .= " AND ";
				}
				$s->b .= $this->quoteField($k);
				$s->b .= " = ";
				$f = Reflect::field($x, $k);
				if($f === null) {
					throw new HException(("Missing key " . $k));
				}
				$this->addQuote($s, $f);
				unset($k,$f);
			}
		}
	}
	public function execute($sql) {
		return php_db_Manager::$cnx->request($sql);
	}
	public function select($cond) {
		$s = new StringBuf();
		$s->b .= "SELECT * FROM ";
		$s->b .= $this->table_name;
		$s->b .= " WHERE ";
		$s->b .= $cond;
		$s->b .= php_db_Manager::$FOR_UPDATE;
		return $s->b;
	}
	public function selectReadOnly($cond) {
		$s = new StringBuf();
		$s->b .= "SELECT * FROM ";
		$s->b .= $this->table_name;
		$s->b .= " WHERE ";
		$s->b .= $cond;
		return $s->b;
	}
	public function object($sql, $lock) {
		$r = php_db_Manager::$cnx->request($sql)->next();
		if($r === null) {
			return null;
		}
		$c = $this->getFromCache($r, $lock);
		if($c !== null) {
			return $c;
		}
		$o = $this->cacheObject($r, $lock);
		$this->make($o);
		return $o;
	}
	public function objects($sql, $lock) {
		$me = $this;
		$l = php_db_Manager::$cnx->request($sql)->results();
		$l2 = new HList();
		$»it = $l->iterator();
		while($»it->hasNext()) {
		$x = $»it->next();
		{
			$c = $this->getFromCache($x, $lock);
			if($c !== null) {
				$l2->add($c);
			}
			else {
				$o = $this->cacheObject($x, $lock);
				$this->make($o);
				$l2->add($o);
			}
			unset($o,$c);
		}
		}
		return $l2;
	}
	public function dbClass() {
		return $this->cls;
	}
	public function initRelation($o, $r) {
		$manager = $r->manager;
		$hkey = $r->key;
		$lock = $r->lock;
		if($lock === null) {
			$lock = true;
		}
		if($manager === null || $manager->table_keys === null) {
			throw new HException(("Invalid manager for relation " . $this->table_name . ":" . $r->prop));
		}
		if($manager->table_keys->length !== 1) {
			throw new HException(("Relation " . $r->prop . "(" . $r->key . ") on a multiple key table"));
		}
		$o->{"get_" . $r->prop} = array(new _hx_lambda(array("hkey" => &$hkey, "lock" => &$lock, "manager" => &$manager, "o" => &$o, "r" => &$r), null, array(), "{
			return \$manager->get(Reflect::field(\$o, \$hkey), \$lock);
		}"), 'execute0');
		$o->{"set_" . $r->prop} = array(new _hx_lambda(array("hkey" => &$hkey, "lock" => &$lock, "manager" => &$manager, "o" => &$o, "r" => &$r), null, array('f'), "{
			\$o->{\$hkey} = Reflect::field(\$f, \$manager->table_keys[0]);
			return \$f;
		}"), 'execute1');
	}
	public function makeCacheKey($x) {
		if($this->table_keys->length === 1) {
			$k = Reflect::field($x, $this->table_keys[0]);
			if($k === null) {
				throw new HException(("Missing key " . $this->table_keys[0]));
			}
			return Std::string($k) . $this->table_name;
		}
		$s = new StringBuf();
		{
			$_g = 0; $_g1 = $this->table_keys;
			while($_g < $_g1->length) {
				$k2 = $_g1[$_g];
				++$_g;
				$v = Reflect::field($x, $k2);
				if($k2 === null) {
					throw new HException(("Missing key " . $k2));
				}
				$s->b .= $v;
				$s->b .= "#";
				unset($v,$k2);
			}
		}
		$s->b .= $this->table_name;
		return $s->b;
	}
	public function addToCache($x) {
		php_db_Manager::$object_cache->set($this->makeCacheKey($x), $x);
	}
	public function getFromCache($x, $lock) {
		$c = php_db_Manager::$object_cache->get($this->makeCacheKey($x));
		if($c !== null && $lock && $c->__noupdate__) {
			$c->__noupdate__ = false;
		}
		return $c;
	}
	public function __call($m, $a) {
		if(isset($this->$m) && is_callable($this->$m))
			return call_user_func_array($this->$m, $a);
		else if(isset($this->»dynamics[$m]) && is_callable($this->»dynamics[$m]))
			return call_user_func_array($this->»dynamics[$m], $a);
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
	}
	static function cleanup() {
		php_db_Manager::$object_cache = new Hash();
	}
	function __toString() { return 'php.db.Manager'; }
}
php_db_Manager::$object_cache = new Hash();
php_db_Manager::$managers = new Hash();
php_db_Manager::$setConnection = array(new _hx_lambda(array(), null, array('c'), "{
	_hx_qtype(\"php.db.Manager\")->{\"cnx\"} = \$c;
	if(\$c !== null) {
		php_db_Manager::\$FOR_UPDATE = (\$c->dbName() == \"MySQL\" ? \" FOR UPDATE\" : \"\");
	}
	return \$c;
}"), 'execute1');
