<?php

class site_cms_modules_base_DatasetBase extends site_cms_templates_CmsTemplate {
	public function __construct() {
		if( !php_Boot::$skip_constructor ) {
		parent::__construct();
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
		}
		$this->linkMode = _hx_equal($this->app->params->get("linkMode"), "true");
		$this->pagesMode = _hx_equal($this->app->params->get("pagesMode"), "true");
		$this->siteMode = _hx_equal($this->app->params->get("siteMode"), "true") || $this->siteMode;
		if($this->pagesMode) {
			$this->navigation->pageHeading = "Pages";
			$this->navigation->setSelected("Pages");
		}
		else {
			if($this->siteMode) {
				$this->navigation->pageHeading = "Site";
				$this->navigation->setSelected("SiteView");
			}
			else {
				$this->navigation->pageHeading = "Datasets";
				$this->navigation->setSelected("Datasets");
			}
		}
	}
	public function setupLeftNav() {
		if($this->pagesMode && !$this->siteMode) {
			$pages = $this->app->getDb()->request("SELECT *, p.id as pid FROM `_pages` p, `_definitions` d WHERE p.definitionId=d.id ORDER BY d.`order`");
			$this->leftNavigation->addSection("Pages", null);
			$»it = $pages->iterator();
			while($»it->hasNext()) {
			$page = $»it->next();
			{
				$this->leftNavigation->addLink("Pages", $page->name, "cms.modules.base.DatasetItem&pagesMode=true&action=edit&id=" . $page->pid, $page->indents, null);
				;
			}
			}
			if($this->user->isAdmin() || $this->user->isSuper()) {
				$this->leftNavigation->footer = "<a href=\"?request=cms.modules.base.Definitions&manage=true&pagesMode=true\">Manage Pages</a>";
			}
		}
		else {
			if($this->siteMode) {
				$pages2 = $this->app->getDb()->request("SELECT *, p.id as pid FROM `_pages` p, `_definitions` d WHERE p.definitionId=d.id ORDER BY d.`order`");
				$tables = $this->app->getDb()->request("SELECT * FROM `_definitions` d WHERE d.isPage='0' ORDER BY `order`");
				$siteViewData = $this->app->getDb()->requestSingle("SELECT `value` FROM `_settings` WHERE `key`='siteView'")->value;
				$menu = new site_cms_modules_base_helper_MenuDef(null, null);
				$this->siteView = new site_cms_modules_base_helper_MenuDef(null, null);
				try {
					$menu = haxe_Unserializer::run($siteViewData);
				}catch(Exception $»e) {
				$_ex_ = ($»e instanceof HException) ? $»e->e : $»e;
				;
				{ $e = $_ex_;
				{
					;
				}}}
				if($menu->headings->length === 0) {
					$this->leftNavigation->addSection("ERROR", null);
					$this->leftNavigation->addLink("ERROR", ">> Manage", "cms.modules.base.SiteView&manage=true", null, null);
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
							}
							else {
								$this->siteView->addSeperator();
							}
							unset($heading);
						}
					}
					{
						$_g2 = 0; $_g12 = $menu->items;
						while($_g2 < $_g12->length) {
							$item = $_g12[$_g2];
							++$_g2;
							switch($item->type) {
							case site_cms_modules_base_helper_MenuItemType::$DATASET:{
								if(Lambda::exists($tables, array(new _hx_lambda(array("_ex_" => &$_ex_, "_g" => &$_g, "_g1" => &$_g1, "_g12" => &$_g12, "_g2" => &$_g2, "e" => &$e, "heading" => &$heading, "item" => &$item, "menu" => &$menu, "page" => &$page, "pages" => &$pages, "pages2" => &$pages2, "siteViewData" => &$siteViewData, "tables" => &$tables, "»e" => &$»e, "»it" => &$»it), null, array('x'), "{
									return (\$x->id === \$item->id);
								}"), 'execute1'))) {
									$link = "cms.modules.base.Dataset&dataset=" . $item->id . "&resetState=true&siteMode=true";
									$this->leftNavigation->addLink($item->heading, $item->name, $link, $item->indent, null);
									if($item->listChildren !== null) {
										$def = new site_cms_common_Definition($item->id);
										$el = $def->getElement($item->listChildren);
										if($el->type == "association") {
											$p = $el->properties;
											$primaryData = $this->app->getDb()->request("SHOW COLUMNS FROM `" . $p->table . "` WHERE `Key`='PRI' AND `Extra`='auto_increment'");
											if($primaryData->length > 0) {
												$primaryKey = $primaryData->pop()->Field;
												$sql = "SELECT `" . $primaryKey . "` AS 'k', `" . $p->name . "` AS 'v' FROM `" . $p->table . "`";
												$result = $this->app->getDb()->request($sql);
												$tIndent = $item->indent + 1;
												$»it2 = $result->iterator();
												while($»it2->hasNext()) {
												$row = $»it2->next();
												{
													$this->leftNavigation->addLink($item->heading, $row->v, $link . "&autofilterBy=" . $p->name . "&autofilterByAssoc=" . $row->k . "", $tIndent, null);
													;
												}
												}
											}
										}
									}
									$tables = Lambda::filter($tables, array(new _hx_lambda(array("_ex_" => &$_ex_, "_g" => &$_g, "_g1" => &$_g1, "_g12" => &$_g12, "_g2" => &$_g2, "def" => &$def, "e" => &$e, "el" => &$el, "heading" => &$heading, "item" => &$item, "link" => &$link, "menu" => &$menu, "p" => &$p, "page" => &$page, "pages" => &$pages, "pages2" => &$pages2, "primaryData" => &$primaryData, "primaryKey" => &$primaryKey, "result" => &$result, "row" => &$row, "siteViewData" => &$siteViewData, "sql" => &$sql, "tIndent" => &$tIndent, "tables" => &$tables, "»e" => &$»e, "»it" => &$»it, "»it2" => &$»it2), null, array('x'), "{
										return \$x->id !== \$item->id;
									}"), 'execute1'));
									$this->siteView->addItem($item->id, $item->type, $item->name, $item->heading, $item->indent, $item->listChildren, null);
								}
							}break;
							case site_cms_modules_base_helper_MenuItemType::$PAGE:{
								if(Lambda::exists($pages2, array(new _hx_lambda(array("_ex_" => &$_ex_, "_g" => &$_g, "_g1" => &$_g1, "_g12" => &$_g12, "_g2" => &$_g2, "def" => &$def, "e" => &$e, "el" => &$el, "heading" => &$heading, "item" => &$item, "link" => &$link, "menu" => &$menu, "p" => &$p, "page" => &$page, "pages" => &$pages, "pages2" => &$pages2, "primaryData" => &$primaryData, "primaryKey" => &$primaryKey, "result" => &$result, "row" => &$row, "siteViewData" => &$siteViewData, "sql" => &$sql, "tIndent" => &$tIndent, "tables" => &$tables, "»e" => &$»e, "»it" => &$»it, "»it2" => &$»it2), null, array('x'), "{
									return (\$x->pid === \$item->id);
								}"), 'execute1'))) {
									$link2 = "cms.modules.base.DatasetItem&pagesMode=true&action=edit&id=" . $item->id . "&siteMode=true";
									$this->leftNavigation->addLink($item->heading, $item->name, $link2, $item->indent, null);
									$pages2 = Lambda::filter($pages2, array(new _hx_lambda(array("_ex_" => &$_ex_, "_g" => &$_g, "_g1" => &$_g1, "_g12" => &$_g12, "_g2" => &$_g2, "def" => &$def, "e" => &$e, "el" => &$el, "heading" => &$heading, "item" => &$item, "link" => &$link, "link2" => &$link2, "menu" => &$menu, "p" => &$p, "page" => &$page, "pages" => &$pages, "pages2" => &$pages2, "primaryData" => &$primaryData, "primaryKey" => &$primaryKey, "result" => &$result, "row" => &$row, "siteViewData" => &$siteViewData, "sql" => &$sql, "tIndent" => &$tIndent, "tables" => &$tables, "»e" => &$»e, "»it" => &$»it, "»it2" => &$»it2), null, array('x'), "{
										return \$x->pid !== \$item->id;
									}"), 'execute1'));
									$this->siteView->addItem($item->id, $item->type, $item->name, $item->heading, $item->indent, $item->listChildren, null);
								}
							}break;
							case site_cms_modules_base_helper_MenuItemType::$NULL:{
								if(_hx_field($item, "linkChild") !== null) {
									$link3 = "cms.modules.base.DatasetItem&action=edit&siteMode=true&singleInstanceEdit=true&dataset=" . $item->linkChild->dataset . "&id=" . $item->linkChild->id . "";
									$this->leftNavigation->addLink($item->heading, $item->name, $link3, $item->indent, null);
									$this->siteView->addItem($item->id, $item->type, $item->name, $item->heading, $item->indent, null, $item->linkChild);
								}
								else {
									$this->leftNavigation->addLink($item->heading, $item->name, null, $item->indent, null);
									$this->siteView->addItem($item->id, $item->type, $item->name, $item->heading, $item->indent, null, null);
								}
							}break;
							}
							unset($»it2,$tIndent,$sql,$row,$result,$primaryKey,$primaryData,$p,$link3,$link2,$link,$item,$el,$def);
						}
					}
				}
				$this->siteViewHidden = new site_cms_modules_base_helper_MenuDef(null, null);
				$»it3 = $tables->iterator();
				while($»it3->hasNext()) {
				$item2 = $»it3->next();
				{
					$this->siteViewHidden->addItem($item2->id, site_cms_modules_base_helper_MenuItemType::$DATASET, $item2->name, null, null, null, null);
					;
				}
				}
				$»it4 = $pages2->iterator();
				while($»it4->hasNext()) {
				$item3 = $»it4->next();
				{
					$this->siteViewHidden->addItem($item3->pid, site_cms_modules_base_helper_MenuItemType::$PAGE, $item3->name, null, null, null, null);
					;
				}
				}
				$this->siteViewSerialized = haxe_Serializer::run($this->siteView);
				$this->siteViewHiddenSerialized = haxe_Serializer::run($this->siteViewHidden);
				if($this->user->isAdmin() || $this->user->isSuper()) {
					$this->leftNavigation->footer = "<a href=\"?request=cms.modules.base.SiteView&manage=true\">Manage Menu</a>";
				}
			}
			else {
				$tables2 = $this->app->getDb()->request("SELECT * FROM `_definitions` d WHERE d.isPage='0' ORDER BY `order`");
				$this->leftNavigation->addSection("Datasets", null);
				$»it5 = $tables2->iterator();
				while($»it5->hasNext()) {
				$table = $»it5->next();
				{
					if($table->showInMenu) {
						$name = ($table->name != "" ? $table->name : $table->table);
						$this->leftNavigation->addLink("Datasets", $name, "cms.modules.base.Dataset&dataset=" . $table->id . "&resetState=true", $table->indents, null);
					}
					unset($name);
				}
				}
				if($this->user->isAdmin() || $this->user->isSuper()) {
					$this->leftNavigation->footer = "<a href=\"?request=cms.modules.base.Definitions&manage=true\">Manage Lists</a>";
				}
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
	function __toString() { return 'site.cms.modules.base.DatasetBase'; }
}
