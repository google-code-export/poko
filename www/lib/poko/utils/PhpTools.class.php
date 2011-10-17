<?php

class poko_utils_PhpTools {
	public function __construct(){}
	static function pf($obj) {
		php_Lib::hprint("<pre>");
		print_r($obj);
		php_Lib::hprint("</pre>");
		;
	}
	static function setupTrace() {
		$f = haxe_Log::$trace;
		haxe_Log::$trace = array(new _hx_lambda(array(&$f), "poko_utils_PhpTools_0"), 'execute');
		unset($f);
	}
	static function mail($to, $subject, $message, $headers, $additionalParameters) {
		if($additionalParameters === null) {
			$additionalParameters = "";
			;
		}
		if($headers === null) {
			$headers = "";
			;
		}
		mail($to, $subject, $message, $headers, $additionalParameters);
		;
	}
	static function moveFile($filename, $destination) {
		$success = move_uploaded_file($filename, $destination);
		if(!$success) {
			throw new HException(((("Error uploading '" . $filename) . "' to '") . $destination) . "'");
			;
		}
		unset($success);
	}
	static function getFilesInfo() {
		$files = php_Lib::hashOfAssociativeArray($_FILES);
		$output = new Hash();
		if(null == $files) throw new HException('null iterable');
		$»it = $files->keys();
		while($»it->hasNext()) {
		$file = $»it->next();
		$output->set($file, php_Lib::hashOfAssociativeArray($files->get($file)));
		}
		return $output;
		unset($output,$files);
	}
	static function cleanTags($text) {
		
			$text = preg_replace(
			array(
			  // Remove invisible content
				'@<head[^>]*?>.*?</head>@siu',
				'@<style[^>]*?>.*?</style>@siu',
				'@<script[^>]*?.*?</script>@siu',
				'@<object[^>]*?.*?</object>@siu',
				'@<embed[^>]*?.*?</embed>@siu',
				'@<applet[^>]*?.*?</applet>@siu',
				'@<noframes[^>]*?.*?</noframes>@siu',
				'@<noscript[^>]*?.*?</noscript>@siu',
				'@<noembed[^>]*?.*?</noembed>@siu',
			  // Add line breaks before and after blocks
				'@</?((address)|(blockquote)|(center)|(del))@iu',
				'@</?((div)|(h[1-9])|(ins)|(isindex)|(p)|(pre))@iu',
				'@</?((dir)|(dl)|(dt)|(dd)|(li)|(menu)|(ol)|(ul))@iu',
				'@</?((table)|(th)|(td)|(caption))@iu',
				'@</?((form)|(button)|(fieldset)|(legend)|(input))@iu',
				'@</?((label)|(select)|(optgroup)|(option)|(textarea))@iu',
				'@</?((frameset)|(frame)|(iframe))@iu',
			),
			array(
				' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ',
				"\n\$0", "\n\$0", "\n\$0", "\n\$0", "\n\$0", "\n\$0",
				"\n\$0", "\n\$0",
			),
			$text );
		return strip_tags( $text );

		
		;
		return "";
		;
	}
	function __toString() { return 'poko.utils.PhpTools'; }
}
;
function poko_utils_PhpTools_0(&$f, $v, $pos) {
{
	call_user_func_array($f, array($v, $pos));
	php_Lib::hprint("<br />");
	;
}
}