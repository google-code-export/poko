<?php

class site_cms_common_DateTimeMode extends Enum {
	public static $date;
	public static $dateTime;
	public static $time;
	public static $__constructors = array(0 => 'date', 2 => 'dateTime', 1 => 'time');
	}
site_cms_common_DateTimeMode::$date = new site_cms_common_DateTimeMode("date", 0);
site_cms_common_DateTimeMode::$dateTime = new site_cms_common_DateTimeMode("dateTime", 2);
site_cms_common_DateTimeMode::$time = new site_cms_common_DateTimeMode("time", 1);
