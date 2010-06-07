<?php

class poko_utils_MessageType extends Enum {
		public static $DEBUG;
		public static $ERROR;
		public static $MESSAGE;
		public static $WARNING;
	}
	poko_utils_MessageType::$DEBUG = new poko_utils_MessageType("DEBUG", 0);
	poko_utils_MessageType::$ERROR = new poko_utils_MessageType("ERROR", 1);
	poko_utils_MessageType::$MESSAGE = new poko_utils_MessageType("MESSAGE", 2);
	poko_utils_MessageType::$WARNING = new poko_utils_MessageType("WARNING", 3);
