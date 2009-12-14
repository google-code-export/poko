<?php

class site_cms_common_Definition {
	public function __construct($id) {
		if( !php_Boot::$skip_constructor ) {
		$this->id = $id;
		$this->elements = new _hx_array(array());
		$this->primaryKey = "";
		$this->load();
	}}
	public $id;
	public $name;
	public $table;
	public $description;
	public $primaryKey;
	public $elements;
	public $postCreateSql;
	public $postEditSql;
	public $postDeleteSql;
	public $postProcedure;
	public $showFiltering;
	public $showOrdering;
	public $helpItem;
	public $helpList;
	public $autoOrderingField;
	public $autoOrderingOrder;
	public function getElement($name) {
		{
			$_g = 0; $_g1 = $this->elements;
			while($_g < $_g1->length) {
				$el = $_g1[$_g];
				++$_g;
				if($el->name == $name) {
					return $el;
				}
				unset($el);
			}
		}
		return null;
	}
	public function addElement($name) {
		$exists = false;
		if($this->elements->length !== 0) {
			{
				$_g = 0; $_g1 = $this->elements;
				while($_g < $_g1->length) {
					$el = $_g1[$_g];
					++$_g;
					if($el->name == $name) {
						$exists = true;
					}
					unset($el);
				}
			}
			if($exists) {
				return null;
			}
		}
		$element = new site_cms_common_DefinitionElementMeta($name);
		$this->elements->push($element);
		return $element;
	}
	public function removeElement($name) {
		$el = null;
		{
			$_g = 0; $_g1 = $this->elements;
			while($_g < $_g1->length) {
				$el1 = $_g1[$_g];
				++$_g;
				if($el1->name == $name) {
					$this->elements->remove($el1);
				}
				unset($el1);
			}
		}
	}
	public function reOrderElements($order) {
		if($order === null) {
			return;
		}
		{
			$_g1 = 0; $_g = $order->length;
			while($_g1 < $_g) {
				$i = $_g1++;
				_hx_array_get($this->elements, $i)->order = Std::parseFloat($order[$i]);
				unset($i);
			}
		}
		$this->elements->sort(array(new _hx_lambda(array("_g" => &$_g, "_g1" => &$_g1, "i" => &$i, "order" => &$order), null, array('el1','el2'), "{
			return (\$el1->order > \$el2->order ? 1 : -1);
		}"), 'execute2'));
		$this->save();
		$this->load();
	}
	public function load() {
		$results = poko_Poko::$instance->getDb()->requestSingle("SELECT * FROM `_definitions` WHERE `id`=\"" . $this->id . "\"");
		if($results === null) {
			throw new HException(("failed to load definition: " . $this->id));
		}
		$this->name = $results->name;
		$this->table = $results->table;
		$this->description = $results->description;
		$this->primaryKey = $results->primaryKey;
		$this->postCreateSql = $results->postCreateSql;
		$this->postEditSql = $results->postEditSql;
		$this->postDeleteSql = $results->postDeleteSql;
		$this->postProcedure = $results->postProcedure;
		$this->showFiltering = $results->showFiltering;
		$this->showOrdering = $results->showOrdering;
		$this->helpItem = $results->help;
		$this->helpList = $results->help_list;
		$this->autoOrderingField = "";
		$this->autoOrderingOrder = "ASC";
		$autoOrdering = _hx_string_call($results->autoOrdering, "split", array("|"));
		if($autoOrdering->length === 2) {
			$this->autoOrderingField = $autoOrdering[0];
			$this->autoOrderingOrder = $autoOrdering[1];
		}
		try {
			$this->elements = haxe_Unserializer::run($results->elements);
		}catch(Exception $»e) {
		$_ex_ = ($»e instanceof HException) ? $»e->e : $»e;
		;
		{ $e = $_ex_;
		{
			$this->save();
		}}}
	}
	public function save() {
		$data = _hx_anonymous(array());
		$s = new haxe_Serializer();
		$s->serialize($this->elements);
		$data->name = $this->name;
		$data->table = $this->table;
		$data->description = $this->description;
		$data->elements = $s->toString();
		$data->primaryKey = $this->primaryKey;
		$data->showFiltering = $this->showFiltering;
		$data->showOrdering = $this->showOrdering;
		$data->postCreateSql = $this->postCreateSql;
		$data->postEditSql = $this->postEditSql;
		$data->postDeleteSql = $this->postDeleteSql;
		$data->postProcedure = $this->postProcedure;
		$data->help = $this->helpItem;
		$data->help_list = $this->helpList;
		$data->autoOrdering = $this->autoOrderingField . "|" . $this->autoOrderingOrder;
		poko_Poko::$instance->getDb()->update("_definitions", $data, "`id`=\"" . $this->id . "\"");
	}
	public function __call($m, $a) {
		if(isset($this->$m) && is_callable($this->$m))
			return call_user_func_array($this->$m, $a);
		else if(isset($this->»dynamics[$m]) && is_callable($this->»dynamics[$m]))
			return call_user_func_array($this->»dynamics[$m], $a);
		else
			throw new HException('Unable to call «'.$m.'»');
	}
	static function tableToDefinitionId($table) {
		$res = poko_Poko::$instance->getDb()->requestSingle("SELECT `id` FROM `_definitions` WHERE `table`='" . $table . "'");
		return $res->id;
	}
	function __toString() { return 'site.cms.common.Definition'; }
}
