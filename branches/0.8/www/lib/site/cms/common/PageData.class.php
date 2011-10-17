<?php

class site_cms_common_PageData {
	public function __construct() {
		;
		;
		;
	}
	public $id;
	public $name;
	public $data;
	public $definition;
	public function loadById($id) {
		$app = poko_Poko::$instance;
		$result = $app->getDb()->requestSingle(("SELECT p.id as 'id', d.name as 'name', d.id as 'definitionId', p.data as 'data' FROM `_pages` p, `_definitions` d WHERE p.`id`=\"" . $app->getDb()->escape(Std::string($id))) . "\" AND p.`definitionid`=d.`id`");
		$this->init($result);
		unset($result,$app);
	}
	public function loadByName($name) {
		$app = poko_Poko::$instance;
		$result = $app->getDb()->requestSingle(("SELECT p.id as 'id', d.name as 'name', d.id as 'definitionId', p.data as 'data' FROM `_pages` p, `_definitions` d WHERE d.`name`=\"" . $app->getDb()->escape($name)) . "\" AND p.`definitionid`=d.`id`");
		$this->init($result);
		unset($result,$app);
	}
	public function init($result) {
		$this->id = $result->id;
		$this->name = $result->name;
		try {
			$this->data = haxe_Unserializer::run($result->data);
			;
		}catch(Exception $»e) {
		$_ex_ = ($»e instanceof HException) ? $»e->e : $»e;
		;
		{ $e = $_ex_;
		{
			$result->data = _hx_anonymous(array());
			;
		}}}
		$this->definition = new site_cms_common_Definition($result->definitionId);
		unset($e);
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
	static function getPageById($id) {
		$p = new site_cms_common_PageData();
		$p->loadById($id);
		return $p;
		unset($p);
	}
	static function getPageByName($name) {
		$p = new site_cms_common_PageData();
		$p->loadByName($name);
		return $p;
		unset($p);
	}
	static function getPageNames() {
		$result = poko_Poko::$instance->getDb()->request("SELECT `name` FROM `_definitions` WHERE `isPage`='1' ORDER BY `order`");
		$result = Lambda::map($result, array(new _hx_lambda(array(&$result), "site_cms_common_PageData_0"), 'execute'));
		return $result;
		unset($result);
	}
	static function getPages() {
		$result = poko_Poko::$instance->getDb()->request("SELECT `name` FROM `_definitions` WHERE `isPage`='1' ORDER BY `order`");
		$pages = new HList();
		if(null == $result) throw new HException('null iterable');
		$»it = $result->iterator();
		while($»it->hasNext()) {
		$row = $»it->next();
		$pages->add(site_cms_common_PageData::getPageByName($row->name));
		}
		return $pages;
		unset($result,$pages);
	}
	function __toString() { return 'site.cms.common.PageData'; }
}
;
function site_cms_common_PageData_0(&$result, $item) {
{
	return Reflect::field($item, "name");
	;
}
}