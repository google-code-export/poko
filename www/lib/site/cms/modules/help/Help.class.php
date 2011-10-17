<?php

class site_cms_modules_help_Help extends site_cms_templates_CmsTemplate {
	public function __construct() { if( !php_Boot::$skip_constructor ) {
		parent::__construct();
		;
	}}
	public function main() {
		$topics = new Hash();
		$developerTopics = new Hash();
		if($this->user->isAdmin()) {
			$topics->set("manager_home", "Introduction");
			$topics->set("manager_about_users", "About Users");
			;
		}
		else {
			if(!$this->user->isAdmin() && !$this->user->isSuper()) {
				$topics->set("user_home", "Introduction");
				;
			}
			;
		}
		$topics->set("user_pages", "About Pages");
		$topics->set("user_data", "About Data");
		if($this->user->isSuper()) {
			$developerTopics->set("developer_home", "Introduction");
			$developerTopics->set("developer_basicConcepts", "Basic Concepts");
			$developerTopics->set("developer_gettingStarted", "Getting started");
			$developerTopics->set("developer_about_pages", "About Pages");
			$developerTopics->set("developer_about_datasets", "About Datasets");
			$developerTopics->set("developer_about_users", "About Users");
			$developerTopics->set("developer_fieldDefinitions", "Field Definitions");
			;
		}
		$this->leftNavigation->addSection("General", null);
		$this->leftNavigation->addSection("Developer", null);
		if(null == $topics) throw new HException('null iterable');
		$»it = $topics->keys();
		while($»it->hasNext()) {
		$key = $»it->next();
		$this->leftNavigation->addLink("General", $topics->get($key), "cms.modules.help.Help&topic=" . $key, null, null, null);
		}
		if(null == $developerTopics) throw new HException('null iterable');
		$»it = $developerTopics->keys();
		while($»it->hasNext()) {
		$key = $»it->next();
		$this->leftNavigation->addLink("Developer", $developerTopics->get($key), "cms.modules.help.Help&topic=" . $key, null, null, null);
		}
		$topic = $this->app->params->get("topic");
		if($topic === null) {
			if($this->user->isSuper()) {
				$topic = "developer_home";
				;
			}
			else {
				if($this->user->isAdmin()) {
					$topic = "manager_home";
					;
				}
				else {
					$topic = "user_home";
					;
				}
				;
			}
			;
		}
		$this->view->setOutput(("<div class=\"helpWrapper\">" . poko_views_renderers_Templo::parse(("cms/modules/help/blocks/" . $topic) . ".mtt", null)) . "</div>");
		unset($topics,$topic,$developerTopics);
	}
	function __toString() { return 'site.cms.modules.help.Help'; }
}
