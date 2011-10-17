<?php

class poko_views_Parse {
	public function __construct(){}
	static function template($template, $data) {
		$ext = _hx_substr($template, _hx_last_index_of($template, ".", null) + 1, null);
		return poko_views_Parse_0($data, $ext, $template);
		unset($ext);
	}
	function __toString() { return 'poko.views.Parse'; }
}
;
function poko_views_Parse_0(&$data, &$ext, &$template) {
switch(strtoupper($ext)) {
case "TPL":{
	return poko_views_renderers_Templo::parse($template, $data);
	;
}break;
case "PHP":{
	return poko_views_renderers_Php::parse($template, $data);
	;
}break;
case "HT":{
	return poko_views_renderers_HTemplate::parse($template, $data);
	;
}break;
default:{
	return null;
	;
}break;
}
}