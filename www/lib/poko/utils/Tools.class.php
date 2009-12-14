<?php

class poko_utils_Tools {
	public function __construct() { ;
		;
	}
	static function getFormDataAsString($form) {
		$out = "";
		$»it = $form->getElements()->iterator();
		while($»it->hasNext()) {
		$element = $»it->next();
		{
			$out .= $element->label . ": " . $element->value . "\x0A";
			;
		}
		}
		return ($out);
	}
	function __toString() { return 'poko.utils.Tools'; }
}
