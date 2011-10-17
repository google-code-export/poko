<?php

class poko_views_ViewType extends Enum {
	public static $HTEMPLATE;
	public static $PHP;
	public static $TEMPLO;
	public static $__constructors = array(2 => 'HTEMPLATE', 0 => 'PHP', 1 => 'TEMPLO');
	}
poko_views_ViewType::$HTEMPLATE = new poko_views_ViewType("HTEMPLATE", 2);
poko_views_ViewType::$PHP = new poko_views_ViewType("PHP", 0);
poko_views_ViewType::$TEMPLO = new poko_views_ViewType("TEMPLO", 1);
