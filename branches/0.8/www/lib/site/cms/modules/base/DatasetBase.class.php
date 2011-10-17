<?php

class site_cms_modules_base_DatasetBase extends site_cms_templates_CmsTemplate {
	public function __construct() {
		if( !php_Boot::$skip_constructor ) {
		parent::__construct();
		;
	}}
	public $pagesMode;
	public $siteMode;
	public $linkMode;
	public $siteView;
	public $siteViewHidden;
	public $siteViewSerialized;
	public $siteViewHiddenSerialized;
	public function init() {
		parent::init();
		if($this->app->params->get("manage") !== null) {
			$this->authenticationRequired = new _hx_array(array("cms_admin", "cms_manager"));
			;
		}
		$this->linkMode = _hx_equal($this->app->params->get("linkMode"), "true");
		$this->pagesMode = _hx_equal($this->app->params->get("pagesMode"), "true");
		$this->siteMode = _hx_equal($this->app->params->get("siteMode"), "true") || $this->siteMode;
		if($this->pagesMode) {
			$this->navigation->pageHeading = "Pages";
			$this->navigation->setSelected("Pages");
			;
		}
		else {
			$this->navigation->pageHeading = "Datasets";
			$this->navigation->setSelected("Datasets");
			;
		}
		if($this->siteMode) {
			$this->navigation->pageHeading = "Site";
			$this->navigation->setSelected("SiteView");
			;
		}
		;
	}
	public function setupLeftNav() {
		$isAdmin = $this->user->isAdmin();
		if($this->pagesMode && !$this->siteMode) {
			$pages = $this->app->getDb()->request("SELECT *, p.id as pid FROM `_pages` p, `_definitions` d WHERE p.definitionId=d.id ORDER BY d.`order`");
			$this->leftNavigation->addSection("Pages", null);
			if(null == $pages) throw new HException('null iterable');
			$»it = $pages->iterator();
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
			if($this->user->isAdmin() || $this->user->isSuper()) {
				$this->leftNavigation->footer = "<a href=\"?request=cms.modules.base.Definitions&manage=true&pagesMode=true\">Manage Pages</a>";
				;
			}
			unset($pages);
		}
		else {
			if($this->siteMode) {
				$pages = $this->app->getDb()->request("SELECT *, p.id as pid FROM `_pages` p, `_definitions` d WHERE p.definitionId=d.id ORDER BY d.`order`");
				$tables = $this->app->getDb()->request("SELECT * FROM `_definitions` d WHERE d.isPage='0' ORDER BY `order`");
				$siteViewData = $this->app->getDb()->requestSingle("SELECT `value` FROM `_settings` WHERE `key`='siteView'")->value;
				$menu = new site_cms_modules_base_helper_MenuDef(null, null);
				$this->siteView = new site_cms_modules_base_helper_MenuDef(null, null);
				try {
					$menu = haxe_Unserializer::run($siteViewData);
					;
				}catch(Exception $»e) {
				$_ex_ = ($»e instanceof HException) ? $»e->e : $»e;
				;
				{ $e = $_ex_;
				{
					;
					;
				}}}
				if($menu->headings->length === 0) {
					$this->leftNavigation->addSection("ERROR", null);
					$this->leftNavigation->addLink("ERROR", ">> Manage", "cms.modules.base.SiteView&manage=true", null, null, null);
					;
				}
				else {
					{
						$_g = 0; $_g1 = $menu->headings;
						while($_g < $_g1->length) {
							$heading = $_g1[$_g];
							++$_g;
							$this->leftNavigation->addSection($heading->name, $heading->isSeperator);
							if(!$heading->isSeperator) {
								$this->siteView->addHeading($heading->name);
								;
							}
							else {
								$this->siteView->addSeperator();
								;
							}
							unset($heading);
						}
						unset($_g1,$_g);
					}
					{
						$_g = 0; $_g1 = $menu->items;
						while($_g < $_g1->length) {
							$item = $_g1[$_g];
							++$_g;
							switch($item->type) {
							case site_cms_modules_base_helper_MenuItemType::$PAGE_ROLL:{
								null;
								;
							}break;
							case site_cms_modules_base_helper_MenuItemType::$DATASET:{
								if(Lambda::exists($tables, array(new _hx_lambda(array(&$_g, &$_g1, &$e, &$isAdmin, &$item, &$menu, &$pages, &$siteViewData, &$tables), "site_cms_modules_base_DatasetBase_0"), 'execute'))) {
									$link = ("cms.modules.base.Dataset&dataset=" . $item->id) . "&resetState=true&siteMode=true";
									$editTag = site_cms_modules_base_DatasetBase_1($this, $_g, $_g1, $e, $isAdmin, $item, $link, $menu, $pages, $siteViewData, $tables);
									$this->leftNavigation->addLink($item->heading, $item->name, $link, $item->indent, false, $editTag);
									if($item->listChildren !== null) {
										$def = new site_cms_common_Definition($item->id);
										$el = $def->getElement($item->listChildren);
										if($el->type === "association") {
											$p = $el->properties;
											$primaryData = $this->app->getDb()->request(("SHOW COLUMNS FROM `" . $p->table) . "` WHERE `Key`='PRI' AND `Extra`='auto_increment'");
											if($primaryData->length > 0) {
												$primaryKey = $primaryData->pop()->Field;
												$sql = ((((("SELECT `" . $primaryKey) . "` AS 'k', `") . $p->name) . "` AS 'v' FROM `") . $p->table) . "`";
												$result = $this->app->getDb()->request($sql);
												$tIndent = $item->indent + 1;
												if(null == $result) throw new HException('null iterable');
												$»it = $result->iterator();
												while($»it->hasNext()) {
												$row = $»it->next();
												{
													$this->leftNavigation->addLink($item->heading, $row->v, (((($link . "&autofilterBy=") . $p->name) . "&autofilterByAssoc=") . $row->k) . "", $tIndent, null, null);
													;
												}
												}
												unset($tIndent,$sql,$result,$primaryKey);
											}
											unset($primaryData,$p);
										}
										unset($el,$def);
									}
									$tables = Lambda::filter($tables, array(new _hx_lambda(array(&$_g, &$_g1, &$e, &$editTag, &$isAdmin, &$item, &$link, &$menu, &$pages, &$siteViewData, &$tables), "site_cms_modules_base_DatasetBase_2"), 'execute'));
									$this->siteView->addItem($item->id, $item->type, $item->name, $item->heading, $item->indent, $item->listChildren, null);
									unset($link,$editTag);
								}
								;
							}break;
							case site_cms_modules_base_helper_MenuItemType::$PAGE:{
								$page = null;
								if(null == $pages) throw new HException('null iterable');
								$»it = $pages->iterator();
								while($»it->hasNext()) {
								$p = $»it->next();
								{
									if($p->pid === $item->id) {
										$page = $p;
										break;
										;
									}
									;
								}
								}
								if($page !== null) {
									$link = ("cms.modules.base.DatasetItem&pagesMode=true&action=edit&id=" . $item->id) . "&siteMode=true";
									$editTag = site_cms_modules_base_DatasetBase_3($this, $_g, $_g1, $e, $isAdmin, $item, $link, $menu, $page, $pages, $siteViewData, $tables);
									$this->leftNavigation->addLink($item->heading, $item->name, $link, $item->indent, false, $editTag);
									$pages = Lambda::filter($pages, array(new _hx_lambda(array(&$_g, &$_g1, &$e, &$editTag, &$isAdmin, &$item, &$link, &$menu, &$page, &$pages, &$siteViewData, &$tables), "site_cms_modules_base_DatasetBase_4"), 'execute'));
									$this->siteView->addItem($item->id, $item->type, $item->name, $item->heading, $item->indent, $item->listChildren, null);
									unset($link,$editTag);
								}
								unset($page);
							}break;
							case site_cms_modules_base_helper_MenuItemType::$NULL:{
								if(_hx_field($item, "linkChild") !== null) {
									$link = ((("cms.modules.base.DatasetItem&action=edit&siteMode=true&singleInstanceEdit=true&dataset=" . $item->linkChild->dataset) . "&id=") . $item->linkChild->id) . "";
									$this->leftNavigation->addLink($item->heading, $item->name, $link, $item->indent, null, null);
									$this->siteView->addItem($item->id, $item->type, $item->name, $item->heading, $item->indent, null, $item->linkChild);
									unset($link);
								}
								else {
									$this->leftNavigation->addLink($item->heading, $item->name, null, $item->indent, null, null);
									$this->siteView->addItem($item->id, $item->type, $item->name, $item->heading, $item->indent, null, null);
									;
								}
								;
							}break;
							}
							unset($item);
						}
						unset($_g1,$_g);
					}
					;
				}
				$this->siteViewHidden = new site_cms_modules_base_helper_MenuDef(null, null);
				if(null == $tables) throw new HException('null iterable');
				$»it = $tables->iterator();
				while($»it->hasNext()) {
				$item = $»it->next();
				{
					$this->siteViewHidden->addItem($item->id, site_cms_modules_base_helper_MenuItemType::$DATASET, $item->name, null, null, null, null);
					;
				}
				}
				if(null == $pages) throw new HException('null iterable');
				$»it = $pages->iterator();
				while($»it->hasNext()) {
				$item = $»it->next();
				{
					$this->siteViewHidden->addItem($item->pid, site_cms_modules_base_helper_MenuItemType::$PAGE, $item->name, null, null, null, null);
					;
				}
				}
				$this->siteViewSerialized = haxe_Serializer::run($this->siteView);
				$this->siteViewHiddenSerialized = haxe_Serializer::run($this->siteViewHidden);
				if($this->user->isAdmin() || $this->user->isSuper()) {
					$this->leftNavigation->footer = "<a href=\"?request=cms.modules.base.SiteView&manage=true\">Manage Menu</a>";
					;
				}
				unset($tables,$siteViewData,$pages,$menu,$e);
			}
			else {
				$tables = $this->app->getDb()->request("SELECT * FROM `_definitions` d WHERE d.isPage='0' ORDER BY `order`");
				$this->leftNavigation->addSection("Datasets", null);
				$isAdmin1 = $this->user->isAdmin();
				if(null == $tables) throw new HException('null iterable');
				$»it = $tables->iterator();
				while($»it->hasNext()) {
				$table = $»it->next();
				{
					if($table->showInMenu) {
						$name = site_cms_modules_base_DatasetBase_5($this, $isAdmin, $isAdmin1, $table, $tables);
						if($isAdmin1) {
							$this->leftNavigation->addLink("Datasets", $name, ("cms.modules.base.Dataset&dataset=" . $table->id) . "&resetState=true", $table->indents, false, $this->leftNavigation->editTag(("cms.modules.base.Definition&id=" . $table->id) . "&pagesMode=false"));
							;
						}
						else {
							$this->leftNavigation->addLink("Datasets", $name, ("cms.modules.base.Dataset&dataset=" . $table->id) . "&resetState=true", $table->indents, null, null);
							;
						}
						unset($name);
					}
					;
				}
				}
				if($this->user->isAdmin() || $this->user->isSuper()) {
					$this->leftNavigation->footer = "<a href=\"?request=cms.modules.base.Definitions&manage=true\">Manage Lists</a>";
					;
				}
				unset($tables,$isAdmin1);
			}
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
	function __toString() { return 'site.cms.modules.base.DatasetBase'; }
}
;
function site_cms_modules_base_DatasetBase_0(&$_g, &$_g1, &$e, &$isAdmin, &$item, &$menu, &$pages, &$siteViewData, &$tables, $x) {
{
	return ($x->id === $item->id);
	;
}
}
function site_cms_modules_base_DatasetBase_1(&$»this, &$_g, &$_g1, &$e, &$isAdmin, &$item, &$link, &$menu, &$pages, &$siteViewData, &$tables) {
if($isAdmin) {
	return $»this->leftNavigation->editTag(("cms.modules.base.Definition&id=" . $item->id) . "&pagesMode=false");
	;
}
else {
	return "";
	;
}
}
function site_cms_modules_base_DatasetBase_2(&$_g, &$_g1, &$e, &$editTag, &$isAdmin, &$item, &$link, &$menu, &$pages, &$siteViewData, &$tables, $x) {
{
	return $x->id !== $item->id;
	;
}
}
function site_cms_modules_base_DatasetBase_3(&$»this, &$_g, &$_g1, &$e, &$isAdmin, &$item, &$link, &$menu, &$page, &$pages, &$siteViewData, &$tables) {
if($isAdmin) {
	return $»this->leftNavigation->editTag(("cms.modules.base.Definition&id=" . $page->id) . "&pagesMode=true");
	;
}
else {
	return "";
	;
}
}
function site_cms_modules_base_DatasetBase_4(&$_g, &$_g1, &$e, &$editTag, &$isAdmin, &$item, &$link, &$menu, &$page, &$pages, &$siteViewData, &$tables, $x) {
{
	return $x->pid !== $item->id;
	;
}
}
function site_cms_modules_base_DatasetBase_5(&$»this, &$isAdmin, &$isAdmin1, &$table, &$tables) {
if($table->name !== "") {
	return $table->name;
	;
}
else {
	return $table->table;
	;
}
}