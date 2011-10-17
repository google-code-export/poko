<?php

class site_cms_modules_base_ChangePassword extends site_cms_templates_CmsTemplate {
	public function __construct() {
		if( !php_Boot::$skip_constructor ) {
		parent::__construct();
		;
	}}
	public $userData;
	public $action;
	public $form1;
	public $heading;
	public function main() {
		parent::main();
		$this->action = $this->app->params->get("form1_action");
		$this->heading = "Change Password";
		$this->setupForm1();
		if($this->form1->isSubmitted() && $this->form1->isValid()) {
			$this->process();
			;
		}
		$this->navigation->setSelected("Password");
		;
	}
	public function process() {
		switch($this->action) {
		case "change":{
			$d = $this->form1->getData();
			$oldPassword = $this->app->getDb()->requestSingle(("SELECT password FROM _users WHERE id='" . $this->user->id) . "'")->password;
			if(haxe_Md5::encode($d->passwordOld) === $oldPassword) {
				if(_hx_equal($d->passwordNew1, $d->passwordNew2)) {
					$this->app->getDb()->update("_users", _hx_anonymous(array("password" => haxe_Md5::encode($d->passwordNew1))), ("id='" . $this->user->id) . "'");
					$this->messages->addMessage("Password updated.");
					$this->form1->clearData();
					;
				}
				else {
					$this->messages->addError("You did not enter the new password the same twice. Please try again.");
					;
				}
				;
			}
			else {
				$this->messages->addError("Your old password was not correct.");
				;
			}
			unset($oldPassword,$d);
		}break;
		}
		;
	}
	public function setupForm1() {
		$this->form1 = new poko_form_Form("form1", null, null);
		$pass1 = new poko_form_elements_Input("passwordOld", "Old Password", null, true, null, null);
		$pass1->password = true;
		$this->form1->addElement($pass1, null);
		$pass2 = new poko_form_elements_Input("passwordNew1", "New Password", null, true, null, null);
		$pass2->password = true;
		$this->form1->addElement($pass2, null);
		$pass3 = new poko_form_elements_Input("passwordNew2", "New Password (Again)", null, true, null, null);
		$pass3->password = true;
		$this->form1->addElement($pass3, null);
		$this->form1->addElement(new poko_form_elements_Hidden("action", "change", null, null, null), null);
		$this->form1->addElement(new poko_form_elements_Button("submit", "Update", "Update", null), null);
		$this->form1->populateElements(null);
		unset($pass3,$pass2,$pass1);
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
	function __toString() { return 'site.cms.modules.base.ChangePassword'; }
}
