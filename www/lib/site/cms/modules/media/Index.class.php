<?php

class site_cms_modules_media_Index extends site_cms_modules_media_MediaBase {
	public function main() {
		parent::main();
		$this->setupLeftNav();
		;
	}
	function __toString() { return 'site.cms.modules.media.Index'; }
}
