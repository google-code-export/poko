<?php

class poko_system_Db {
	public function __construct() {
		;
		;
	}
	public $cnx;
	public $lastError;
	public $lastQuery;
	public $lastAffectedRows;
	public $host;
	public $database;
	public $user;
	public $password;
	public $port;
	public $socket;
	public function connect($host, $database, $user, $password, $port, $socket) {
		$this->cnx = php_db_Mysql::connect(_hx_anonymous(array("host" => $host, "port" => $port, "user" => $user, "pass" => $password, "socket" => $socket, "database" => $database)));
		$this->host = $host;
		$this->database = $database;
		$this->user = $user;
		$this->password = $password;
		$this->port = $port;
		$this->socket = $socket;
	}
	public function query($sql) {
		$this->lastQuery = $sql;
		$result = $this->cnx->request($sql);
		return $result;
	}
	public function request($sql) {
		$this->lastQuery = $sql;
		$result = $this->cnx->request($sql);
		return $result->results();
	}
	public function requestSingle($sql) {
		$this->lastQuery = $sql;
		$result = $this->cnx->request($sql);
		return $result->next();
	}
	public function insert($table, $data) {
		$fields = $this->cnx->request("SHOW FIELDS from `" . $table . "`");
		if($fields->getLength() === 0) {
			$this->lastError = "DB UPDATE ERROR";
			return false;
		}
		$sql = "INSERT INTO `" . $table . "` ";
		$fieldNames = new _hx_array(array());
		$fieldData = new _hx_array(array());
		$»it = $fields;
		while($»it->hasNext()) {
		$field = $»it->next();
		{
			$fieldName = $field->Field;
			$variable = ((Std::is($data, _hx_qtype("Hash"))) ? $data->get($fieldName) : Reflect::field($data, $fieldName));
			if($variable !== null) {
				$fieldNames->push($fieldName);
				$fieldData->push($variable);
			}
			unset($variable,$fieldName);
		}
		}
		$sql .= "(";
		$c = 0;
		{
			$_g = 0;
			while($_g < $fieldNames->length) {
				$f = $fieldNames[$_g];
				++$_g;
				if($c > 0) {
					$sql .= " , ";
				}
				$sql .= "`" . $f . "`";
				$c++;
				unset($f);
			}
		}
		$sql .= ") VALUES (";
		$c1 = 0;
		{
			$_g2 = 0;
			while($_g2 < $fieldData->length) {
				$d = $fieldData[$_g2];
				++$_g2;
				if($c1 > 0) {
					$sql .= " , ";
				}
				$sql .= $this->cnx->quote($d);
				$c1++;
				unset($d);
			}
		}
		$sql .= ")";
		$this->lastQuery = $sql;
		$request = $this->cnx->request($sql);
		$this->lastAffectedRows = $request->getLength();
		return true;
	}
	public function update($table, $data, $where) {
		$sql = "SHOW FIELDS from `" . $table . "`";
		$fields = null;
		try {
			$fields = $this->cnx->request($sql);
		}catch(Exception $»e) {
		$_ex_ = ($»e instanceof HException) ? $»e->e : $»e;
		;
		{ $e = $_ex_;
		{
			haxe_Log::trace($sql, _hx_anonymous(array("fileName" => "Db.hx", "lineNumber" => 160, "className" => "poko.system.Db", "methodName" => "update")));
			throw new HException(($e));
		}}}
		if($fields->getLength() === 0) {
			$this->lastError = "DB UPDATE ERROR";
			return false;
		}
		$sql1 = "UPDATE `" . $table . "` SET ";
		$c = 0;
		$»it = $fields;
		while($»it->hasNext()) {
		$field = $»it->next();
		{
			$fieldName = $field->Field;
			$variable = ((Std::is($data, _hx_qtype("Hash"))) ? $data->get($fieldName) : Reflect::field($data, $fieldName));
			if($variable !== null) {
				if($c > 0) {
					$sql1 .= " , ";
				}
				$sql1 .= " `" . $fieldName . "`=" . $this->cnx->quote($variable);
				$c++;
			}
			unset($variable,$fieldName);
		}
		}
		$sql1 .= " WHERE " . $where;
		$this->lastQuery = $sql1;
		$this->lastAffectedRows = $this->cnx->request($sql1)->getLength();
		return true;
	}
	public function delete($table, $where) {
		$sql = "DELETE FROM `" . $table . "` WHERE " . $where;
		$this->lastQuery = $sql;
		$this->lastAffectedRows = $this->cnx->request($sql)->getLength();
		return true;
	}
	public function count($table, $where) {
		if($where === null) {
			$where = "";
		}
		$sql = "SELECT COUNT(*) as count FROM `" . $table . "` WHERE " . $where;
		$this->lastQuery = $sql;
		$result = $this->cnx->request($sql);
		return $result->next()->count;
	}
	public function exists($table, $where) {
		if($where === null) {
			$where = "";
		}
		return $this->count($table, $where) > 0;
	}
	public function getPrimaryKey($table) {
		$primaryData = $this->request("SHOW COLUMNS FROM `" . $table . "` WHERE `Key`='PRI' AND `Extra`='auto_increment'");
		if($primaryData->length > 0) {
			return ($primaryData->pop()->Field);
		}
		else {
			return null;
		}
	}
	public function __call($m, $a) {
		if(isset($this->$m) && is_callable($this->$m))
			return call_user_func_array($this->$m, $a);
		else if(isset($this->»dynamics[$m]) && is_callable($this->»dynamics[$m]))
			return call_user_func_array($this->»dynamics[$m], $a);
		else
			throw new HException('Unable to call «'.$m.'»');
	}
	function __toString() { return 'poko.system.Db'; }
}
