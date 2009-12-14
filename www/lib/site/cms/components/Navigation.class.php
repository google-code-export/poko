<?php

class site_cms_components_Navigation extends poko_system_Component {
	public function __construct() {
		if( !php_Boot::$skip_constructor ) {
		parent::__construct();
	}}
	public $pageHeading;
	public $content;
	public $selected;
	public $userName;
	public function init() {
		$name = $this->app->params->get("request");
		$name = _hx_substr($name, _hx_last_index_of($name, ".", null) + 1, null);
		$this->pageHeading = "page";
	}
	public function main() {
		$requests = new Hash();
		$cmsController = $this->app->controller;
		if($cmsController->user->authenticated) {
			if($cmsController->user->isAdmin() || $cmsController->user->isSuper()) {
				$requests->set("modules.base.Pages", "Pages");
				$requests->set("modules.base.Datasets", "Data");
				$requests->set("modules.base.SiteView", "Site View");
				$requests->set("modules.media.Index", "Media");
				$requests->set("modules.base.Settings", "Settings");
			}
			else {
				$requests->set("modules.base.SiteView", "Site Map");
			}
			$requests->set("modules.help.Help", "Help");
			if($cmsController->user->isAdmin() || $cmsController->user->isSuper()) {
				$requests->set("modules.base.Users", "Users");
			}
			$this->content = "<ul id=\"headingNavigation\">\x0A";
			$»it = $requests->keys();
			while($»it->hasNext()) {
			$request = $»it->next();
			{
				$parts = _hx_explode(".", $request);
				if($parts[$parts->length - 1] == $this->selected) {
					$this->content .= "<li><a href=\"?request=cms." . $request . "\" class=\"navigation_selected\">" . $requests->get($request) . "</a></li>\x0A";
				}
				else {
					$this->content .= "<li><a href=\"?request=cms." . $request . "\">" . $requests->get($request) . "</a></li>\x0A";
				}
				unset($parts);
			}
			}
			$this->content .= "</ul>\x0A";
			$this->userName = $cmsController->user->name;
		}
		else {
			$this->content = null;
		}
	}
	public function setSelected($id) {
		$this->selected = $id;
	}
	public function __call($m, $a) {
		if(isset($this->$m) && is_callable($this->$m))
			return call_user_func_array($this->$m, $a);
		else if(isset($this->»dynamics[$m]) && is_callable($this->»dynamics[$m]))
			return call_user_func_array($this->»dynamics[$m], $a);
		else
			throw new HException('Unable to call «'.$m.'»');
	}
	function __toString() { return 'site.cms.components.Navigation'; }
}
