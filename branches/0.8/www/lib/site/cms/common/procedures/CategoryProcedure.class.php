<?php

class site_cms_common_procedures_CategoryProcedure extends site_cms_common_Procedure {
	public function __construct() { if( !php_Boot::$skip_constructor ) {
		parent::__construct();
		;
	}}
	public function postCreate($table, $data, $id) {
		return true;
		;
	}
	public function postUpdate($table, $oldData, $newData) {
		return true;
		;
	}
	public function postDelete($table, $data) {
		return true;
		;
	}
	public function postDuplicate($table, $oldData, $newData) {
		haxe_Log::trace("CategoryProcedure: postDuplicate", _hx_anonymous(array("fileName" => "CategoryProcedure.hx", "lineNumber" => 32, "className" => "site.cms.common.procedures.CategoryProcedure", "methodName" => "postDuplicate")));
		haxe_Log::trace($table, _hx_anonymous(array("fileName" => "CategoryProcedure.hx", "lineNumber" => 33, "className" => "site.cms.common.procedures.CategoryProcedure", "methodName" => "postDuplicate")));
		haxe_Log::trace($oldData, _hx_anonymous(array("fileName" => "CategoryProcedure.hx", "lineNumber" => 34, "className" => "site.cms.common.procedures.CategoryProcedure", "methodName" => "postDuplicate")));
		haxe_Log::trace($newData, _hx_anonymous(array("fileName" => "CategoryProcedure.hx", "lineNumber" => 35, "className" => "site.cms.common.procedures.CategoryProcedure", "methodName" => "postDuplicate")));
		return true;
		;
	}
	function __toString() { return 'site.cms.common.procedures.CategoryProcedure'; }
}
