<?php

class poko_utils_Tools {
	public function __construct() { ;
		;
		;
	}
	static function getFormDataAsString($form) {
		$out = "";
		if(null == $form->getElements()) throw new HException('null iterable');
		$»it = $form->getElements()->iterator();
		while($»it->hasNext()) {
		$element = $»it->next();
		{
			$out .= (($element->label . ": ") . $element->value) . "\x0A";
			;
		}
		}
		return ($out);
		unset($out);
	}
	function __toString() { return 'poko.utils.Tools'; }
}
