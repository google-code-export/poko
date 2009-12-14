<?php

interface php_db_Connection {
	function request($s);
	function close();
	function escape($s);
	function quote($s);
	function lastInsertId();
	function dbName();
	function startTransaction();
	function commit();
	function rollback();
}
