<?php

class haxe_xml_Fast {
	public function __construct($x) { if( !php_Boot::$skip_constructor ) {
		if($x->nodeType != Xml::$Document && $x->nodeType != Xml::$Element) {
			throw new HException("Invalid nodeType " . $x->nodeType);
			;
		}
		$this->x = $x;
		$this->node = new haxe_xml__Fast_NodeAccess($x);
		$this->nodes = new haxe_xml__Fast_NodeListAccess($x);
		$this->att = new haxe_xml__Fast_AttribAccess($x);
		$this->has = new haxe_xml__Fast_HasAttribAccess($x);
		$this->hasNode = new haxe_xml__Fast_HasNodeAccess($x);
		;
	}}
	public $x;
	public $name;
	public $innerData;
	public $innerHTML;
	public $node;
	public $nodes;
	public $att;
	public $has;
	public $hasNode;
	public $elements;
	public function getName() {
		return haxe_xml_Fast_0($this);
		;
	}
	public function getInnerData() {
		$it = $this->x->iterator();
		if(!$it->hasNext()) {
			throw new HException($this->getName() . " does not have data");
			;
		}
		$v = $it->next();
		if($it->hasNext()) {
			throw new HException($this->getName() . " does not only have data");
			;
		}
		if($v->nodeType != Xml::$PCData && $v->nodeType != Xml::$CData) {
			throw new HException($this->getName() . " does not have data");
			;
		}
		return $v->getNodeValue();
		unset($v,$it);
	}
	public function getInnerHTML() {
		$s = new StringBuf();
		if(null == $this->x) throw new HException('null iterable');
		$»it = $this->x->iterator();
		while($»it->hasNext()) {
		$x = $»it->next();
		$s->b .= $x->toString();
		}
		return $s->b;
		unset($s);
	}
	public function getElements() {
		$it = $this->x->elements();
		return _hx_anonymous(array("hasNext" => (isset($it->hasNext) ? $it->hasNext: array($it, "hasNext")), "next" => array(new _hx_lambda(array(&$it), "haxe_xml_Fast_1"), 'execute')));
		unset($it);
	}
	function __toString() { return 'haxe.xml.Fast'; }
}
;
function haxe_xml_Fast_0(&$»this) {
if($»this->x->nodeType == Xml::$Document) {
	return "Document";
	;
}
else {
	return $»this->x->getNodeName();
	;
}
}
function haxe_xml_Fast_1(&$it) {
{
	$x = $it->next();
	if($x === null) {
		return null;
		;
	}
	return new haxe_xml_Fast($x);
	unset($x);
}
}