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
		if($this->application->params->get("manage") === null) {
			$str = "< Select a Dataset";
			$str .= poko_ViewContext::parse("site/cms/modules/base/blocks/definitions.mtt", null);
			$this->setContentOutput($str);
		}
		$tables = site_cms_common_Tools::getDBTables();
		$defs = $this->application->db->request("SELECT * FROM `_definitions` WHERE `isPage`='" . $this->pagesMode . "' ORDER BY `order`");
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
		switch($this->application->params->get("action")) {
		case "add":{
			$nextId = $this->application->db->requestSingle("SELECT MAX(`order`) as 'order' FROM `_definitions` WHERE `isPage`='" . $this->pagesMode . "'")->order;
			$nextId++;
			$this->application->db->insert("_definitions", _hx_anonymous(array("name" => $this->application->params->get("name"), "isPage" => $this->pagesMode, "order" => $nextId)));
			$defId = $this->application->db->cnx->lastInsertId();
			if($this->pagesMode) {
				$this->application->db->insert("_pages", _hx_anonymous(array("name" => $this->application->params->get("name"), "definitionId" => $defId)));
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
							$defId2 = $this->application->db->cnx->quote(Std::string($i));
							$this->application->db->delete("_definitions", "`id`=" . $defId2);
							$this->application->db->delete("_pages", "`definitionId`=" . $defId2);
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
						$this->application->db->update("_definitions", _hx_anonymous(array("order" => $val)), "`id`=" . $c);
					}
					$c++;
					unset($val);
				}
			}
			$c = 0;
			$result = $this->application->db->request("SELECT `id` as 'id' from `_definitions` WHERE `isPage`='" . $this->pagesMode . "' ORDER BY `order`");
			$»it = $result->iterator();
			while($»it->hasNext()) {
			$item = $»it->next();
			$this->application->db->update("_definitions", _hx_anonymous(array("order" => ++$c)), "`id`='" . $item->id . "'");
			}
		}break;
		case "define":{
			$nextId2 = $this->application->db->requestSingle("SELECT MAX(`order`) as 'order' FROM `_definitions` WHERE `isPage`='" . $this->pagesMode . "'")->order;
			$nextId2++;
			$name = $this->application->params->get("define");
			$this->application->db->insert("_definitions", _hx_anonymous(array("name" => $name, "table" => $name, "isPage" => false, "order" => $nextId2)));
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
