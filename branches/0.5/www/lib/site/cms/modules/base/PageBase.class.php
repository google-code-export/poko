<?php

class site_cms_modules_base_PageBase extends site_cms_templates_CmsTemplate {
	public function __construct() {
		if( !php_Boot::$skip_constructor ) {
		parent::__construct();
	}}
	public $pages;
	public function pre() {
		$this->navigation->setSelected("Pages");
	}
	public function setupLeftNav() {
		parent::main();
		$this->pages = $this->application->db->request("SELECT *, p.id as pid FROM `_pages` p, `_definitions` d WHERE p.definitionId=d.id ORDER BY d.`order`");
		$this->leftNavigation->addSection("Pages", null);
		$»it = $this->pages->iterator();
		while($»it->hasNext()) {
		$page = $»it->next();
		{
			$this->leftNavigation->addLink("Pages", $page->name, "cms.modules.base.DatasetItem&pagesMode=true&action=edit&id=" . $page->pid, $page->indents, null);
			;
		}
		}
		if(poko_Application::$instance->user->isAdmin() || poko_Application::$instance->user->isSuper()) {
			$this->leftNavigation->footer = "<a href=\"?request=cms.modules.base.Definitions&manage=true&pagesMode=true\">Manage Pages</a>";
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
	function __toString() { return 'site.cms.modules.base.PageBase'; }
}
