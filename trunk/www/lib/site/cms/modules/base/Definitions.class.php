<?php

class site_cms_modules_base_Definitions extends site_cms_modules_base_DefinitionsBase {
	public function __construct() {
		if( !php_Boot::$skip_constructor ) {
		parent::__construct();
		$this->authenticationRequired = new _hx_array(array("cms_admin", "cms_manager"));
	}}
	public $assigned;
	public $unassigned;
	public $pageLabel;
	public function main() {
		parent::main();
		$this->process();
		$this->pageLabel = ($this->pagesMode ? "Page" : "Dataset");
		if($this->app->params->get("manage") === null) {
			$str = "< Select a Dataset";
			$str .= poko_views_renderers_Templo::parse("site/cms/modules/base/blocks/definitions.mtt", null);
			$this->setContentOutput($str);
		}
		$tables = site_cms_common_Tools::getDBTables();
		$defs = $this->app->getDb()->request("SELECT * FROM `_definitions` WHERE `isPage`='" . $this->pagesMode . "' ORDER BY `order`");
		$this->unassigned = new HList();
		$this->assigned = new HList();
		$»it = $defs->iterator();
		while($»it->hasNext()) {
		$def = $»it->next();
		{
			$tables->remove($def->table);
			$this->assigned->add($def);
			;
		}
		}
		$»it2 = $tables->iterator();
		while($»it2->hasNext()) {
		$table = $»it2->next();
		$this->unassigned->add(_hx_anonymous(array("name" => $table)));
		}
		$this->setupLeftNav();
	}
	public function process() {
		switch($this->app->params->get("action")) {
		case "add":{
			$nextId = $this->app->getDb()->requestSingle("SELECT MAX(`order`) as 'order' FROM `_definitions` WHERE `isPage`='" . $this->pagesMode . "'")->order;
			$nextId++;
			$this->app->getDb()->insert("_definitions", _hx_anonymous(array("name" => $this->app->params->get("name"), "isPage" => $this->pagesMode, "order" => $nextId)));
			$defId = $this->app->getDb()->cnx->lastInsertId();
			if($this->pagesMode) {
				$this->app->getDb()->insert("_pages", _hx_anonymous(array("name" => $this->app->params->get("name"), "definitionId" => $defId)));
			}
		}break;
		case "update":{
			$deleteList = php_Web::getParamValues("delete");
			if($deleteList !== null) {
				{
					$_g1 = 0; $_g = $deleteList->length;
					while($_g1 < $_g) {
						$i = $_g1++;
						if($deleteList[$i] !== null) {
							$defId2 = $this->app->getDb()->cnx->quote(Std::string($i));
							$this->app->getDb()->delete("_definitions", "`id`=" . $defId2);
							$this->app->getDb()->delete("_pages", "`definitionId`=" . $defId2);
						}
						unset($i,$defId2);
					}
				}
			}
			$c = 0;
			{
				$_g2 = 0; $_g12 = php_Web::getParamValues("order");
				while($_g2 < $_g12->length) {
					$val = $_g12[$_g2];
					++$_g2;
					if($val !== null) {
						$this->app->getDb()->update("_definitions", _hx_anonymous(array("order" => $val)), "`id`=" . $c);
					}
					$c++;
					unset($val);
				}
			}
			$c = 0;
			$result = $this->app->getDb()->request("SELECT `id` as 'id' from `_definitions` WHERE `isPage`='" . $this->pagesMode . "' ORDER BY `order`");
			$»it = $result->iterator();
			while($»it->hasNext()) {
			$item = $»it->next();
			$this->app->getDb()->update("_definitions", _hx_anonymous(array("order" => ++$c)), "`id`='" . $item->id . "'");
			}
		}break;
		case "define":{
			$nextId2 = $this->app->getDb()->requestSingle("SELECT MAX(`order`) as 'order' FROM `_definitions` WHERE `isPage`='" . $this->pagesMode . "'")->order;
			$nextId2++;
			$name = $this->app->params->get("define");
			$this->app->getDb()->insert("_definitions", _hx_anonymous(array("name" => $name, "table" => $name, "isPage" => false, "order" => $nextId2)));
		}break;
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
	function __toString() { return 'site.cms.modules.base.Definitions'; }
}
