<?php

class poko_utils_ImageType extends Enum {
		public static $BMP;
		public static $GIF;
		public static $IFF;
		public static $JB2;
		public static $JP2;
		public static $JPC;
		public static $JPG;
		public static $JPX;
		public static $PNG;
		public static $PSD;
		public static $SWC;
		public static $SWF;
		public static $TIFF_II;
		public static $TIFF_MM;
		public static $WBMP;
		public static $XBM;
	}
	poko_utils_ImageType::$BMP = new poko_utils_ImageType("BMP", 5);
	poko_utils_ImageType::$GIF = new poko_utils_ImageType("GIF", 0);
	poko_utils_ImageType::$IFF = new poko_utils_ImageType("IFF", 13);
	poko_utils_ImageType::$JB2 = new poko_utils_ImageType("JB2", 11);
	poko_utils_ImageType::$JP2 = new poko_utils_ImageType("JP2", 9);
	poko_utils_ImageType::$JPC = new poko_utils_ImageType("JPC", 8);
	poko_utils_ImageType::$JPG = new poko_utils_ImageType("JPG", 1);
	poko_utils_ImageType::$JPX = new poko_utils_ImageType("JPX", 10);
	poko_utils_ImageType::$PNG = new poko_utils_ImageType("PNG", 2);
	poko_utils_ImageType::$PSD = new poko_utils_ImageType("PSD", 4);
	poko_utils_ImageType::$SWC = new poko_utils_ImageType("SWC", 12);
	poko_utils_ImageType::$SWF = new poko_utils_ImageType("SWF", 3);
	poko_utils_ImageType::$TIFF_II = new poko_utils_ImageType("TIFF_II", 6);
	poko_utils_ImageType::$TIFF_MM = new poko_utils_ImageType("TIFF_MM", 7);
	poko_utils_ImageType::$WBMP = new poko_utils_ImageType("WBMP", 14);
	poko_utils_ImageType::$XBM = new poko_utils_ImageType("XBM", 15);
