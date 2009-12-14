<?php

class site_cms_modules_base_helper_MenuItemType extends Enum {
		public static $DATASET;
		public static $NULL;
		public static $PAGE;
		public static $PAGE_ROLL;
	}
	site_cms_modules_base_helper_MenuItemType::$DATASET = new site_cms_modules_base_helper_MenuItemType("DATASET", 1);
	site_cms_modules_base_helper_MenuItemType::$NULL = new site_cms_modules_base_helper_MenuItemType("NULL", 2);
	site_cms_modules_base_helper_MenuItemType::$PAGE = new site_cms_modules_base_helper_MenuItemType("PAGE", 0);
	site_cms_modules_base_helper_MenuItemType::$PAGE_ROLL = new site_cms_modules_base_helper_MenuItemType("PAGE_ROLL", 3);
