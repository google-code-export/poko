<?php

class site_cms_modules_email_Index extends site_cms_modules_email_EmailBase {
	public function __construct() {
		if( !php_Boot::$skip_constructor ) {
		parent::__construct();
		;
	}}
	public $form;
	public $emailVars;
	public $previewStr;
	public $editStr;
	public $action;
	public $userCount;
	public function main() {
		parent::main();
		$this->head->addExternal(poko_utils_html_ScriptType::$js, "js/cms/wymeditor/jquery.wymeditor.pack.js", null, null, null);
		$this->setupLeftNav();
		$this->action = site_cms_modules_email_Index_0($this);
		$this->form = new poko_form_Form("emailForm", null, null);
		$this->emailVars = new HList();
		$this->emailVars->add(_hx_anonymous(array("name" => "id", "desc" => "The ID of the user", "link" => "idField", "field" => null)));
		$this->emailVars->add(_hx_anonymous(array("name" => "email", "desc" => "The email address of the user", "link" => "emailField", "field" => null)));
		$this->emailVars->add(_hx_anonymous(array("name" => "name", "desc" => "The name of the user", "link" => "nameField", "field" => null)));
		$fromName = new poko_form_elements_Input("fromName", "From Name", null, true, null, null);
		$this->form->addElement($fromName, null);
		$fromEmail = new poko_form_elements_Input("fromEmail", "From Email", null, true, null, null);
		$fromEmail->addValidator(new poko_form_validators_EmailValidator());
		$this->form->addElement($fromEmail, null);
		$emailSubject = new poko_form_elements_Input("emailSubject", "Subject", null, true, null, null);
		$this->form->addElement($emailSubject, null);
		$fromName->width = $fromEmail->width = $emailSubject->width = 350;
		$fromName->useSizeValues = $fromEmail->useSizeValues = $emailSubject->useSizeValues = true;
		$body = new poko_form_elements_RichtextWym("body", "Email Body", null, null, null);
		$body->width = 400;
		$body->height = 200;
		$this->form->addElement($body, null);
		$editBtn = new poko_form_elements_Button("editBtn", "Edit", null, null);
		$this->form->addElement($editBtn, null);
		$sendBtn = new poko_form_elements_Button("sendBtn", "Send", null, null);
		$this->form->addElement($sendBtn, null);
		$previewBtn = new poko_form_elements_Button("previewBtn", "Preview", null, null);
		$this->form->addElement($previewBtn, null);
		$this->form->populateElements(null);
		$emailSettings = $this->loadSettings();
		if($emailSettings->userTable === null || $emailSettings->userTable === "") {
			$this->messages->addError("Warning: No user table was defined in email settings.");
			return;
			;
		}
		if(null == $this->emailVars) throw new HException('null iterable');
		$»it = $this->emailVars->iterator();
		while($»it->hasNext()) {
		$item = $»it->next();
		$item->field = Reflect::field($emailSettings, $item->link);
		}
		try {
			$this->userCount = $this->app->getDb()->count($emailSettings->userTable, "1");
			;
		}catch(Exception $»e) {
		$_ex_ = ($»e instanceof HException) ? $»e->e : $»e;
		;
		{ $e = $_ex_;
		{
			$this->messages->addError("Table not found. Please edit settings.");
			;
		}}}
		$this->previewStr = $body->value;
		$this->editStr = $body->value;
		if(null == $this->emailVars) throw new HException('null iterable');
		$»it = $this->emailVars->iterator();
		while($»it->hasNext()) {
		$item = $»it->next();
		{
			;
		}
		}
		try {
			$userData = $this->getUsers($emailSettings, "1 LIMIT 1")->first();
			if($userData === null) {
				$userData = _hx_anonymous(array("id" => "#id#", "name" => "#name#", "email" => "#email#"));
				;
			}
			$this->previewStr = $this->generateHtml($this->previewStr, $this->emailVars, $userData, true);
			unset($userData);
		}catch(Exception $»e) {
		$_ex_ = ($»e instanceof HException) ? $»e->e : $»e;
		;
		{ $e2 = $_ex_;
		{
			$this->messages->addError("Couldn't get user information.");
			;
		}}}
		if($this->form->isSubmitted() && $this->form->isValid()) {
			$formData = $this->form->getData();
			if($this->form->submittedButtonName === "sendBtn") {
				if($emailSettings->userTable !== null && $emailSettings->idField !== null) {
					$users = $this->getUsers($emailSettings, null);
					if(null == $users) throw new HException('null iterable');
					$»it = $users->iterator();
					while($»it->hasNext()) {
					$user = $»it->next();
					{
						$html = $this->generateHtml($formData->body, $this->emailVars, $user, null);
						$from = (($fromName->value . "<") . $fromEmail->value) . ">";
						poko_utils_EmailForwarder::forwardMultipart($user->email, $emailSubject->value, $from, "plain@##@\$@#\$#@", $html, null, null, null);
						unset($html,$from);
					}
					}
					unset($users);
				}
				;
			}
			unset($formData);
		}
		unset($sendBtn,$previewBtn,$fromName,$fromEmail,$emailSubject,$emailSettings,$editBtn,$e2,$e,$body);
	}
	public function encodeVar($name) {
		return ("{" . $name) . "}";
		;
	}
	public function getUsers($s, $where) {
		if($where === null) {
			$where = "1";
			;
		}
		return $this->app->getDb()->request((((((((("SELECT `" . $s->idField) . "` as 'id', `") . $s->emailField) . "` as 'email', `") . $s->nameField) . "` as 'name' FROM `") . $s->userTable) . "` WHERE ") . $where);
		;
	}
	public function generateHtml($template, $vars, $data, $preview) {
		if($preview === null) {
			$preview = false;
			;
		}
		if($data !== null) {
			if(null == $vars) throw new HException('null iterable');
			$»it = $vars->iterator();
			while($»it->hasNext()) {
			$v = $»it->next();
			{
				$value = site_cms_modules_email_Index_1($this, $data, $preview, $template, $v, $vars);
				$template = str_replace(("{" . $v->name) . "}", $value, $template);
				unset($value);
			}
			}
			return $template;
			;
		}
		return null;
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
	function __toString() { return 'site.cms.modules.email.Index'; }
}
;
function site_cms_modules_email_Index_0(&$»this) {
if($»this->app->params->exists("action")) {
	return $»this->app->params->get("action");
	;
}
else {
	return "edit";
	;
}
}
function site_cms_modules_email_Index_1(&$»this, &$data, &$preview, &$template, &$v, &$vars) {
if($preview) {
	return ("<em><strong>" . Reflect::field($data, $v->field)) . "</strong></em>";
	;
}
else {
	return Reflect::field($data, $v->field);
	;
}
}