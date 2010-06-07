<?php

class site_cms_components_Navigation extends poko_Component {
	public function __construct() {
		if( !php_Boot::$skip_constructor ) {
		parent::__construct();
		$name = $this->application->params->get("request");
		$name = _hx_substr($name, _hx_last_index_of($name, ".", null) + 1, null);
		$this->pageHeading = "page";
		$this->setSelected($name);
	}}
	public $pageHeading;
	public $content;
	public $selected;
	public $userName;
	public function main() {
		$requests = new Hash();
		if($this->application->user->authenticated) {
			if($this->application->user->isAdmin() || $this->application->user->isSuper()) {
				$requests->set("modules.base.Pages", "Pages");
				$requests->set("modules.base.Datasets", "Lists");
				$requests->set("modules.base.SiteView", "Site View");
				$requests->set("modules.media.Index", "Media");
				$requests->set("modules.base.Settings", "Settings");
			}
			else {
				$requests->set("modules.base.SiteView", "Site Map");
			}
			$requests->set("modules.help.Help", "Help");
			if($this->application->user->isAdmin() || $this->application->user->isSuper()) {
				$requests->set("modules.base.Users", "Users");
			}
			$this->content = "<ul id=\"headingNavigation\">\x0A";
			$»it = $requests->keys();
			while($»it->hasNext()) {
			$request = $»it->next();
			{
				if($request == $this->selected) {
					$this->content .= "<li>" . $requests->get($request) . "</li>\x0A";
				}
				else {
					$this->content .= "<li><a href=\"?request=cms." . $request . "\">" . $requests->get($request) . "</a></li>\x0A";
				}
				;
			}
			}
			$this->content .= "</ul>\x0A";
			$this->userName = $this->application->user->name;
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
