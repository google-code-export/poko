<?php

class site_cms_modules_base_Definitions extends site_cms_modules_base_DefinitionsBase {
	public function __construct() {
		if( !php_Boot::$skip_constructor ) {
		parent::__construct();
		$this->authenticationRequired = new _hx_array(array("cms_admin", "cms_manager"));
		;
	}}
	public $assigned;
	public $unassigned;
	public $pageLabel;
	public function main() {
		parent::main();
		$this->process();
		$this->pageLabel = site_cms_modules_base_Definitions_0($this);
		if($this->app->params->get("manage") === null) {
			$str = "< Select a Dataset";
			$str .= poko_views_renderers_Templo::parse("site/cms/modules/base/blocks/definitions.mtt", null);
			$this->setContentOutput($str);
			unset($str);
		}
		$tables = site_cms_common_Tools::getDBTables();
		$defs = $this->app->getDb()->request(("SELECT * FROM `_definitions` WHERE `isPage`='" . $this->pagesMode) . "' ORDER BY `order`");
		$this->unassigned = new HList();
		$this->assigned = new HList();
		if(null == $defs) throw new HException('null iterable');
		$»it = $defs->iterator();
		while($»it->hasNext()) {
		$def = $»it->next();
		{
			$tables->remove($def->table);
			$this->assigned->add($def);
			;
		}
		}
		if(null == $tables) throw new HException('null iterable');
		$»it = $tables->iterator();
		while($»it->hasNext()) {
		$table = $»it->next();
		$this->unassigned->add(_hx_anonymous(array("name" => $table)));
		}
		$this->setupLeftNav();
		unset($tables,$defs);
	}
	public function process() {
		switch($this->app->params->get("action")) {
		case "add":{
			$nextId = $this->app->getDb()->requestSingle(("SELECT MAX(`order`) as 'order' FROM `_definitions` WHERE `isPage`='" . $this->pagesMode) . "'")->order;
			$nextId++;
			$this->app->getDb()->insert("_definitions", _hx_anonymous(array("name" => $this->app->params->get("name"), "isPage" => $this->pagesMode, "order" => $nextId, "postCreateSql" => "", "postEditSql" => "", "postDeleteSql" => "", "postProcedure" => "", "help" => "", "help_list" => "", "autoOrdering" => "ASC")));
			$defId = $this->app->getDb()->lastInsertId;
			if($this->pagesMode) {
				$this->app->getDb()->insert("_pages", _hx_anonymous(array("name" => $this->app->params->get("name"), "definitionId" => $defId)));
				;
			}
			unset($nextId,$defId);
		}break;
		case "update":{
			$deleteList = php_Web::getParamValues("delete");
			if($deleteList !== null) {
				{
					$_g1 = 0; $_g = $deleteList->length;
					while($_g1 < $_g) {
						$i = $_g1++;
						if($deleteList[$i] !== null) {
							$defId = $this->app->getDb()->quote(Std::string($i));
							$this->app->getDb()->delete("_definitions", "`id`=" . $defId);
							$this->app->getDb()->delete("_pages", "`definitionId`=" . $defId);
							unset($defId);
						}
						unset($i);
					}
					unset($_g1,$_g);
				}
				;
			}
			$c = 0;
			{
				$_g = 0; $_g1 = php_Web::getParamValues("order");
				while($_g < $_g1->length) {
					$val = $_g1[$_g];
					++$_g;
					if($val !== null) {
						$this->app->getDb()->update("_definitions", _hx_anonymous(array("order" => $val)), "`id`=" . $c);
						;
					}
					$c++;
					unset($val);
				}
				unset($_g1,$_g);
			}
			$c = 0;
			$result = $this->app->getDb()->request(("SELECT `id` as 'id' from `_definitions` WHERE `isPage`='" . $this->pagesMode) . "' ORDER BY `order`");
			if(null == $result) throw new HException('null iterable');
			$»it = $result->iterator();
			while($»it->hasNext()) {
			$item = $»it->next();
			$this->app->getDb()->update("_definitions", _hx_anonymous(array("order" => ++$c)), ("`id`='" . $item->id) . "'");
			}
			unset($result,$deleteList,$c);
		}break;
		case "define":{
			$nextId = $this->app->getDb()->requestSingle(("SELECT MAX(`order`) as 'order' FROM `_definitions` WHERE `isPage`='" . $this->pagesMode) . "'")->order;
			$nextId++;
			$name = $this->app->params->get("define");
			$this->app->getDb()->insert("_definitions", _hx_anonymous(array("name" => $name, "table" => $name, "isPage" => false, "order" => $nextId)));
			unset($nextId,$name);
		}break;
		}
		;
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
	function __toString() { return 'site.cms.modules.base.Definitions'; }
}
;
function site_cms_modules_base_Definitions_0(&$»this) {
if($»this->pagesMode) {
	return "Page";
	;
}
else {
	return "Dataset";
	;
}
}