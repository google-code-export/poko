<?php

class poko_views_ViewType extends Enum {
		public static $HAXE;
		public static $PHP;
		public static $TEMPLO;
	}
	poko_views_ViewType::$HAXE = new poko_views_ViewType("HAXE", 2);
	poko_views_ViewType::$PHP = new poko_views_ViewType("PHP", 0);
	poko_views_ViewType::$TEMPLO = new poko_views_ViewType("TEMPLO", 1);
