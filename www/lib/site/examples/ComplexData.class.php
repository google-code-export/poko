<?php

class site_examples_ComplexData extends site_examples_templates_DefaultTemplate {
	public function __construct() {
		if( !php_Boot::$skip_constructor ) {
		parent::__construct();
		;
	}}
	public $form1;
	public $showProject;
	public $projectId;
	public $projectData;
	public $projectServices;
	public $projectImages;
	public function main() {
		$this->form1 = new poko_form_Form("form1", null, null);
		$this->form1->addElement(new poko_form_elements_Selectbox("project", "Project", null, null, null, null, null), null);
		$this->form1->setSubmitButton($this->form1->addElement(new poko_form_elements_Button("submit", "Submit", null, null), null));
		$this->form1->populateElements(null);
		$options = $this->app->getDb()->request("SELECT `id` as 'key', `name` as 'value' FROM example_projects");
		if(null == $options) throw new HException('null iterable');
		$»it = $options->iterator();
		while($»it->hasNext()) {
		$option = $»it->next();
		$this->form1->getElementTyped("project", _hx_qtype("poko.form.elements.Selectbox"))->data->add($option);
		}
		$this->showProject = false;
		if($this->form1->isSubmitted()) {
			$this->projectId = $this->form1->getData()->project;
			if($this->projectId !== "") {
				$this->viewProject();
				;
			}
			;
		}
		unset($options);
	}
	public function viewProject() {
		$this->showProject = true;
		$this->projectData = $this->app->getDb()->requestSingle(("SELECT * FROM `example_projects` p, `example_categories` c WHERE c.`id`=p.`category` AND p.`id`=\"" . $this->projectId) . "\"");
		$this->projectServices = $this->app->getDb()->request(("SELECT * FROM `example_services` s, `example_projects_services` link WHERE link.`projectId`=" . $this->projectId) . " AND s.`id`=link.`serviceId`");
		$this->projectImages = $this->app->getDb()->request(("SELECT * FROM `example_images` WHERE `link_to`='example_projects' AND `link_value`=\"" . $this->projectId) . "\"");
		;
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
	function __toString() { return 'site.examples.ComplexData'; }
}
