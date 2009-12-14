<?php

class site_Application extends poko_Poko {
	public function __construct() { if( !php_Boot::$skip_constructor ) {
		parent::__construct();
	}}
	function __toString() { return 'site.Application'; }
}
