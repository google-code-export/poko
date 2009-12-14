<?php

class poko_form_elements_RichtextMode extends Enum {
		public static $ADVANCED;
		public static $FORMAT;
		public static $SIMPLE;
		public static $SIMPLE_TABLES;
	}
	poko_form_elements_RichtextMode::$ADVANCED = new poko_form_elements_RichtextMode("ADVANCED", 3);
	poko_form_elements_RichtextMode::$FORMAT = new poko_form_elements_RichtextMode("FORMAT", 1);
	poko_form_elements_RichtextMode::$SIMPLE = new poko_form_elements_RichtextMode("SIMPLE", 0);
	poko_form_elements_RichtextMode::$SIMPLE_TABLES = new poko_form_elements_RichtextMode("SIMPLE_TABLES", 2);
