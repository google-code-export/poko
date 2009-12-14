<?php

class poko_utils_ImageFilter extends Enum {
		public static $BRIGHTNESS;
		public static $COLORIZE;
		public static $CONTRAST;
		public static $EDGEDETECT;
		public static $EMBOSS;
		public static $GAUSSIAN_BLUR;
		public static $GRAYSCALE;
		public static $MEAN_REMOVAL;
		public static $NEGATE;
		public static $SELECTIVE_BLUR;
		public static $SMOOTH;
	}
	poko_utils_ImageFilter::$BRIGHTNESS = new poko_utils_ImageFilter("BRIGHTNESS", 2);
	poko_utils_ImageFilter::$COLORIZE = new poko_utils_ImageFilter("COLORIZE", 4);
	poko_utils_ImageFilter::$CONTRAST = new poko_utils_ImageFilter("CONTRAST", 3);
	poko_utils_ImageFilter::$EDGEDETECT = new poko_utils_ImageFilter("EDGEDETECT", 5);
	poko_utils_ImageFilter::$EMBOSS = new poko_utils_ImageFilter("EMBOSS", 6);
	poko_utils_ImageFilter::$GAUSSIAN_BLUR = new poko_utils_ImageFilter("GAUSSIAN_BLUR", 7);
	poko_utils_ImageFilter::$GRAYSCALE = new poko_utils_ImageFilter("GRAYSCALE", 1);
	poko_utils_ImageFilter::$MEAN_REMOVAL = new poko_utils_ImageFilter("MEAN_REMOVAL", 9);
	poko_utils_ImageFilter::$NEGATE = new poko_utils_ImageFilter("NEGATE", 0);
	poko_utils_ImageFilter::$SELECTIVE_BLUR = new poko_utils_ImageFilter("SELECTIVE_BLUR", 8);
	poko_utils_ImageFilter::$SMOOTH = new poko_utils_ImageFilter("SMOOTH", 10);
