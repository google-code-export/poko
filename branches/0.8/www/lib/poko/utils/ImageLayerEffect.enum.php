<?php

class poko_utils_ImageLayerEffect extends Enum {
	public static $NORMAL;
	public static $OVERLAY;
	public static $REPLACE;
	public static $__constructors = array(1 => 'NORMAL', 2 => 'OVERLAY', 0 => 'REPLACE');
	}
poko_utils_ImageLayerEffect::$NORMAL = new poko_utils_ImageLayerEffect("NORMAL", 1);
poko_utils_ImageLayerEffect::$OVERLAY = new poko_utils_ImageLayerEffect("OVERLAY", 2);
poko_utils_ImageLayerEffect::$REPLACE = new poko_utils_ImageLayerEffect("REPLACE", 0);
