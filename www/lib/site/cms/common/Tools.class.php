<?php

class site_cms_common_Tools {
	public function __construct() { ;
		;
	}
	static function getDBTables() {
		$application = poko_Poko::$instance;
		$tables = poko_Poko::$instance->getDb()->request("SHOW TABLES");
		$app = $application;
		$tables = Lambda::map($tables, array(new _hx_lambda(array("app" => &$app, "application" => &$application, "tables" => &$tables), null, array('table'), "{
			return Reflect::field(\$table, \"Tables_in_\" . \$app->getDb()->database);
		}"), 'execute1'));
		$tables = Lambda::filter($tables, array(new _hx_lambda(array("app" => &$app, "application" => &$application, "tables" => &$tables), null, array('table'), "{
			return _hx_string_call(\$table, \"substr\", array(0, 1)) != \"_\";
		}"), 'execute1'));
		return $tables;
	}
	function __toString() { return 'site.cms.common.Tools'; }
}
