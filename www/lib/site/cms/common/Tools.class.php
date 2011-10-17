<?php

class site_cms_common_Tools {
	public function __construct() { ;
		;
		;
	}
	static function getDBTables() {
		$application = poko_Poko::$instance;
		$tables = poko_Poko::$instance->getDb()->request("SHOW TABLES");
		$app = $application;
		$tables = Lambda::map($tables, array(new _hx_lambda(array(&$app, &$application, &$tables), "site_cms_common_Tools_0"), 'execute'));
		$tables = Lambda::filter($tables, array(new _hx_lambda(array(&$app, &$application, &$tables), "site_cms_common_Tools_1"), 'execute'));
		return $tables;
		unset($tables,$application,$app);
	}
	function __toString() { return 'site.cms.common.Tools'; }
}
;
function site_cms_common_Tools_0(&$app, &$application, &$tables, $table) {
{
	return Reflect::field($table, "Tables_in_" . $app->getDb()->database);
	;
}
}
function site_cms_common_Tools_1(&$app, &$application, &$tables, $table) {
{
	return _hx_string_call($table, "substr", array(0, 1)) !== "_";
	;
}
}