<?php

class site_cms_modules_base_DefinitionsBase extends site_cms_templates_CmsTemplate {
	public function __construct() {
		if( !php_Boot::$skip_constructor ) {
		parent::__construct();
		;
	}}
	public $pagesMode;
	public function init() {
		parent::init();
		$this->pagesMode = _hx_equal($this->app->params->get("pagesMode"), "true");
		if($this->pagesMode) {
			$this->navigation->setSelected("Pages");
			;
		}
		else {
			$this->navigation->setSelected("Dataset");
			;
		}
		;
	}
	public function main() {
		$this->refreshDefinitions();
		;
	}
	public function refreshDefinitions() {
		;
		;
	}
	public function setupLeftNav() {
		$this->refreshDefinitions();
		$isAdmin = $this->user->isAdmin();
		if($this->pagesMode) {
			$this->leftNavigation->footer = "<a href=\"?request=cms.modules.base.Definitions&manage=true&pagesMode=true\">Manage Pages</a>";
			$this->leftNavigation->addSection("Pages", null);
			$pages = $this->app->getDb()->request("SELECT *, p.id as pid FROM `_pages` p, `_definitions` d WHERE p.definitionId=d.id ORDER BY d.`order`");
			$this->leftNavigation->addSection("Pages", null);
			if(null == $pages) throw new HException('null iterable');
			$»it = $pages->iterator();
			while($»it->hasNext()) {
			$page = $»it->next();
			{
				if($isAdmin) {
					$this->leftNavigation->addLink("Pages", $page->name, "cms.modules.base.DatasetItem&pagesMode=true&action=edit&id=" . $page->pid, $page->indents, false, $this->leftNavigation->editTag(("cms.modules.base.Definition&id=" . $page->id) . "&pagesMode=true"));
					;
				}
				else {
					$this->leftNavigation->addLink("Pages", $page->name, "cms.modules.base.DatasetItem&pagesMode=true&action=edit&id=" . $page->pid, $page->indents, null, null);
					;
				}
				;
			}
			}
			unset($pages);
		}
		else {
			$tables = $this->app->getDb()->request("SELECT * FROM `_definitions` d WHERE d.isPage='0' ORDER BY `order`");
			$this->leftNavigation->addSection("Datasets", null);
			$def = _hx_qtype("site.cms.modules.base.Definition");
			if(null == $tables) throw new HException('null iterable');
			$»it = $tables->iterator();
			while($»it->hasNext()) {
			$table = $»it->next();
			{
				if($table->showInMenu) {
					$name = site_cms_modules_base_DefinitionsBase_0($this, $def, $isAdmin, $table, $tables);
					if($isAdmin) {
						$this->leftNavigation->addLink("Datasets", $name, "cms.modules.base.Dataset&dataset=" . $table->id, $table->indents, false, $this->leftNavigation->editTag(("cms.modules.base.Definition&id=" . $table->id) . "&pagesMode=false"));
						;
					}
					else {
						$this->leftNavigation->addLink("Datasets", $name, "cms.modules.base.Dataset&dataset=" . $table->id, $table->indents, null, null);
						;
					}
					unset($name);
				}
				;
			}
			}
			if($this->user->isAdmin() || $this->user->isSuper()) {
				$this->leftNavigation->footer = "<a href=\"?request=cms.modules.base.Definitions&manage=true\">Manage</a>";
				;
			}
			unset($tables,$def);
		}
		unset($isAdmin);
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
	function __toString() { return 'site.cms.modules.base.DefinitionsBase'; }
}
;
function site_cms_modules_base_DefinitionsBase_0(&$»this, &$def, &$isAdmin, &$table, &$tables) {
if($table->name !== "") {
	return $table->name;
	;
}
else {
	return $table->table;
	;
}
}