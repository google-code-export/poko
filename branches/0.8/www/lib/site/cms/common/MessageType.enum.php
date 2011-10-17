<?php

class site_cms_common_MessageType extends Enum {
	public static $DEBUG;
	public static $ERROR;
	public static $MESSAGE;
	public static $WARNING;
	public static $__constructors = array(0 => 'DEBUG', 1 => 'ERROR', 2 => 'MESSAGE', 3 => 'WARNING');
	}
site_cms_common_MessageType::$DEBUG = new site_cms_common_MessageType("DEBUG", 0);
site_cms_common_MessageType::$ERROR = new site_cms_common_MessageType("ERROR", 1);
site_cms_common_MessageType::$MESSAGE = new site_cms_common_MessageType("MESSAGE", 2);
site_cms_common_MessageType::$WARNING = new site_cms_common_MessageType("WARNING", 3);
