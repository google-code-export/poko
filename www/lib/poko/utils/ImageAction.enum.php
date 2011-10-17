<?php

class poko_utils_ImageAction extends Enum {
	public static $ASPECT;
	public static $CROP;
	public static $CUSTOM;
	public static $FIT;
	public static $RESIZE;
	public static $ROTATE;
	public static $SCALE;
	public static $__constructors = array(2 => 'ASPECT', 5 => 'CROP', 0 => 'CUSTOM', 1 => 'FIT', 3 => 'RESIZE', 6 => 'ROTATE', 4 => 'SCALE');
	}
poko_utils_ImageAction::$ASPECT = new poko_utils_ImageAction("ASPECT", 2);
poko_utils_ImageAction::$CROP = new poko_utils_ImageAction("CROP", 5);
poko_utils_ImageAction::$CUSTOM = new poko_utils_ImageAction("CUSTOM", 0);
poko_utils_ImageAction::$FIT = new poko_utils_ImageAction("FIT", 1);
poko_utils_ImageAction::$RESIZE = new poko_utils_ImageAction("RESIZE", 3);
poko_utils_ImageAction::$ROTATE = new poko_utils_ImageAction("ROTATE", 6);
poko_utils_ImageAction::$SCALE = new poko_utils_ImageAction("SCALE", 4);
