<?php

class site_cms_modules_base_DefinitionsBase extends site_cms_templates_CmsTemplate {
	public function __construct() {
		if( !php_Boot::$skip_constructor ) {
		parent::__construct();
	}}
	public $pagesMode;
	public function pre() {
		parent::pre();
		$this->pagesMode = _hx_equal($this->application->params->get("pagesMode"), "true");
		if($this->pagesMode) {
			$this->navigation->setSelected("Pages");
		}
		else {
			$this->navigation->setSelected("Dataset");
		}
	}
	public function main() {
		$this->refreshDefinitions();
	}
	public function refreshDefinitions() {
		;
	}
	public function setupLeftNav() {
		$this->refreshDefinitions();
		if($this->pagesMode) {
			$this->leftNavigation->footer = "<a href=\"?request=cms.modules.base.Definitions&manage=true&pagesMode=true\">Manage Pages</a>";
			$this->leftNavigation->addSection("Pages", null);
			$pages = $this->application->db->request("SELECT *, p.id as pid FROM `_pages` p, `_definitions` d WHERE p.definitionId=d.id ORDER BY d.`order`");
			$this->leftNavigation->addSection("Pages", null);
			$»it = $pages->iterator();
			while($»it->hasNext()) {
			$page = $»it->next();
			$this->leftNavigation->addLink("Pages", $page->name, "cms.modules.base.DatasetItem&pagesMode=true&action=edit&id=" . $page->pid, $page->indents, null);
			}
		}
		else {
			$tables = $this->application->db->request("SELECT * FROM `_definitions` d WHERE d.isPage='0' ORDER BY `order`");
			$this->leftNavigation->addSection("Datasets", null);
			$def = _hx_qtype("site.cms.modules.base.Definition");
			$»it2 = $tables->iterator();
			while($»it2->hasNext()) {
			$table = $»it2->next();
			{
				if($table->showInMenu) {
					$name = ($table->name != "" ? $table->name : $table->table);
					$this->leftNavigation->addLink("Datasets", $name, "cms.modules.base.Dataset&dataset=" . $table->id, $table->indents, null);
				}
				unset($name);
			}
			}
			if($this->application->user->isAdmin() || $this->application->user->isSuper()) {
				$this->leftNavigation->footer = "<a href=\"?request=cms.modules.base.Definitions&manage=true\">Manage</a>";
			}
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
	function __toString() { return 'site.cms.modules.base.DefinitionsBase'; }
}
