<?php

class site_cms_modules_base_PageBase extends site_cms_templates_CmsTemplate {
	public function __construct() {
		if( !php_Boot::$skip_constructor ) {
		parent::__construct();
		;
	}}
	public $pages;
	public function init() {
		parent::init();
		$this->navigation->setSelected("Pages");
		;
	}
	public function setupLeftNav() {
		parent::main();
		$this->pages = $this->app->getDb()->request("SELECT *, p.id as pid FROM `_pages` p, `_definitions` d WHERE p.definitionId=d.id ORDER BY d.`order`");
		$this->leftNavigation->addSection("Pages", null);
		$isAdmin = $this->user->isAdmin();
		if(null == $this->pages) throw new HException('null iterable');
		$»it = $this->pages->iterator();
		while($»it->hasNext()) {
		$page = $»it->next();
		{
			if(!$isAdmin) {
				$this->leftNavigation->addLink("Pages", $page->name, "cms.modules.base.DatasetItem&pagesMode=true&action=edit&id=" . $page->pid, $page->indents, null, null);
				;
			}
			else {
				$this->leftNavigation->addLink("Pages", $page->name, "cms.modules.base.DatasetItem&pagesMode=true&action=edit&id=" . $page->pid, $page->indents, false, $this->leftNavigation->editTag(("cms.modules.base.Definition&id=" . $page->id) . "&pagesMode=true"));
				;
			}
			;
		}
		}
		if($isAdmin || $this->user->isSuper()) {
			$this->leftNavigation->footer = "<a href=\"?request=cms.modules.base.Definitions&manage=true&pagesMode=true\">Manage Pages</a>";
			;
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
	function __toString() { return 'site.cms.modules.base.PageBase'; }
}
