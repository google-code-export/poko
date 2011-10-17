<?php

class poko_system_Db {
	public function __construct() {
		;
		;
		;
	}
	public $cnx;
	public $lastError;
	public $lastQuery;
	public $lastAffectedRows;
	public $lastInsertId;
	public $numRows;
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
		;
	}
	public function escape($s) {
		return $this->cnx->escape($s);
		;
	}
	public function quote($s) {
		return $this->cnx->quote($s);
		;
	}
	public function query($sql) {
		$this->lastQuery = $sql;
		$result = $this->cnx->request($sql);
		return $result;
		unset($result);
	}
	public function request($sql) {
		$this->lastQuery = $sql;
		$result = $this->cnx->request($sql);
		return $result->results();
		unset($result);
	}
	public function requestSingle($sql) {
		$this->lastQuery = $sql;
		$result = $this->cnx->request($sql);
		return $result->next();
		unset($result);
	}
	public function insert($table, $data) {
		$fields = $this->cnx->request(("SHOW FIELDS from `" . $table) . "`");
		if($fields->getLength() === 0) {
			$this->lastError = "DB UPDATE ERROR";
			return false;
			;
		}
		$sql = ("INSERT INTO `" . $table) . "` ";
		$fieldNames = new _hx_array(array());
		$fieldData = new _hx_array(array());
		$»it = $fields;
		while($»it->hasNext()) {
		$field = $»it->next();
		{
			$fieldName = $field->Field;
			$variable = poko_system_Db_0($this, $data, $field, $fieldData, $fieldName, $fieldNames, $fields, $sql, $table);
			if($variable !== null) {
				$fieldNames->push($fieldName);
				$fieldData->push($variable);
				;
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
					;
				}
				$sql .= ("`" . $f) . "`";
				$c++;
				unset($f);
			}
			unset($_g);
		}
		$sql .= ") VALUES (";
		$c1 = 0;
		{
			$_g = 0;
			while($_g < $fieldData->length) {
				$d = $fieldData[$_g];
				++$_g;
				if($c1 > 0) {
					$sql .= " , ";
					;
				}
				$sql .= $this->cnx->quote($d);
				$c1++;
				unset($d);
			}
			unset($_g);
		}
		$sql .= ")";
		$this->lastQuery = $sql;
		$request = $this->cnx->request($sql);
		$this->lastInsertId = $this->cnx->lastInsertId();
		return true;
		unset($sql,$request,$fields,$fieldNames,$fieldData,$c1,$c);
	}
	public function update($table, $data, $where) {
		$sql = ("SHOW FIELDS from `" . $table) . "`";
		$fields = null;
		try {
			$fields = $this->cnx->request($sql);
			;
		}catch(Exception $»e) {
		$_ex_ = ($»e instanceof HException) ? $»e->e : $»e;
		;
		{ $e = $_ex_;
		{
			haxe_Log::trace($sql, _hx_anonymous(array("fileName" => "Db.hx", "lineNumber" => 178, "className" => "poko.system.Db", "methodName" => "update")));
			throw new HException(($e));
			;
		}}}
		if($fields->getLength() === 0) {
			$this->lastError = "DB UPDATE ERROR";
			return false;
			;
		}
		$sql1 = ("UPDATE `" . $table) . "` SET ";
		$c = 0;
		$»it = $fields;
		while($»it->hasNext()) {
		$field = $»it->next();
		{
			$fieldName = $field->Field;
			$variable = poko_system_Db_1($this, $c, $data, $e, $field, $fieldName, $fields, $sql, $sql1, $table, $where);
			if($variable !== null) {
				if($c > 0) {
					$sql1 .= " , ";
					;
				}
				$sql1 .= ((" `" . $fieldName) . "`=") . $this->cnx->quote($variable);
				$c++;
				;
			}
			unset($variable,$fieldName);
		}
		}
		$sql1 .= " WHERE " . $where;
		$this->lastQuery = $sql1;
		$this->lastAffectedRows = $this->cnx->request($sql1)->getLength();
		return true;
		unset($sql1,$sql,$fields,$e,$c);
	}
	public function delete($table, $where) {
		$sql = (("DELETE FROM `" . $table) . "` WHERE ") . $where;
		$this->lastQuery = $sql;
		$this->lastAffectedRows = $this->cnx->request($sql)->getLength();
		return true;
		unset($sql);
	}
	public function count($table, $where) {
		if($where === null) {
			$where = "";
			;
		}
		$sql = (("SELECT COUNT(*) as count FROM `" . $table) . "` WHERE ") . $where;
		$this->lastQuery = $sql;
		$result = $this->cnx->request($sql);
		return $result->next()->count;
		unset($sql,$result);
	}
	public function exists($table, $where) {
		if($where === null) {
			$where = "";
			;
		}
		return $this->count($table, $where) > 0;
		;
	}
	public function getPrimaryKey($table) {
		$primaryData = $this->request(("SHOW COLUMNS FROM `" . $table) . "` WHERE `Key`='PRI' AND `Extra`='auto_increment'");
		if($primaryData->length > 0) {
			return ($primaryData->pop()->Field);
			;
		}
		else {
			return null;
			;
		}
		unset($primaryData);
	}
	public function getTables() {
		$tables = new _hx_array(array());
		$result = $this->cnx->request("SHOW TABLES");
		$»it = $result;
		while($»it->hasNext()) {
		$row = $»it->next();
		$tables->push(Reflect::field($row, "Tables_in_" . $this->database));
		}
		return $tables;
		unset($tables,$result);
	}
	public function importFromFile($filepath) {
		$f = php_io_File::read($filepath, false);
		try {
			$temp = "";
			while(true) {
				$line = $f->readLine();
				if(_hx_substr($line, 0, 2) === "--" || _hx_char_at($line, 0) === "#" || $line === "") {
					continue;
					;
				}
				$temp .= $line;
				if(_hx_substr(trim($line), -1, 1) === ";") {
					$this->cnx->request($temp);
					$temp = "";
					;
				}
				unset($line);
			}
			unset($temp);
		}catch(Exception $»e) {
		$_ex_ = ($»e instanceof HException) ? $»e->e : $»e;
		;
		if(($e = $_ex_) instanceof haxe_io_Eof){
			$f->close();
			;
		} else throw $»e; }
		unset($f,$e);
	}
	public function export($tableStr) {
		if($tableStr === null) {
			$tableStr = "*";
			;
		}
		$output = "";
		$tables = null;
		if($tableStr === "*") {
			$tables = new _hx_array(array());
			$result = $this->cnx->request("SHOW TABLES");
			$»it = $result;
			while($»it->hasNext()) {
			$row = $»it->next();
			$tables->push(Reflect::field($row, "Tables_in_" . $this->database));
			}
			unset($result);
		}
		else {
			$tables = _hx_explode(",", $tableStr);
			{
				$_g1 = 0; $_g = $tables->length;
				while($_g1 < $_g) {
					$i = $_g1++;
					$tables[$i] = trim($tables[$i]);
					unset($i);
				}
				unset($_g1,$_g);
			}
			;
		}
		{
			$_g = 0;
			while($_g < $tables->length) {
				$table = $tables[$_g];
				++$_g;
				$result = $this->cnx->request("SELECT * FROM " . $table);
				$numFields = $result->getNFields();
				$output .= ("DROP TABLE IF EXISTS " . $table) . ";";
				$createSql = $this->cnx->request("SHOW CREATE TABLE " . $table)->next();
				$output .= ("\x0A\x0A" . Reflect::field($createSql, "Create Table")) . ";\x0A\x0A";
				{
					$_g1 = 0;
					while($_g1 < $numFields) {
						$i = $_g1++;
						$»it = $result;
						while($»it->hasNext()) {
						$row = $»it->next();
						{
							$output .= ("INSERT INTO " . $table) . " VALUES (";
							$j = 0;
							{
								$_g2 = 0; $_g3 = Reflect::fields($row);
								while($_g2 < $_g3->length) {
									$f = $_g3[$_g2];
									++$_g2;
									$value = Reflect::field($row, $f);
									$value = addslashes($value);
									$value = ereg_replace("\x0A", "\\n", $value);
									if($value !== "" && $value !== null) {
										$output .= ("\"" . $value) . "\"";
										;
									}
									else {
										$output .= "\"\"";
										;
									}
									if($j < $numFields - 1) {
										$output .= ",";
										;
									}
									$j++;
									unset($value,$f);
								}
								unset($_g3,$_g2);
							}
							$output .= ");\x0A";
							unset($j);
						}
						}
						unset($i);
					}
					unset($_g1);
				}
				$output .= "\x0A\x0A\x0A";
				unset($table,$result,$numFields,$createSql);
			}
			unset($_g);
		}
		return $output;
		unset($tables,$output);
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
	function __toString() { return 'poko.system.Db'; }
}
;
function poko_system_Db_0(&$»this, &$data, &$field, &$fieldData, &$fieldName, &$fieldNames, &$fields, &$sql, &$table) {
if(Std::is($data, _hx_qtype("Hash"))) {
	return $data->get($fieldName);
	;
}
else {
	return Reflect::field($data, $fieldName);
	;
}
}
function poko_system_Db_1(&$»this, &$c, &$data, &$e, &$field, &$fieldName, &$fields, &$sql, &$sql1, &$table, &$where) {
if(Std::is($data, _hx_qtype("Hash"))) {
	return $data->get($fieldName);
	;
}
else {
	return Reflect::field($data, $fieldName);
	;
}
}